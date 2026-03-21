<?php
/**
 * File-type icon keys → Material Symbols Outlined ligature names for table rows.
 * Single source for App and lister/templates/index.php (injected as JSON for expandable rows).
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
      // Folders (file rows; directory rows use CSS ::before)
      'folder' => 'folder',

      // Basic file types
      'file' => 'draft',

      // Media types
      'image' => 'image',
      'video' => 'movie',
      'audio' => 'audio_file',

      // Documents and text
      'document' => 'description',
      'text' => 'article',
      'pdf' => 'picture_as_pdf',
      'book' => 'menu_book',

      // Code and development
      'code' => 'code',
      'web' => 'language',
      'exec' => 'terminal',

      // Data and office
      'spreadsheet' => 'table_chart',
      'sheet' => 'table_chart',
      'presentation' => 'slideshow',
      'slide' => 'slideshow',

      // Archives and storage
      'archive' => 'folder_zip',

      // System and fonts
      'font' => 'font_download',
      'config' => 'settings',
      'backup' => 'save',
      'database' => 'database',
      'cad' => 'architecture',
      'ebook' => 'menu_book',
      'game' => 'sports_esports',
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
    return $map[$iconKey] ?? 'draft';
  }
}
