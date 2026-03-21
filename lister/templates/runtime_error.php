<?php
/**
 * Fatal / bootstrap error after App.php loads (config, template, environment).
 * Included from index.php only.
 */
if (!defined('LISTER_RUNTIME_ERROR_RENDER')) {
  http_response_code(403);
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($errorType) ?> - Directory Listing</title>
  <style>
    body { 
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
      margin: 0; 
      padding: 40px; 
      background: #f5f5f5;
      line-height: 1.6;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      background: white;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .error-header {
      background: #dc3545;
      color: white;
      padding: 24px 32px;
    }
    .error-header h1 {
      margin: 0;
      font-size: 24px;
      font-weight: 600;
    }
    .error-content {
      padding: 32px;
    }
    .error-message {
      background: #f8d7da;
      border-left: 4px solid #dc3545;
      padding: 16px;
      margin: 16px 0;
      color: #721c24;
    }
    .error-message code {
      background: rgba(0,0,0,0.1);
      padding: 2px 6px;
      border-radius: 3px;
      font-family: "Courier New", monospace;
      font-size: 14px;
    }
    .suggestions {
      background: #d1ecf1;
      border-left: 4px solid #0c5460;
      padding: 16px;
      margin: 16px 0;
    }
    .suggestions h3 {
      margin: 0 0 12px 0;
      color: #0c5460;
      font-size: 16px;
    }
    .suggestions ul {
      margin: 8px 0;
      padding-left: 24px;
    }
    .suggestions li {
      margin: 4px 0;
      color: #0c5460;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="error-header">
      <h1><?= htmlspecialchars($errorType) ?></h1>
    </div>
    <div class="error-content">
      <div class="error-message">
        <p><strong>Error:</strong> <?= htmlspecialchars($errorMessage) ?></p>
      </div>
      
      <?php if (!empty($suggestions)): ?>
      <div class="suggestions">
        <h3>Suggested Solutions</h3>
        <ul>
          <?php foreach ($suggestions as $suggestion): ?>
          <li><?= htmlspecialchars($suggestion) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>
      
      <p>For more help, see <code>INSTALL.md</code> or <code>docs/notes.md</code> in the repository.</p>
    </div>
  </div>
</body>
</html>
