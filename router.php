<?php
/**
 * Simple router for PHP built-in server
 * Routes directory requests to index.php
 */

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Security: prevent directory traversal (before decoding)
$path = str_replace(['../', '..\\'], '', $path);

// Decode URL-encoded characters (e.g., %20 for spaces)
$path = urldecode($path);

// Security: prevent directory traversal again after decoding
$path = str_replace(['../', '..\\'], '', $path);

// If it's a request to the root, serve the main app
if ($path === '/' || $path === '/index.php') {
  require_once __DIR__ . '/index.php';
  return;
}

// Build full path and verify it's within the document root
$fullPath = __DIR__ . $path;
$realPath = realpath($fullPath);
$realBase = realpath(__DIR__);

// Security: ensure the resolved path is within the base directory
if ($realPath === false || ($realBase !== false && strpos($realPath, $realBase) !== 0)) {
  // Path outside base directory - route through main app for proper handling
  require_once __DIR__ . '/index.php';
  return;
}

// If it's a request to a directory or file that doesn't exist as a physical file,
// route it through the main app
if (!file_exists($fullPath) || is_dir($fullPath)) {
  require_once __DIR__ . '/index.php';
  return;
}

// For physical files that exist, serve them directly
return false;
?>
