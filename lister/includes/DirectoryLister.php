<?php
/**
 * DirectoryLister - Core directory listing functionality
 * Handles directory scanning, file type detection, and data formatting
 */

class DirectoryLister
{
  private $config;
  private $currentPath;
  private $basePath;
  private $files = [];
  private $directories = [];
  private $extensionTypes = null;

  public function __construct($config, $basePath = null)
  {
    $this->config = $config;
    $rawBasePath = $basePath ?: $_SERVER['DOCUMENT_ROOT'];
    // Normalize basePath to ensure consistent path comparison
    $this->basePath = realpath($rawBasePath) ?: $rawBasePath;
    $this->currentPath = $this->getCurrentPath();
  }

  /**
   * Get the current directory path from URL
   */
  private function getCurrentPath()
  {
    $requestUri = $_SERVER['REQUEST_URI'];
    
    // Remove query string
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    // Remove leading slash
    $path = ltrim($path, '/');
    
    // Security: prevent directory traversal (before decoding)
    $path = str_replace(['../', '..\\'], '', $path);
    
    // Decode URL-encoded characters (e.g., %20 for spaces)
    $path = urldecode($path);
    
    // Security: prevent directory traversal again after decoding
    $path = str_replace(['../', '..\\'], '', $path);
    
    // If path is empty, use base path
    if (empty($path)) {
      return $this->basePath;
    }
    
    $fullPath = $this->basePath . '/' . $path;
    
    // If the path is a file, get its directory
    if (is_file($fullPath)) {
      return dirname($fullPath);
    }
    
    // If the path is a directory, return it directly
    if (is_dir($fullPath)) {
      return $fullPath;
    }
    
    // If neither file nor directory exists, return base path
    return $this->basePath;
  }

  /**
   * Scan directory and return file/directory information
   */
  public function scanDirectory($path = null)
  {
    $targetPath = $path ?: $this->currentPath;
    
    if (!is_dir($targetPath)) {
      throw new Exception('Directory not found: ' . $targetPath);
    }
    
    // Normalize the target path (resolve symlinks, etc.)
    $normalizedPath = realpath($targetPath);
    if ($normalizedPath === false) {
      $normalizedPath = $targetPath;
    }
    
    // Update currentPath to the directory being scanned
    // This ensures getItemUrl() generates correct URLs for files in this directory
    $this->currentPath = $normalizedPath;

    $items = scandir($normalizedPath);
    $this->files = [];
    $this->directories = [];

    foreach ($items as $item) {
      if ($item === '.' || $item === '..') {
        continue;
      }

      // Check if file should be hidden
      if ($this->shouldHideFile($item)) {
        error_log("Hiding item: $item");
        continue;
      }

      // Use normalized path for consistency
      $fullPath = $normalizedPath . '/' . $item;
      $itemInfo = $this->getItemInfo($item, $fullPath);

      if (is_dir($fullPath)) {
        // Check if directory is empty
        $dirContents = scandir($fullPath);
        $hasVisibleItems = false;
        
        foreach ($dirContents as $dirItem) {
          if ($dirItem === '.' || $dirItem === '..') {
            continue;
          }
          if (!$this->shouldHideFile($dirItem)) {
            $hasVisibleItems = true;
            break;
          }
        }
        
        // If directory is empty, mark it as such
        if (!$hasVisibleItems) {
          $itemInfo['type'] = 'Empty folder';
          $itemInfo['is_empty'] = true;
        }
        
        $this->directories[] = $itemInfo;
      } else {
        $this->files[] = $itemInfo;
      }
    }

    // Sort items
    $this->sortItems();
    
    return [
      'directories' => $this->directories,
      'files' => $this->files,
      'current_path' => $this->currentPath,
      'parent_path' => $this->getParentPath()
    ];
  }

  /**
   * Check if a file should be hidden based on configuration
   */
  private function shouldHideFile($filename)
  {
    $hidingConfig = $this->config['file_hiding'] ?? [];
    
    // Check dotfiles
    if (($hidingConfig['hide_dotfiles'] ?? true) && strpos($filename, '.') === 0) {
      return true;
    }
    
    // Check sensitive files
    if ($hidingConfig['hide_sensitive_files'] ?? true) {
      $sensitivePatterns = $hidingConfig['sensitive_patterns'] ?? [];
      foreach ($sensitivePatterns as $pattern) {
        if ($this->matchesPattern($filename, $pattern)) {
          return true;
        }
      }
    }
    
    // Check OS cruft
    if ($hidingConfig['hide_os_cruft'] ?? true) {
      $osCruftPatterns = $hidingConfig['os_cruft_patterns'] ?? [];
      foreach ($osCruftPatterns as $pattern) {
        if ($this->matchesPattern($filename, $pattern)) {
          return true;
        }
      }
    }
    
    // Check app files
    if ($hidingConfig['hide_app_files'] ?? true) {
      $appFilesPatterns = $hidingConfig['app_files_patterns'] ?? [];
      foreach ($appFilesPatterns as $pattern) {
        if ($this->matchesPattern($filename, $pattern)) {
          return true;
        }
      }
    }
    
    return false;
  }

  /**
   * Check if filename matches a pattern (supports wildcards)
   */
  private function matchesPattern($filename, $pattern)
  {
    // Handle directory patterns (ending with /)
    if (substr($pattern, -1) === '/') {
      $dirPattern = substr($pattern, 0, -1);
      return $filename === $dirPattern;
    }
    
    // Convert wildcard pattern to regex
    $regex = str_replace(['*', '.'], ['.*', '\.'], $pattern);
    $regex = '/^' . $regex . '$/i';
    
    return preg_match($regex, $filename);
  }

  /**
   * Get detailed information about a file or directory
   */
  private function getItemInfo($name, $fullPath)
  {
    $stat = stat($fullPath);
    $isDir = is_dir($fullPath);
    
    return [
      'name' => $name,
      'path' => $fullPath,
      'url' => $this->getItemUrl($name),
      'is_directory' => $isDir,
      'size' => $isDir ? null : $stat['size'],
      'size_formatted' => $isDir ? '-' : $this->formatFileSize($stat['size']),
      'modified' => $stat['mtime'],
      'modified_formatted' => $this->formatDate($stat['mtime']),
      'permissions' => $this->formatPermissions($stat['mode']),
      'extension' => $isDir ? null : $this->getFileExtension($name),
      'mime_type' => $isDir ? null : $this->getMimeType($fullPath),
      'type' => $isDir ? 'Folder' : $this->getFileType($name),
      'icon' => $this->getFileIcon($name, $isDir)
    ];
  }

  /**
   * Get URL for file or directory
   */
  private function getItemUrl($name)
  {
    // For expandable directories, we'll use data attributes instead of URLs
    // Files will still have direct URLs
    
    // Ensure both paths are normalized (basePath is normalized in constructor,
    // currentPath is normalized in scanDirectory)
    $normalizedBase = rtrim($this->basePath, '/');
    $normalizedCurrent = rtrim($this->currentPath, '/');
    
    // Safety check: ensure currentPath is valid
    if (empty($normalizedCurrent) || !is_dir($normalizedCurrent)) {
      // Fallback to base path if currentPath is invalid
      $normalizedCurrent = $normalizedBase;
    }
    
    // Build URL based on current path relative to base path
    $relativePath = '';
    if ($normalizedCurrent !== $normalizedBase) {
      // Get the relative path from base to current directory
      // Both paths should already be normalized, but verify with realpath
      $realBase = realpath($normalizedBase);
      $realCurrent = realpath($normalizedCurrent);
      
      if ($realBase && $realCurrent) {
        // Both paths exist and are normalized
        if (strpos($realCurrent, $realBase) === 0) {
          // Current path is within base path
          $relativePath = substr($realCurrent, strlen($realBase));
          $relativePath = trim($relativePath, '/');
        }
      }
      
      // Fallback: if realpath failed or paths don't match, use string replacement
      if (empty($relativePath)) {
        $relativePath = str_replace($normalizedBase, '', $normalizedCurrent);
        $relativePath = trim($relativePath, '/');
      }
    }
    
    // Construct URL path - encode each path segment
    $urlParts = [];
    if (!empty($relativePath)) {
      // Split path and encode each segment
      $pathSegments = explode('/', $relativePath);
      foreach ($pathSegments as $segment) {
        if (!empty($segment)) {
          $urlParts[] = rawurlencode($segment);
        }
      }
    }
    // Add the filename (encoded)
    $urlParts[] = rawurlencode($name);
    
    // Always return an absolute URL starting with /
    $url = '/' . implode('/', $urlParts);
    
    // Safety check: ensure URL doesn't contain API endpoint patterns
    if (strpos($url, 'api.php') !== false || strpos($url, 'lister/api') !== false) {
      error_log("Warning: getItemUrl() generated invalid URL: $url for file: $name in path: $normalizedCurrent");
      // Fallback: return just the filename if URL construction failed
      return '/' . rawurlencode($name);
    }
    
    return $url;
  }

  /**
   * Format file size in human-readable format
   */
  private function formatFileSize($bytes)
  {
    if ($bytes == 0) return '0 B';
    
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $factor = floor(log($bytes, 1024));
    
    return round($bytes / pow(1024, $factor), 1) . ' ' . $units[$factor];
  }

  /**
   * Format date in readable format
   */
  private function formatDate($timestamp)
  {
    return date('Y-m-d H:i', $timestamp);
  }

  /**
   * Format file permissions
   */
  private function formatPermissions($mode)
  {
    $info = '';
    
    $info .= (($mode & 0x0100) ? 'r' : '-');
    $info .= (($mode & 0x0080) ? 'w' : '-');
    $info .= (($mode & 0x0040) ? 'x' : '-');
    $info .= (($mode & 0x0020) ? 'r' : '-');
    $info .= (($mode & 0x0010) ? 'w' : '-');
    $info .= (($mode & 0x0008) ? 'x' : '-');
    $info .= (($mode & 0x0004) ? 'r' : '-');
    $info .= (($mode & 0x0002) ? 'w' : '-');
    $info .= (($mode & 0x0001) ? 'x' : '-');
    
    return $info;
  }

  /**
   * Get file extension
   */
  private function getFileExtension($filename)
  {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  }

  /**
   * Get MIME type for file
   */
  private function getMimeType($filepath)
  {
    if (function_exists('mime_content_type')) {
      return mime_content_type($filepath);
    }
    
    if (function_exists('finfo_file')) {
      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      return finfo_file($finfo, $filepath);
    }
    
    return 'application/octet-stream';
  }

  /**
   * Load extension types from JSON file
   * 
   * Uses dyne/file-extension-list (https://github.com/dyne/file-extension-list)
   * for comprehensive file extension to type mapping
   */
  private function loadExtensionTypes()
  {
    if ($this->extensionTypes !== null) {
      return;
    }
    
    $extensionsFile = __DIR__ . '/../config/extensions.json';
    if (file_exists($extensionsFile)) {
      $jsonData = file_get_contents($extensionsFile);
      $this->extensionTypes = json_decode($jsonData, true) ?: [];
    } else {
      $this->extensionTypes = [];
    }
  }

  /**
   * Get descriptive file type from extension
   */
  private function getFileType($filename)
  {
    $this->loadExtensionTypes();
    
    $extension = $this->getFileExtension($filename);
    if (empty($extension)) {
      return 'File';
    }
    
    // Check if we have type information for this extension
    if (isset($this->extensionTypes[$extension])) {
      $categories = $this->extensionTypes[$extension];
      // Return the first category, or a more descriptive name
      $category = $categories[0];
      
      // Convert category to more descriptive names
      $typeMap = [
        'code' => 'Code file',
        'image' => 'Image',
        'video' => 'Video',
        'audio' => 'Audio',
        'archive' => 'Archive',
        'document' => 'Document',
        'text' => 'Text file',
        'data' => 'Data file',
        'font' => 'Font',
        'executable' => 'Executable',
        'system' => 'System file',
        'web' => 'Web file',
        'presentation' => 'Presentation',
        'spreadsheet' => 'Spreadsheet',
        'database' => 'Database',
        'cad' => 'CAD file',
        'ebook' => 'E-book',
        'game' => 'Game file',
        'config' => 'Configuration',
        'backup' => 'Backup'
      ];
      
      // Special handling for specific extensions that need better categorization
      $specialTypes = [
        'pdf' => 'Document',
        'doc' => 'Document',
        'docx' => 'Document',
        'odt' => 'Document',
        'rtf' => 'Document',
        'txt' => 'Text file',
        'md' => 'Text file',
        'log' => 'Text file'
      ];
      
      if (isset($specialTypes[$extension])) {
        return $specialTypes[$extension];
      }
      
      return $typeMap[$category] ?? ucfirst($category);
    }
    
    // Fallback to extension-based naming
    return ucfirst($extension) . ' file';
  }

  /**
   * Get appropriate icon for file type
   */
  private function getFileIcon($name, $isDir)
  {
    if ($isDir) {
      return 'folder';
    }
    
    $this->loadExtensionTypes();
    $extension = $this->getFileExtension($name);
    
    if (empty($extension)) {
      return 'file';
    }
    
    // Check if we have type information for this extension
    if (isset($this->extensionTypes[$extension])) {
      $categories = $this->extensionTypes[$extension];
      $category = $categories[0]; // Use the first category
      
      // Map categories to icon types
      $categoryToIconMap = [
        'code' => 'code',
        'image' => 'image',
        'video' => 'video',
        'audio' => 'audio',
        'archive' => 'archive',
        'book' => 'book',
        'exec' => 'exec',
        'web' => 'web',
        'sheet' => 'sheet',
        'text' => 'text',
        'font' => 'font',
        'slide' => 'slide'
      ];
      
      return $categoryToIconMap[$category] ?? 'file';
    }
    
    // Fallback for unknown extensions
    return 'file';
  }

  /**
   * Sort items based on configuration
   */
  private function sortItems()
  {
    $sortBy = $this->config['display']['default_sort'] ?? 'name';
    $sortDir = $this->config['display']['sort_direction'] ?? 'asc';
    
    $sortFunction = function($a, $b) use ($sortBy, $sortDir) {
      $valueA = $a[$sortBy] ?? '';
      $valueB = $b[$sortBy] ?? '';
      
      if ($sortBy === 'size') {
        $valueA = $a['size'] ?? 0;
        $valueB = $b['size'] ?? 0;
      }
      
      if ($sortBy === 'modified') {
        $valueA = $a['modified'] ?? 0;
        $valueB = $b['modified'] ?? 0;
      }
      
      $result = strcasecmp($valueA, $valueB);
      return $sortDir === 'desc' ? -$result : $result;
    };
    
    usort($this->directories, $sortFunction);
    usort($this->files, $sortFunction);
  }

  /**
   * Get parent directory path
   */
  private function getParentPath()
  {
    $parent = dirname($this->currentPath);
    if ($parent === $this->basePath) {
      return null; // Already at root
    }
    return $parent;
  }

  /**
   * Get breadcrumb navigation
   */
  public function getBreadcrumbs()
  {
    $breadcrumbs = [];
    $path = $this->currentPath;
    $basePath = $this->basePath;
    
    while ($path !== $basePath && $path !== dirname($basePath)) {
      $name = basename($path);
      $breadcrumbs[] = [
        'name' => $name,
        'path' => $path,
        'url' => $this->getItemUrl($name)
      ];
      $path = dirname($path);
    }
    
    $breadcrumbs[] = [
      'name' => 'Home',
      'path' => $basePath,
      'url' => '/'
    ];
    
    return array_reverse($breadcrumbs);
  }
}

