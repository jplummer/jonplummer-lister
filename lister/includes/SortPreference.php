<?php
/**
 * Resolves list sort column and direction from query string, cookie, and config.
 * Full HTML page: GET overrides cookie; writing the cookie when GET is used keeps
 * the next navigation stable. API: POST (then GET) overrides cookie for consistency
 * with the active page without setting cookies on each request.
 */

class SortPreference
{
  const COOKIE_NAME = 'lister_sort';
  const COOKIE_TTL_SECONDS = 31536000;

  /** @var string[] */
  private static $allowedColumns = ['name', 'size', 'modified', 'type'];

  /** @var string[] */
  private static $allowedDirs = ['asc', 'desc'];

  /**
   * @param array $config Application config (expects display.default_sort, display.sort_direction)
   * @return array{sort_by: string, sort_dir: string}
   */
  public static function resolveForHtmlPage(array $config)
  {
    $defaults = self::defaultsFromConfig($config);
    $fromCookie = self::readValidCookie();

    $hadQuery = array_key_exists('sort', $_GET) || array_key_exists('dir', $_GET);

    if ($hadQuery) {
      $sortBy = self::sortFromQueryOrFallback('sort', $fromCookie['sort'] ?? null, $defaults['sort']);
      $sortDir = self::dirFromQueryOrFallback('dir', $fromCookie['dir'] ?? null, $defaults['dir']);
      self::writeCookie($sortBy, $sortDir);
    } else {
      $sortBy = $fromCookie['sort'] ?? $defaults['sort'];
      $sortDir = $fromCookie['dir'] ?? $defaults['dir'];
    }

    return ['sort_by' => $sortBy, 'sort_dir' => $sortDir];
  }

  /**
   * @param array $config Application config
   * @return array{sort_by: string, sort_dir: string}
   */
  public static function resolveForApi(array $config)
  {
    $defaults = self::defaultsFromConfig($config);
    $fromCookie = self::readValidCookie();

    $postSort = $_POST['sort'] ?? null;
    $postDir = $_POST['dir'] ?? null;
    $getSort = $_GET['sort'] ?? null;
    $getDir = $_GET['dir'] ?? null;

    $hadBody = array_key_exists('sort', $_POST) || array_key_exists('dir', $_POST);
    $hadGet = array_key_exists('sort', $_GET) || array_key_exists('dir', $_GET);

    if ($hadBody) {
      $sortBy = self::normalizeSortInput($postSort, $fromCookie['sort'] ?? null, $defaults['sort']);
      $sortDir = self::normalizeDirInput($postDir, $fromCookie['dir'] ?? null, $defaults['dir']);
    } elseif ($hadGet) {
      $sortBy = self::normalizeSortInput($getSort, $fromCookie['sort'] ?? null, $defaults['sort']);
      $sortDir = self::normalizeDirInput($getDir, $fromCookie['dir'] ?? null, $defaults['dir']);
    } else {
      $sortBy = $fromCookie['sort'] ?? $defaults['sort'];
      $sortDir = $fromCookie['dir'] ?? $defaults['dir'];
    }

    return ['sort_by' => $sortBy, 'sort_dir' => $sortDir];
  }

  /**
   * Build href for a column header (toggles direction when already active).
   */
  public static function sortColumnHref($columnKey, $currentBy, $currentDir)
  {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if ($path === null || $path === '') {
      $path = '/';
    }
    if ($currentBy === $columnKey) {
      $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';
    } else {
      $nextDir = 'asc';
    }
    return $path . '?' . http_build_query([
      'sort' => $columnKey,
      'dir' => $nextDir,
    ]);
  }

  /**
   * @param array $config
   * @return array{sort: string, dir: string}
   */
  private static function defaultsFromConfig(array $config)
  {
    $sort = $config['display']['default_sort'] ?? 'name';
    $dir = $config['display']['sort_direction'] ?? 'asc';
    if (!self::isAllowedSort($sort)) {
      $sort = 'name';
    }
    if (!self::isAllowedDir($dir)) {
      $dir = 'asc';
    }
    return ['sort' => $sort, 'dir' => $dir];
  }

  /**
   * @return array{sort: string, dir: string}|null
   */
  private static function readValidCookie()
  {
    if (empty($_COOKIE[self::COOKIE_NAME])) {
      return null;
    }
    $raw = $_COOKIE[self::COOKIE_NAME];
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
      return null;
    }
    $sort = isset($decoded['sort']) ? (string) $decoded['sort'] : null;
    $dir = isset($decoded['dir']) ? (string) $decoded['dir'] : null;
    if (!self::isAllowedSort($sort) || !self::isAllowedDir($dir)) {
      return null;
    }
    return ['sort' => $sort, 'dir' => $dir];
  }

  private static function writeCookie($sortBy, $sortDir)
  {
    $payload = json_encode(
      ['sort' => $sortBy, 'dir' => $sortDir],
      JSON_UNESCAPED_SLASHES
    );
    $opts = [
      'expires' => time() + self::COOKIE_TTL_SECONDS,
      'path' => '/',
      'httponly' => true,
      'samesite' => 'Lax',
    ];
    setcookie(self::COOKIE_NAME, $payload, $opts);
  }

  private static function sortFromQueryOrFallback($getKey, $cookieSort, $defaultSort)
  {
    if (!array_key_exists($getKey, $_GET)) {
      return $cookieSort ?? $defaultSort;
    }
    $v = $_GET[$getKey];
    if ($v === '' || $v === null) {
      return $cookieSort ?? $defaultSort;
    }
    if (!self::isAllowedSort((string) $v)) {
      return $cookieSort ?? $defaultSort;
    }
    return (string) $v;
  }

  private static function dirFromQueryOrFallback($getKey, $cookieDir, $defaultDir)
  {
    if (!array_key_exists($getKey, $_GET)) {
      return $cookieDir ?? $defaultDir;
    }
    $v = $_GET[$getKey];
    if ($v === '' || $v === null) {
      return $cookieDir ?? $defaultDir;
    }
    if (!self::isAllowedDir((string) $v)) {
      return $cookieDir ?? $defaultDir;
    }
    return (string) $v;
  }

  private static function normalizeSortInput($value, $cookieSort, $defaultSort)
  {
    if ($value === '' || $value === null) {
      return $cookieSort ?? $defaultSort;
    }
    if (!self::isAllowedSort((string) $value)) {
      return $cookieSort ?? $defaultSort;
    }
    return (string) $value;
  }

  private static function normalizeDirInput($value, $cookieDir, $defaultDir)
  {
    if ($value === '' || $value === null) {
      return $cookieDir ?? $defaultDir;
    }
    if (!self::isAllowedDir((string) $value)) {
      return $cookieDir ?? $defaultDir;
    }
    return (string) $value;
  }

  private static function isAllowedSort($value)
  {
    return is_string($value) && in_array($value, self::$allowedColumns, true);
  }

  private static function isAllowedDir($value)
  {
    return is_string($value) && in_array($value, self::$allowedDirs, true);
  }
}
