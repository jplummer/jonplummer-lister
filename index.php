<?php
/**
 * Lister - Directory Listing Application
 * Main entry point for the application
 */

// Load the main App class
require_once __DIR__ . '/lister/includes/App.php';

try {
  // Initialize and run the application
  $app = new App();
  $app->render();
} catch (Exception $e) {
  // Handle critical errors
  http_response_code(500);
  ?>
  <!DOCTYPE html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Directory Listing</title>
    <style>
      body { font-family: Arial, sans-serif; margin: 40px; }
      .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 4px; }
      h1 { color: #721c24; }
    </style>
  </head>
  <body>
    <div class="error">
      <h1>Application Error</h1>
      <p><?= htmlspecialchars($e->getMessage()) ?></p>
    </div>
  </body>
  </html>
  <?php
}