<?php
/**
 * Strip directory-traversal fragments and decode percent-encoding safely.
 * Used for URL path segments (router, DirectoryLister) and web-relative
 * paths from the listing API (POST/GET path).
 */

class PathSanitizer
{
  /**
   * Remove ../ and ..\, urldecode, then remove again (decoded payload may reintroduce them).
   *
   * @param string $path Raw path string (may include leading slash or multiple segments)
   * @return string
   */
  public static function stripTraversalAfterUrlDecode($path)
  {
    $path = (string) $path;
    $path = str_replace(['../', '..\\'], '', $path);
    $path = urldecode($path);
    $path = str_replace(['../', '..\\'], '', $path);
    return $path;
  }
}
