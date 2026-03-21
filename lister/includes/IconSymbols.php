<?php
/**
 * File-type icon keys → emoji for table rows. Single source for App and
 * lister/templates/index.php (injected as JSON for expandable rows).
 */

class IconSymbols
{
  /** @var array<string, string>|null */
  private static $map = null;

  /**
   * @return array<string, string>
   */
  public static function getMap()
  {
    if (self::$map !== null) {
      return self::$map;
    }

    self::$map = [
      // Folders
      'folder' => '📁',

      // Basic file types
      'file' => '📄',

      // Media types
      'image' => '🖼️',
      'video' => '🎬',
      'audio' => '🎵',

      // Documents and text
      'document' => '📄',
      'text' => '📝',
      'pdf' => '📕',
      'book' => '📚',

      // Code and development
      'code' => '💻',
      'web' => '🌐',
      'exec' => '⚙️',

      // Data and office
      'spreadsheet' => '📊',
      'sheet' => '📊',
      'presentation' => '📽️',
      'slide' => '📽️',

      // Archives and storage
      'archive' => '📦',

      // System and fonts
      'font' => '🔤',
      'config' => '⚙️',
      'backup' => '💾',
      'database' => '🗄️',
      'cad' => '📐',
      'ebook' => '📖',
      'game' => '🎮',
    ];

    return self::$map;
  }

  /**
   * @param string $iconKey
   * @return string
   */
  public static function symbolFor($iconKey)
  {
    $map = self::getMap();
    return $map[$iconKey] ?? '📄';
  }
}
