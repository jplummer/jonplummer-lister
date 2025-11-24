<?php
/**
 * Favicon Diagnostic Script
 * Helps identify favicon issues on the live site
 */

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Favicon Diagnostic</title>
  <style>
    body {
      font-family: system-ui, -apple-system, sans-serif;
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
      line-height: 1.6;
    }
    .section {
      background: #f5f5f5;
      padding: 20px;
      margin: 20px 0;
      border-radius: 4px;
    }
    .success { color: #28a745; }
    .error { color: #dc3545; }
    .warning { color: #ffc107; }
    code {
      background: #e9ecef;
      padding: 2px 6px;
      border-radius: 3px;
      font-family: 'Monaco', 'Courier New', monospace;
    }
    .check-item {
      margin: 10px 0;
      padding: 10px;
      background: white;
      border-left: 3px solid #ddd;
    }
    .check-item.pass { border-left-color: #28a745; }
    .check-item.fail { border-left-color: #dc3545; }
    .check-item.warn { border-left-color: #ffc107; }
    img {
      max-width: 100px;
      border: 1px solid #ddd;
      margin: 10px;
    }
  </style>
</head>
<body>
  <h1>Favicon Diagnostic Report</h1>
  
  <?php
  $baseDir = dirname(__DIR__);
  $templatePath = $baseDir . '/lister/templates/index.php';
  $faviconPaths = [
    '32x32' => 'lister/assets/images/2021/02/jp_round-48x48.jpg',
    '192x192' => 'lister/assets/images/2021/02/jp_round.jpg',
    '180x180' => 'lister/assets/images/2021/02/jp_round-180x180.jpg'
  ];
  
  // Check 1: Template file exists
  echo '<div class="section">';
  echo '<h2>1. Template File Check</h2>';
  if (file_exists($templatePath)) {
    echo '<div class="check-item pass">✓ Template file exists: <code>' . htmlspecialchars($templatePath) . '</code></div>';
    
    // Read template and check for favicon links
    $templateContent = file_get_contents($templatePath);
    $hasFavicon = preg_match('/rel=["\']icon["\']/', $templateContent);
    if ($hasFavicon) {
      echo '<div class="check-item pass">✓ Template contains favicon link tags</div>';
      
      // Extract favicon paths from template
      preg_match_all('/href=["\']([^"\']*\.(jpg|png|ico|svg))["\']/', $templateContent, $matches);
      if (!empty($matches[1])) {
        echo '<div class="check-item pass">Found favicon references in template:</div>';
        echo '<ul>';
        foreach ($matches[1] as $path) {
          echo '<li><code>' . htmlspecialchars($path) . '</code></li>';
        }
        echo '</ul>';
      }
    } else {
      echo '<div class="check-item fail">✗ Template does not contain favicon link tags</div>';
    }
  } else {
    echo '<div class="check-item fail">✗ Template file not found: <code>' . htmlspecialchars($templatePath) . '</code></div>';
  }
  echo '</div>';
  
  // Check 2: Favicon files exist
  echo '<div class="section">';
  echo '<h2>2. Favicon File Existence</h2>';
  $allExist = true;
  foreach ($faviconPaths as $size => $path) {
    $fullPath = $baseDir . '/' . $path;
    if (file_exists($fullPath)) {
      $fileSize = filesize($fullPath);
      $fileTime = filemtime($fullPath);
      echo '<div class="check-item pass">';
      echo '✓ <code>' . htmlspecialchars($path) . '</code> exists';
      echo ' (' . number_format($fileSize) . ' bytes, modified: ' . date('Y-m-d H:i:s', $fileTime) . ')';
      echo '</div>';
      
      // Try to display image
      if (strpos($path, '.jpg') !== false || strpos($path, '.png') !== false) {
        $urlPath = '/' . $path;
        echo '<div style="margin-left: 20px;">';
        echo '<img src="' . htmlspecialchars($urlPath) . '" alt="' . $size . '" onerror="this.style.border=\'2px solid red\'">';
        echo '</div>';
      }
    } else {
      echo '<div class="check-item fail">';
      echo '✗ <code>' . htmlspecialchars($path) . '</code> NOT FOUND';
      echo ' (checked: <code>' . htmlspecialchars($fullPath) . '</code>)';
      echo '</div>';
      $allExist = false;
    }
  }
  echo '</div>';
  
  // Check 3: Default favicon.ico
  echo '<div class="section">';
  echo '<h2>3. Default favicon.ico Check</h2>';
  $defaultFaviconPaths = [
    $baseDir . '/favicon.ico',
    $baseDir . '/lister/favicon.ico',
    $baseDir . '/lister/assets/favicon.ico'
  ];
  $foundDefault = false;
  foreach ($defaultFaviconPaths as $defaultPath) {
    if (file_exists($defaultPath)) {
      echo '<div class="check-item warn">';
      echo '⚠ Found default favicon: <code>' . htmlspecialchars($defaultPath) . '</code>';
      echo ' (This might override your custom favicon links)';
      echo '</div>';
      $foundDefault = true;
    }
  }
  if (!$foundDefault) {
    echo '<div class="check-item pass">✓ No default favicon.ico found (good - won\'t conflict)</div>';
  }
  echo '</div>';
  
  // Check 4: URL path resolution
  echo '<div class="section">';
  echo '<h2>4. URL Path Resolution</h2>';
  $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  $baseUrl = dirname($currentUrl);
  echo '<div class="check-item">Current URL: <code>' . htmlspecialchars($currentUrl) . '</code></div>';
  echo '<div class="check-item">Base URL: <code>' . htmlspecialchars($baseUrl) . '</code></div>';
  
  echo '<h3>Favicon URLs (as they would resolve from current page):</h3>';
  foreach ($faviconPaths as $size => $path) {
    // Relative path resolution
    $relativeUrl = $path;
    $absoluteUrl = $baseUrl . '/' . $path;
    echo '<div class="check-item">';
    echo '<strong>' . $size . ':</strong><br>';
    echo 'Relative: <code>' . htmlspecialchars($relativeUrl) . '</code><br>';
    echo 'Absolute: <code>' . htmlspecialchars($absoluteUrl) . '</code>';
    echo '</div>';
  }
  echo '</div>';
  
  // Check 5: Browser cache recommendations
  echo '<div class="section">';
  echo '<h2>5. Browser Cache Recommendations</h2>';
  echo '<div class="check-item warn">';
  echo '<strong>If favicon is still showing old version:</strong><br>';
  echo '<ul>';
  echo '<li>Hard refresh: <code>Ctrl+Shift+R</code> (Windows/Linux) or <code>Cmd+Shift+R</code> (Mac)</li>';
  echo '<li>Clear browser cache for your site</li>';
  echo '<li>Try incognito/private browsing mode</li>';
  echo '<li>Add cache-busting query string to favicon URLs (e.g., <code>?v=2</code>)</li>';
  echo '<li>Check if your server/CDN is caching the favicon</li>';
  echo '</ul>';
  echo '</div>';
  echo '</div>';
  
  // Check 6: Server headers
  echo '<div class="section">';
  echo '<h2>6. Server Information</h2>';
  echo '<div class="check-item">';
  echo 'Document Root: <code>' . htmlspecialchars($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . '</code><br>';
  echo 'Script Name: <code>' . htmlspecialchars($_SERVER['SCRIPT_NAME'] ?? 'N/A') . '</code><br>';
  echo 'Request URI: <code>' . htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A') . '</code>';
  echo '</div>';
  echo '</div>';
  ?>
  
  <div class="section">
    <h2>Next Steps</h2>
    <ol>
      <li>Review the checks above to identify issues</li>
      <li>If files exist but URLs don't work, check if paths should be absolute (starting with <code>/</code>)</li>
      <li>If browser caching is the issue, add cache-busting query strings to favicon URLs</li>
      <li>Check your server's error logs for 404s on favicon requests</li>
      <li>Use browser DevTools Network tab to see what favicon URLs are actually being requested</li>
    </ol>
  </div>
</body>
</html>

