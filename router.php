<?php
/**
 * Simple router for PHP built-in server
 * Routes directory requests to index.php
 */

$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Decode URL-encoded characters (e.g., %20 for spaces)
$path = urldecode($path);

// If it's a request to the root, serve the main app
if ($path === '/' || $path === '/index.php') {
  require_once __DIR__ . '/index.php';
  return;
}

// If it's a request to a directory or file that doesn't exist as a physical file,
// route it through the main app
if (!file_exists(__DIR__ . $path) || is_dir(__DIR__ . $path)) {
  require_once __DIR__ . '/index.php';
  return;
}

// For physical files that exist, serve them directly
return false;
?>
