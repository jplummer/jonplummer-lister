<?php
/**
 * App - Main application class
 * Handles application initialization, environment checks, and rendering
 */

class App
{
  private $config;
  private $lister;
  private $data;
  private $breadcrumbs;
  private $error;

  public function __construct()
  {
    $this->loadConfiguration();
    $this->checkEnvironment();
    $this->checkSecurity();
    $this->initializeLister();
    $this->processRequest();
  }

  /**
   * Load configuration from file
   */
  private function loadConfiguration()
  {
    $configPath = __DIR__ . '/../config/default.json';
    if (!file_exists($configPath)) {
      throw new Exception('Configuration file not found: lister/config/default.json. Make sure you uploaded the entire lister/ folder.');
    }
    
    if (!is_readable($configPath)) {
      $perms = substr(sprintf('%o', fileperms($configPath)), -4);
      throw new Exception('Cannot read configuration file: lister/config/default.json. Current permissions: ' . $perms . '. Run: chmod 644 lister/config/default.json');
    }
    
    $configContent = file_get_contents($configPath);
    if ($configContent === false) {
      throw new Exception('Failed to read configuration file: lister/config/default.json. Check file permissions.');
    }
    
    $this->config = json_decode($configContent, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception('Invalid configuration file: lister/config/default.json. JSON error: ' . json_last_error_msg() . '. Check the file for syntax errors.');
    }
  }

  /**
   * Check that the environment is suitable for running the application
   */
  private function checkEnvironment()
  {
    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4.0', '<')) {
      throw new Exception('PHP 7.4 or higher is required. Current version: ' . PHP_VERSION . '. Contact your hosting provider to upgrade PHP.');
    }

    // Check required functions
    $requiredFunctions = ['scandir', 'stat', 'is_dir', 'is_file'];
    $missingFunctions = [];
    foreach ($requiredFunctions as $function) {
      if (!function_exists($function)) {
        $missingFunctions[] = $function;
      }
    }
    if (!empty($missingFunctions)) {
      throw new Exception('Required PHP functions are disabled: ' . implode(', ', $missingFunctions) . '. Contact your hosting provider to enable these functions.');
    }

    // Check if we can read the current directory
    if (!is_readable('.')) {
      $perms = is_dir('.') ? substr(sprintf('%o', fileperms('.')), -4) : 'unknown';
      throw new Exception('Cannot read current directory. Current permissions: ' . $perms . '. The web server needs read access to the directory.');
    }
    
    // Check if data directory is writable (if security is enabled)
    if ($this->config['security']['enabled'] ?? false) {
      $dataDir = __DIR__ . '/../data';
      // If directory doesn't exist, check if parent is writable
      if (!is_dir($dataDir)) {
        $parentDir = dirname($dataDir);
        if (!is_writable($parentDir)) {
          $perms = is_dir($parentDir) ? substr(sprintf('%o', fileperms($parentDir)), -4) : 'unknown';
          throw new Exception('Cannot create data directory: lister/data/. Parent directory permissions: ' . $perms . '. Run: chmod 755 lister/ (or create lister/data/ manually with chmod 755)');
        }
      } elseif (!is_writable($dataDir)) {
        $perms = substr(sprintf('%o', fileperms($dataDir)), -4);
        throw new Exception('Data directory is not writable: lister/data/. Current permissions: ' . $perms . '. Run: chmod 755 lister/data/');
      }
    }
  }

  /**
   * Check security (rate limiting, bot detection)
   */
  private function checkSecurity()
  {
    if ($this->config['security']['enabled'] ?? false) {
      $securityPath = __DIR__ . '/Security.php';
      if (!file_exists($securityPath)) {
        throw new Exception('Security class file not found: lister/includes/Security.php. Make sure you uploaded the entire lister/ folder.');
      }
      
      require_once $securityPath;
      $security = new Security($this->config);
      $security->checkRequest();
    }
  }

  /**
   * Initialize the DirectoryLister
   */
  private function initializeLister()
  {
    $listerPath = __DIR__ . '/DirectoryLister.php';
    if (!file_exists($listerPath)) {
      throw new Exception('DirectoryLister class file not found: lister/includes/DirectoryLister.php. Make sure you uploaded the entire lister/ folder.');
    }
    
    require_once $listerPath;
    $this->lister = new DirectoryLister($this->config);
  }

  /**
   * Process the current request
   */
  private function processRequest()
  {
    try {
      $this->data = $this->lister->scanDirectory();
      $this->breadcrumbs = $this->lister->getBreadcrumbs();
    } catch (Exception $e) {
      $this->error = $e->getMessage();
      $this->data = null;
      $this->breadcrumbs = [];
    }
  }

  /**
   * Get icon symbol for file type
   */
  public function getIconSymbol($icon)
  {
    $icons = [
      // Folders
      'folder' => '📁',
      
      // Basic file types
      'file' => '📄',
      
      // Media types
      'image' => '🖼️',
      'video' => '🎬',
      'audio' => '🎵',
      
      // Documents and text
      'document' => '📄',
      'text' => '📝',
      'pdf' => '📕',
      'book' => '📚',
      
      // Code and development
      'code' => '💻',
      'web' => '🌐',
      'exec' => '⚙️',
      
      // Data and office
      'spreadsheet' => '📊',
      'sheet' => '📊',
      'presentation' => '📽️',
      'slide' => '📽️',
      
      // Archives and storage
      'archive' => '📦',
      
      // System and fonts
      'font' => '🔤',
      'config' => '⚙️',
      'backup' => '💾',
      'database' => '🗄️',
      'cad' => '📐',
      'ebook' => '📖',
      'game' => '🎮'
    ];
    
    return $icons[$icon] ?? '📄';
  }

  /**
   * Get deployment timestamp for verification
   */
  private function getDeploymentTimestamp()
  {
    // Read from .deploy-timestamp file (created during deployment)
    $timestampFile = __DIR__ . '/../.deploy-timestamp';
    if (file_exists($timestampFile)) {
      $timestamp = trim(file_get_contents($timestampFile));
      if (!empty($timestamp)) {
        return $timestamp;
      }
    }
    
    return null;
  }

  /**
   * Render the application
   */
  public function render()
  {
    $templatePath = __DIR__ . '/../templates/index.php';
    if (!file_exists($templatePath)) {
      throw new Exception('Template file not found: lister/templates/index.php. Make sure you uploaded the entire lister/ folder.');
    }
    
    if (!is_readable($templatePath)) {
      $perms = substr(sprintf('%o', fileperms($templatePath)), -4);
      throw new Exception('Cannot read template file: lister/templates/index.php. Current permissions: ' . $perms . '. Run: chmod 644 lister/templates/index.php');
    }

    // Extract variables for template
    $config = $this->config;
    $data = $this->data;
    $breadcrumbs = $this->breadcrumbs;
    $error = $this->error;
    $deploymentTimestamp = $this->getDeploymentTimestamp();
    
    // Make getIconSymbol function available to template
    $getIconSymbol = [$this, 'getIconSymbol'];

    // Include the template
    include $templatePath;
  }

  /**
   * Get configuration
   */
  public function getConfig()
  {
    return $this->config;
  }

  /**
   * Get data
   */
  public function getData()
  {
    return $this->data;
  }

  /**
   * Get breadcrumbs
   */
  public function getBreadcrumbs()
  {
    return $this->breadcrumbs;
  }

  /**
   * Get error
   */
  public function getError()
  {
    return $this->error;
  }
}
