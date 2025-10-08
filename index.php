<?php
/**
 * Lister - Directory Listing Application
 * Main entry point for the application
 */

// Load configuration
$config = json_decode(file_get_contents(__DIR__ . '/lister/config/default.json'), true);

// Basic PHP environment test
echo "<!DOCTYPE html>\n";
echo "<html lang=\"en\">\n";
echo "<head>\n";
echo "  <meta charset=\"UTF-8\">\n";
echo "  <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
echo "  <title>Lister - Directory Listing</title>\n";
echo "  <link rel=\"stylesheet\" href=\"lister/assets/css/lister.css\">\n";
echo "</head>\n";
echo "<body>\n";
echo "  <h1>Lister is working!</h1>\n";
echo "  <p>PHP Version: " . phpversion() . "</p>\n";
echo "  <p>Current directory: " . getcwd() . "</p>\n";
echo "  <p>Server time: " . date('Y-m-d H:i:s') . "</p>\n";
echo "  <p>Config loaded: " . ($config ? 'Yes' : 'No') . "</p>\n";
echo "  <p>App name: " . ($config['app']['name'] ?? 'Unknown') . "</p>\n";
echo "</body>\n";
echo "</html>\n";
?>
