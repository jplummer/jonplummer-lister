<?php
/**
 * Load and render a README from the listed directory (GitHub-style names).
 * Markdown via Parsedown in safe mode; .txt as escaped plain text.
 */

class ReadmePreview
{
  const MAX_BYTES = 524288;

  /** First match wins (same rough order as GitHub). */
  private static $candidateNames = [
    'README.md',
    'readme.md',
    'Readme.md',
    'README.mkd',
    'README.markdown',
    'README.txt',
    'readme.txt',
    'Readme.txt',
    'README',
  ];

  /**
   * @param string $directoryRealPath Directory being listed (resolved path)
   * @param array<string, mixed> $config
   * @return array{filename: string, format: string, html: string, modified: int, modified_formatted: string}|null
   */
  public static function load($directoryRealPath, $config)
  {
    if (!($config['display']['readme_preview'] ?? true)) {
      return null;
    }

    $dir = realpath($directoryRealPath);
    if ($dir === false || !is_dir($dir) || !is_readable($dir)) {
      return null;
    }

    foreach (self::$candidateNames as $name) {
      $full = $dir . DIRECTORY_SEPARATOR . $name;
      if (!is_file($full) || !is_readable($full)) {
        continue;
      }

      $resolved = realpath($full);
      if ($resolved === false) {
        continue;
      }
      if ($resolved !== $dir && strpos($resolved, $dir . DIRECTORY_SEPARATOR) !== 0) {
        continue;
      }

      $size = filesize($full);
      if ($size === false || $size > self::MAX_BYTES) {
        continue;
      }

      $mtime = filemtime($full);
      if ($mtime === false) {
        $mtime = time();
      }
      $modifiedFormatted = date('Y-m-d H:i', $mtime);

      $raw = file_get_contents($full, false, null, 0, self::MAX_BYTES + 1);
      if ($raw === false || strlen($raw) > self::MAX_BYTES) {
        continue;
      }

      if (function_exists('mb_check_encoding')
        && function_exists('mb_convert_encoding')
        && !mb_check_encoding($raw, 'UTF-8')) {
        $converted = @mb_convert_encoding($raw, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252');
        if ($converted !== false) {
          $raw = $converted;
        }
      }

      $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

      if ($ext === 'txt') {
        $html = '<div class="readme-plain">'
          . nl2br(htmlspecialchars($raw, ENT_QUOTES, 'UTF-8'))
          . '</div>';
        return [
          'filename' => $name,
          'format' => 'text',
          'html' => $html,
          'modified' => $mtime,
          'modified_formatted' => $modifiedFormatted,
        ];
      }

      $parsedownPath = __DIR__ . '/Parsedown.php';
      if (!is_readable($parsedownPath)) {
        return null;
      }
      require_once $parsedownPath;
      $pd = new Parsedown();
      $pd->setSafeMode(true);
      $html = $pd->text($raw);

      return [
        'filename' => $name,
        'format' => 'markdown',
        'html' => $html,
        'modified' => $mtime,
        'modified_formatted' => $modifiedFormatted,
      ];
    }

    return null;
  }
}
