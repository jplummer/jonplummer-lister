<?php
/**
 * Installation error page (missing or unreadable required files).
 * Included from index.php only; not meant to be opened directly.
 */
if (!defined('LISTER_INSTALL_ERROR_RENDER')) {
  http_response_code(403);
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Installation Error - Directory Listing</title>
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
    .error-section {
      margin-bottom: 24px;
    }
    .error-section h2 {
      margin: 0 0 12px 0;
      font-size: 18px;
      color: #dc3545;
    }
    .error-section p {
      margin: 8px 0;
      color: #333;
    }
    .file-list {
      background: #f8f9fa;
      border-left: 4px solid #dc3545;
      padding: 16px;
      margin: 16px 0;
    }
    .file-list code {
      background: #e9ecef;
      padding: 2px 6px;
      border-radius: 3px;
      font-family: "Courier New", monospace;
      font-size: 14px;
    }
    .solution {
      background: #d1ecf1;
      border-left: 4px solid #0c5460;
      padding: 16px;
      margin: 16px 0;
    }
    .solution h3 {
      margin: 0 0 8px 0;
      color: #0c5460;
      font-size: 16px;
    }
    .solution ol {
      margin: 8px 0;
      padding-left: 24px;
    }
    .solution li {
      margin: 4px 0;
    }
    .command {
      background: #2d2d2d;
      color: #f8f8f2;
      padding: 12px;
      border-radius: 4px;
      font-family: "Courier New", monospace;
      font-size: 14px;
      margin: 8px 0;
      overflow-x: auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="error-header">
      <h1>Installation Error</h1>
    </div>
    <div class="error-content">
      <?php if (!empty($missingFiles)): ?>
      <div class="error-section">
        <h2>Missing Required Files</h2>
        <p>The following required files are missing from your installation:</p>
        <div class="file-list">
          <?php foreach ($missingFiles as $item): ?>
          <p><strong><?= htmlspecialchars($item['description']) ?>:</strong><br>
          <code><?= htmlspecialchars($item['file']) ?></code></p>
          <?php endforeach; ?>
        </div>
        <div class="solution">
          <h3>Solution</h3>
          <ol>
            <li>Make sure you uploaded the entire <code>lister/</code> folder to your web server</li>
            <li>Verify the file structure matches the installation instructions</li>
            <li>Re-upload any missing files from the repository</li>
            <li>Check that file paths are correct (no nested <code>lister/lister/</code> folders)</li>
          </ol>
          <p><strong>Expected structure:</strong></p>
          <div class="command">
your-domain.com/<br>
├── index.php<br>
├── install_error.php<br>
├── .htaccess<br>
└── lister/<br>
&nbsp;&nbsp;&nbsp;&nbsp;├── api.php<br>
&nbsp;&nbsp;&nbsp;&nbsp;├── config/<br>
&nbsp;&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;└── default.json<br>
&nbsp;&nbsp;&nbsp;&nbsp;├── includes/<br>
&nbsp;&nbsp;&nbsp;&nbsp;│&nbsp;&nbsp;&nbsp;&nbsp;├── App.php, DirectoryLister.php, …<br>
&nbsp;&nbsp;&nbsp;&nbsp;└── templates/<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;└── index.php
          </div>
        </div>
      </div>
      <?php endif; ?>
      
      <?php if (!empty($permissionIssues)): ?>
      <div class="error-section">
        <h2>Permission Issues</h2>
        <p>The following files cannot be read due to incorrect permissions:</p>
        <div class="file-list">
          <?php foreach ($permissionIssues as $item): ?>
          <p><strong><?= htmlspecialchars($item['description']) ?>:</strong><br>
          <code><?= htmlspecialchars($item['file']) ?></code></p>
          <?php endforeach; ?>
        </div>
        <div class="solution">
          <h3>Solution</h3>
          <p>Set correct file permissions using these commands:</p>
          <?php foreach ($permissionIssues as $item): ?>
          <div class="command">chmod 644 <?= htmlspecialchars($item['file']) ?></div>
          <?php endforeach; ?>
          <p>Or set permissions for all config files at once:</p>
          <div class="command">chmod 644 lister/config/*.json</div>
        </div>
      </div>
      <?php endif; ?>
      
      
      <div class="error-section">
        <p>For complete installation instructions, see <code>INSTALL.md</code> in the repository.</p>
      </div>
    </div>
  </div>
</body>
</html>
