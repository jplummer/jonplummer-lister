<?php
/**
 * Lister - Directory Listing Application
 * Main entry point for the application
 */

// Load configuration
$config = json_decode(file_get_contents(__DIR__ . '/lister/config/default.json'), true);

// Load DirectoryLister class
require_once __DIR__ . '/lister/includes/DirectoryLister.php';

try {
  // Initialize directory lister
  $lister = new DirectoryLister($config);
  
  // Scan current directory
  $data = $lister->scanDirectory();
  $breadcrumbs = $lister->getBreadcrumbs();
  
} catch (Exception $e) {
  $error = $e->getMessage();
  $data = null;
  $breadcrumbs = [];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miscellaneous - Directory Listing</title>
  <link rel="stylesheet" href="lister/assets/css/lister.css">
</head>
<body>
  <header>
    <hgroup>
      <h1><a href="/">Miscellaneous</a></h1>
      <p>Directory listing</p>
    </hgroup>
    <nav class="breadcrumbs">
      <?php if (!empty($breadcrumbs)): ?>
        <?php foreach ($breadcrumbs as $index => $crumb): ?>
          <a href="<?= htmlspecialchars($crumb['url']) ?>"><?= htmlspecialchars($crumb['name']) ?></a>
          <?php if ($index < count($breadcrumbs) - 1): ?> / <?php endif; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </nav>
  </header>

  <main>
    <?php if (isset($error)): ?>
      <div class="error">
        <h2>Error</h2>
        <p><?= htmlspecialchars($error) ?></p>
      </div>
    <?php elseif ($data): ?>
      <div class="directory-listing">
        <?php if (!empty($data['directories'])): ?>
          <section class="directories">
            <h2>Directories</h2>
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Modified</th>
                  <th>Permissions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['directories'] as $dir): ?>
                  <tr>
                    <td>
                      <a href="<?= htmlspecialchars($dir['url']) ?>" class="directory-link">
                        <span class="icon folder">📁</span>
                        <?= htmlspecialchars($dir['name']) ?>
                      </a>
                    </td>
                    <td><?= htmlspecialchars($dir['modified_formatted']) ?></td>
                    <td><?= htmlspecialchars($dir['permissions']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        <?php endif; ?>

        <?php if (!empty($data['files'])): ?>
          <section class="files">
            <h2>Files</h2>
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Size</th>
                  <th>Modified</th>
                  <th>Type</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($data['files'] as $file): ?>
                  <tr>
                    <td>
                      <a href="<?= htmlspecialchars($file['url']) ?>" class="file-link">
                        <span class="icon <?= htmlspecialchars($file['icon']) ?>">
                          <?= getIconSymbol($file['icon']) ?>
                        </span>
                        <?= htmlspecialchars($file['name']) ?>
                      </a>
                    </td>
                    <td><?= htmlspecialchars($file['size_formatted']) ?></td>
                    <td><?= htmlspecialchars($file['modified_formatted']) ?></td>
                    <td><?= htmlspecialchars($file['extension'] ?: 'Unknown') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </section>
        <?php endif; ?>

        <?php if (empty($data['directories']) && empty($data['files'])): ?>
          <div class="empty">
            <p>This directory is empty.</p>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </main>

  <footer>
    <p>Lister v<?= htmlspecialchars($config['app']['version'] ?? '1.0.0') ?></p>
  </footer>
</body>
</html>

<?php
/**
 * Get icon symbol for file type
 */
function getIconSymbol($icon) {
  $icons = [
    'folder' => '📁',
    'file' => '📄',
    'image' => '🖼️',
    'pdf' => '📕',
    'document' => '📄',
    'text' => '📝',
    'archive' => '📦',
    'code' => '💻',
    'audio' => '🎵',
    'video' => '🎬',
    'spreadsheet' => '📊',
    'presentation' => '📽️'
  ];
  
  return $icons[$icon] ?? '📄';
}
?>
