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
      throw new Exception('Configuration file not found: ' . $configPath);
    }
    
    $this->config = json_decode(file_get_contents($configPath), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
      throw new Exception('Invalid configuration file: ' . json_last_error_msg());
    }
  }

  /**
   * Check that the environment is suitable for running the application
   */
  private function checkEnvironment()
  {
    // Check PHP version
    if (version_compare(PHP_VERSION, '7.4.0', '<')) {
      throw new Exception('PHP 7.4 or higher is required. Current version: ' . PHP_VERSION);
    }

    // Check required functions
    $requiredFunctions = ['scandir', 'stat', 'is_dir', 'is_file'];
    foreach ($requiredFunctions as $function) {
      if (!function_exists($function)) {
        throw new Exception('Required PHP function not available: ' . $function);
      }
    }

    // Check if we can read the current directory
    if (!is_readable('.')) {
      throw new Exception('Cannot read current directory');
    }
  }

  /**
   * Check security (rate limiting, bot detection)
   */
  private function checkSecurity()
  {
    if ($this->config['security']['enabled'] ?? false) {
      require_once __DIR__ . '/Security.php';
      $security = new Security($this->config);
      $security->checkRequest();
    }
  }

  /**
   * Initialize the DirectoryLister
   */
  private function initializeLister()
  {
    require_once __DIR__ . '/DirectoryLister.php';
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
   * Get git commit hash for deployment verification
   */
  private function getDeploymentHash()
  {
    // Try to read from .git-commit file (created during deployment)
    $commitFile = __DIR__ . '/../.git-commit';
    if (file_exists($commitFile)) {
      $hash = trim(file_get_contents($commitFile));
      if (!empty($hash)) {
        return substr($hash, 0, 7); // Return short hash
      }
    }
    
    // Fallback: try to get from git directly (if available on server)
    $gitDir = __DIR__ . '/../../.git';
    if (is_dir($gitDir)) {
      $hash = @shell_exec('cd ' . escapeshellarg(dirname($gitDir)) . ' && git rev-parse --short HEAD 2>/dev/null');
      if ($hash) {
        return trim($hash);
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
      throw new Exception('Template file not found: ' . $templatePath);
    }

    // Extract variables for template
    $config = $this->config;
    $data = $this->data;
    $breadcrumbs = $this->breadcrumbs;
    $error = $this->error;
    $deploymentHash = $this->getDeploymentHash();
    
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
