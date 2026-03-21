<?php
/**
 * Lister - Directory Listing Application
 * Main entry point for the application
 */

// Pre-flight checks: verify critical files exist before loading App
$requiredFiles = [
  'lister/includes/App.php' => 'Main application class',
  'lister/includes/ConfigLoader.php' => 'Configuration loader',
  'lister/includes/PathSanitizer.php' => 'Path sanitization helper',
  'lister/includes/IconSymbols.php' => 'File icon Material symbol names',
  'lister/config/default.json' => 'Configuration file',
  'lister/templates/index.php' => 'Main template',
  'lister/includes/DirectoryLister.php' => 'Directory listing class',
  'lister/api.php' => 'API endpoint'
];

$missingFiles = [];
$permissionIssues = [];

foreach ($requiredFiles as $file => $description) {
  $fullPath = __DIR__ . '/' . $file;
  if (!file_exists($fullPath)) {
    $missingFiles[] = ['file' => $file, 'path' => $fullPath, 'description' => $description];
  } elseif (!is_readable($fullPath)) {
    $permissionIssues[] = ['file' => $file, 'path' => $fullPath, 'description' => $description];
  }
}

// If critical files are missing, show installation error
// Also show conflict warning if there are other index files (but only if we're missing files)
if (!empty($missingFiles) || !empty($permissionIssues)) {
  http_response_code(500);
  $installErrorPath = __DIR__ . '/install_error.php';
  if (is_readable($installErrorPath)) {
    define('LISTER_INSTALL_ERROR_RENDER', true);
    include $installErrorPath;
  } else {
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Installation error: required files are missing or unreadable. See INSTALL.md in the repository.\n";
  }
  exit;
}

// Load the main App class
require_once __DIR__ . '/lister/includes/App.php';

try {
  // Initialize and run the application
  $app = new App();
  $app->render();
} catch (Exception $e) {
  // Handle critical errors
  $errorMessage = $e->getMessage();
  $errorType = 'Runtime Error';
  $suggestions = [];
  
  // Categorize errors and provide suggestions
  if (strpos($errorMessage, 'Configuration file not found') !== false) {
    $errorType = 'Installation Error';
    $suggestions = [
      'Make sure you uploaded the entire lister/ folder',
      'Verify lister/config/default.json exists',
      'Check file permissions: chmod 644 lister/config/default.json'
    ];
  } elseif (strpos($errorMessage, 'Invalid configuration file') !== false) {
    $errorType = 'Configuration Error';
    $suggestions = [
      'Check lister/config/default.json for JSON syntax errors',
      'Validate JSON using an online JSON validator',
      'Re-upload default.json from the repository if corrupted'
    ];
  } elseif (strpos($errorMessage, 'Cannot read') !== false) {
    $errorType = 'Permission Error';
    $suggestions = [
      'Check file permissions on the mentioned file or directory',
      'Run: chmod 644 for files, chmod 755 for directories',
      'Verify the web server user has read access'
    ];
  } elseif (strpos($errorMessage, 'Template file not found') !== false) {
    $errorType = 'Installation Error';
    $suggestions = [
      'Make sure lister/templates/index.php exists',
      'Verify the entire lister/ folder was uploaded',
      'Check for nested lister/lister/ folder structure'
    ];
  } elseif (strpos($errorMessage, 'PHP') !== false && strpos($errorMessage, 'required') !== false) {
    $errorType = 'Environment Error';
    $suggestions = [
      'Contact your hosting provider to upgrade PHP version',
      'PHP 7.4 or higher is required',
      'Check your current PHP version in your hosting control panel'
    ];
  } elseif (strpos($errorMessage, 'function not available') !== false) {
    $errorType = 'Environment Error';
    $suggestions = [
      'Contact your hosting provider - required PHP functions are disabled',
      'The function may be disabled in php.ini',
      'Some hosting providers restrict certain functions for security'
    ];
  }
  
  http_response_code(500);
  $runtimeErrorPath = __DIR__ . '/lister/templates/runtime_error.php';
  if (is_readable($runtimeErrorPath)) {
    define('LISTER_RUNTIME_ERROR_RENDER', true);
    include $runtimeErrorPath;
  } else {
    header('Content-Type: text/plain; charset=UTF-8');
    echo $errorType . ': ' . $errorMessage . "\n";
  }
}