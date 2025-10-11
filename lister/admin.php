<?php
/**
 * Security Admin - View security logs and statistics
 * Access: yourdomain.com/lister/admin.php
 */

// Simple password protection (change this!)
$adminPassword = 'change_this_password';

// Check if password is provided
if (!isset($_POST['password']) || $_POST['password'] !== $adminPassword) {
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Admin</title>
    <style>
      body { font-family: Arial, sans-serif; margin: 40px; max-width: 400px; }
      .form { background: #f8f9fa; padding: 20px; border-radius: 4px; }
      input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; }
      button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
    </style>
  </head>
  <body>
    <div class="form">
      <h2>Security Admin</h2>
      <form method="post">
        <input type="password" name="password" placeholder="Admin Password" required>
        <button type="submit">Login</button>
      </form>
    </div>
  </body>
  </html>
  <?php
  exit;
}

// Load configuration and security class
$config = json_decode(file_get_contents(__DIR__ . '/config/default.json'), true);
require_once __DIR__ . '/includes/Security.php';

$security = new Security($config);
$stats = $security->getStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Security Admin - Lister</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .stats { background: #e9ecef; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
    .incident { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 5px 0; border-radius: 4px; }
    .blocked { background: #f8d7da; border: 1px solid #f5c6cb; }
    .bot { background: #d1ecf1; border: 1px solid #bee5eb; }
    .rate-limit { background: #fff3cd; border: 1px solid #ffeaa7; }
    .suspicious { background: #f8d7da; border: 1px solid #f5c6cb; }
    pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
    .refresh { float: right; }
  </style>
</head>
<body>
  <h1>Security Admin Dashboard</h1>
  <a href="?refresh=1" class="refresh">🔄 Refresh</a>
  
  <div class="stats">
    <h3>Statistics</h3>
    <p><strong>Total Security Incidents:</strong> <?= $stats['total_incidents'] ?></p>
    <p><strong>Recent Incidents:</strong> <?= count($stats['recent_incidents']) ?></p>
  </div>
  
  <h3>Recent Security Incidents</h3>
  <?php if (empty($stats['recent_incidents'])): ?>
    <p>No recent security incidents.</p>
  <?php else: ?>
    <?php foreach (array_reverse($stats['recent_incidents']) as $incident): ?>
      <div class="incident <?= strtolower(str_replace('_', '-', $incident->type)) ?>">
        <strong><?= htmlspecialchars($incident->type) ?></strong><br>
        <strong>IP:</strong> <?= htmlspecialchars($incident->ip) ?><br>
        <strong>Time:</strong> <?= htmlspecialchars($incident->timestamp) ?><br>
        <strong>User Agent:</strong> <?= htmlspecialchars($incident->user_agent) ?><br>
        <strong>Request:</strong> <?= htmlspecialchars($incident->request_uri) ?><br>
        <?php if (!empty($incident->referer)): ?>
          <strong>Referer:</strong> <?= htmlspecialchars($incident->referer) ?><br>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  
  <h3>Security Configuration</h3>
  <pre><?= json_encode($config['security'], JSON_PRETTY_PRINT) ?></pre>
  
  <p><a href="..">← Back to Directory Listing</a></p>
</body>
</html>
