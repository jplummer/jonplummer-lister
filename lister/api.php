<?php
/**
 * API endpoint for directory listing
 * Handles AJAX requests for expandable directory navigation
 */

// Load configuration
$config = json_decode(file_get_contents(__DIR__ . '/config/default.json'), true);

// Load DirectoryLister class
require_once __DIR__ . '/includes/DirectoryLister.php';

// Set JSON response header
header('Content-Type: application/json');

try {
    // Get the requested path from query parameter
    $requestedPath = $_GET['path'] ?? '';
    
    // Security: prevent directory traversal
    $requestedPath = str_replace(['../', '..\\'], '', $requestedPath);
    
    // Build full path - handle both relative and absolute paths
    $basePath = $_SERVER['DOCUMENT_ROOT'];
    
    // If path starts with basePath, use it directly
    if (strpos($requestedPath, $basePath) === 0) {
        $fullPath = $requestedPath;
    } else {
        // Otherwise, treat as relative to basePath
        $fullPath = $basePath . '/' . ltrim($requestedPath, '/');
    }
    
    // Ensure the path is within our base directory
    $realBasePath = realpath($basePath);
    $realFullPath = realpath($fullPath);
    
    if (!$realFullPath || strpos($realFullPath, $realBasePath) !== 0) {
        throw new Exception('Access denied: Path outside base directory');
    }
    
    // Check if path exists and is a directory
    if (!is_dir($fullPath)) {
        throw new Exception('Directory not found: ' . $requestedPath);
    }
    
    // Create DirectoryLister instance
    $lister = new DirectoryLister($config, $basePath);
    
    // Scan the requested directory
    $result = $lister->scanDirectory($fullPath);
    
    // Return JSON response
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
