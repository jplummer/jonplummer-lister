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

  public function __construct($config, $basePath = null)
  {
    $this->config = $config;
    $this->basePath = $basePath ?: $_SERVER['DOCUMENT_ROOT'];
    $this->currentPath = $this->getCurrentPath();
  }

  /**
   * Get the current directory path from URL
   */
  private function getCurrentPath()
  {
    $requestUri = $_SERVER['REQUEST_URI'];
    $scriptName = $_SERVER['SCRIPT_NAME'];
    
    // Remove query string
    $path = parse_url($requestUri, PHP_URL_PATH);
    
    // Remove script name from path
    $path = str_replace(dirname($scriptName), '', $path);
    $path = ltrim($path, '/');
    
    // Security: prevent directory traversal
    $path = str_replace(['../', '..\\'], '', $path);
    
    // If path is empty or just the script name, use base path
    if (empty($path) || $path === basename($scriptName)) {
      return $this->basePath;
    }
    
    $fullPath = $this->basePath . '/' . $path;
    
    // If the path is a file, get its directory
    if (is_file($fullPath)) {
      return dirname($fullPath);
    }
    
    return $fullPath;
  }

  /**
   * Scan directory and return file/directory information
   */
  public function scanDirectory()
  {
    if (!is_dir($this->currentPath)) {
      throw new Exception('Directory not found: ' . $this->currentPath);
    }

    $items = scandir($this->currentPath);
    $this->files = [];
    $this->directories = [];

    foreach ($items as $item) {
      if ($item === '.' || $item === '..') {
        continue;
      }

      $fullPath = $this->currentPath . '/' . $item;
      $itemInfo = $this->getItemInfo($item, $fullPath);

      if (is_dir($fullPath)) {
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
      'icon' => $this->getFileIcon($name, $isDir)
    ];
  }

  /**
   * Get URL for file or directory
   */
  private function getItemUrl($name)
  {
    $currentUrl = $_SERVER['REQUEST_URI'];
    $currentUrl = rtrim($currentUrl, '/');
    return $currentUrl . '/' . urlencode($name);
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
   * Get appropriate icon for file type
   */
  private function getFileIcon($name, $isDir)
  {
    if ($isDir) {
      return 'folder';
    }
    
    $extension = $this->getFileExtension($name);
    
    // Common file type icons
    $iconMap = [
      // Images
      'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image',
      'svg' => 'image', 'webp' => 'image', 'bmp' => 'image', 'ico' => 'image',
      
      // Documents
      'pdf' => 'pdf', 'doc' => 'document', 'docx' => 'document',
      'txt' => 'text', 'rtf' => 'document', 'odt' => 'document',
      
      // Archives
      'zip' => 'archive', 'rar' => 'archive', '7z' => 'archive',
      'tar' => 'archive', 'gz' => 'archive',
      
      // Code
      'php' => 'code', 'js' => 'code', 'css' => 'code', 'html' => 'code',
      'htm' => 'code', 'xml' => 'code', 'json' => 'code',
      
      // Audio
      'mp3' => 'audio', 'wav' => 'audio', 'ogg' => 'audio', 'm4a' => 'audio',
      
      // Video
      'mp4' => 'video', 'avi' => 'video', 'mov' => 'video', 'wmv' => 'video',
      
      // Spreadsheets
      'xls' => 'spreadsheet', 'xlsx' => 'spreadsheet', 'csv' => 'spreadsheet',
      
      // Presentations
      'ppt' => 'presentation', 'pptx' => 'presentation'
    ];
    
    return $iconMap[$extension] ?? 'file';
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
