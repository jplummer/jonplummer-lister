<?php
/**
 * Security - Simple bot detection and rate limiting
 * Designed for low-traffic sites to catch unusual activity
 */

class Security
{
  private $dataDir;
  private $config;
  
  public function __construct($config)
  {
    $this->config = $config;
    $this->dataDir = __DIR__ . '/../../data';
    
    // Create data directory if it doesn't exist
    if (!is_dir($this->dataDir)) {
      mkdir($this->dataDir, 0755, true);
    }
  }
  
  /**
   * Check if request should be allowed
   */
  public function checkRequest()
  {
    $ip = $this->getClientIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    // Check if IP is blocked
    if ($this->isBlocked($ip)) {
      $this->logSuspiciousActivity($ip, 'BLOCKED_IP_ACCESS_ATTEMPT', $userAgent);
      $this->denyAccess('Access denied');
    }
    
    // Check for bot behavior
    if ($this->isBot($userAgent)) {
      $this->logSuspiciousActivity($ip, 'BOT_DETECTED', $userAgent);
      $this->denyAccess('Bot detected');
    }
    
    // Check rate limiting
    if (!$this->checkRateLimit($ip)) {
      $this->logSuspiciousActivity($ip, 'RATE_LIMIT_EXCEEDED', $userAgent);
      $this->blockIP($ip, 300); // Block for 5 minutes
      $this->denyAccess('Rate limit exceeded');
    }
    
    // Check for suspicious patterns
    if ($this->isSuspiciousRequest()) {
      $this->logSuspiciousActivity($ip, 'SUSPICIOUS_REQUEST', $userAgent);
      // Don't block immediately, just log
    }
  }
  
  /**
   * Get client IP address
   */
  private function getClientIP()
  {
    $ipKeys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    
    foreach ($ipKeys as $key) {
      if (!empty($_SERVER[$key])) {
        $ip = $_SERVER[$key];
        // Handle comma-separated IPs (from proxies)
        if (strpos($ip, ',') !== false) {
          $ip = trim(explode(',', $ip)[0]);
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
          return $ip;
        }
      }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  }
  
  /**
   * Check if user agent looks like a bot
   */
  private function isBot($userAgent)
  {
    if (empty($userAgent)) {
      return true; // No user agent is suspicious
    }
    
    $botPatterns = [
      '/bot/i', '/crawler/i', '/spider/i', '/scraper/i',
      '/curl/i', '/wget/i', '/python/i', '/java/i',
      '/go-http/i', '/okhttp/i', '/libwww/i', '/httpie/i',
      '/postman/i', '/insomnia/i', '/scrapy/i', '/requests/i'
    ];
    
    foreach ($botPatterns as $pattern) {
      if (preg_match($pattern, $userAgent)) {
        return true;
      }
    }
    
    // Check for suspiciously short user agents
    if (strlen($userAgent) < 10) {
      return true;
    }
    
    return false;
  }
  
  /**
   * Check rate limiting
   */
  private function checkRateLimit($ip)
  {
    $maxRequests = $this->config['security']['max_requests_per_minute'] ?? 30;
    $file = $this->dataDir . '/rate_' . md5($ip) . '.json';
    $now = time();
    $windowStart = $now - 60; // 1 minute window
    
    if (file_exists($file)) {
      $data = json_decode(file_get_contents($file), true);
      // Clean old entries
      $data = array_filter($data, function($timestamp) use ($windowStart) {
        return $timestamp > $windowStart;
      });
    } else {
      $data = [];
    }
    
    if (count($data) >= $maxRequests) {
      return false;
    }
    
    $data[] = $now;
    file_put_contents($file, json_encode($data));
    return true;
  }
  
  /**
   * Check for suspicious request patterns
   */
  private function isSuspiciousRequest()
  {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Check for directory traversal attempts
    if (strpos($requestUri, '..') !== false || strpos($requestUri, '//') !== false) {
      return true;
    }
    
    // Check for suspicious query parameters
    $suspiciousParams = ['cmd', 'exec', 'system', 'eval', 'shell', 'passwd', 'shadow'];
    foreach ($suspiciousParams as $param) {
      if (isset($_GET[$param]) || isset($_POST[$param])) {
        return true;
      }
    }
    
    // Check for suspicious file extensions in URL
    $suspiciousExtensions = ['.php', '.asp', '.jsp', '.py', '.sh', '.exe'];
    foreach ($suspiciousExtensions as $ext) {
      if (strpos($requestUri, $ext) !== false) {
        return true;
      }
    }
    
    return false;
  }
  
  /**
   * Check if IP is currently blocked
   */
  private function isBlocked($ip)
  {
    $file = $this->dataDir . '/blocked_' . md5($ip) . '.json';
    
    if (!file_exists($file)) {
      return false;
    }
    
    $data = json_decode(file_get_contents($file), true);
    $blockedUntil = $data['blocked_until'] ?? 0;
    
    if (time() < $blockedUntil) {
      return true;
    } else {
      // Block expired, remove file
      unlink($file);
      return false;
    }
  }
  
  /**
   * Block an IP address
   */
  private function blockIP($ip, $seconds = 300)
  {
    $file = $this->dataDir . '/blocked_' . md5($ip) . '.json';
    $data = [
      'ip' => $ip,
      'blocked_at' => time(),
      'blocked_until' => time() + $seconds,
      'reason' => 'Rate limit exceeded'
    ];
    
    file_put_contents($file, json_encode($data));
  }
  
  /**
   * Log suspicious activity
   */
  private function logSuspiciousActivity($ip, $type, $userAgent)
  {
    $logEntry = [
      'timestamp' => date('Y-m-d H:i:s'),
      'ip' => $ip,
      'type' => $type,
      'user_agent' => $userAgent,
      'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
      'referer' => $_SERVER['HTTP_REFERER'] ?? ''
    ];
    
    $logFile = $this->dataDir . '/security.log';
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
  }
  
  /**
   * Deny access and exit
   */
  private function denyAccess($message)
  {
    http_response_code(403);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Access Denied</title>
      <style>
        body { font-family: Arial, sans-serif; margin: 40px; text-align: center; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 4px; max-width: 500px; margin: 0 auto; }
        h1 { color: #721c24; }
      </style>
    </head>
    <body>
      <div class="error">
        <h1>Access Denied</h1>
        <p><?= htmlspecialchars($message) ?></p>
        <p>If you believe this is an error, please contact the administrator.</p>
      </div>
    </body>
    </html>
    <?php
    exit;
  }
  
  /**
   * Get security statistics
   */
  public function getStats()
  {
    $logFile = $this->dataDir . '/security.log';
    if (!file_exists($logFile)) {
      return ['total_incidents' => 0, 'recent_incidents' => []];
    }
    
    $lines = file($logFile, FILE_IGNORE_NEW_LINES);
    $incidents = array_map('json_decode', $lines);
    $incidents = array_filter($incidents); // Remove empty lines
    
    $recent = array_slice($incidents, -10); // Last 10 incidents
    
    return [
      'total_incidents' => count($incidents),
      'recent_incidents' => $recent
    ];
  }
}
