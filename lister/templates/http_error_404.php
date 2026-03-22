<?php
/**
 * 404 — not found (invalid listing path). Expects:
 * $listerPageTitle, $listerMainHeading, $listerRequestPathDisplay (path shown to user, e.g. /foo/bar)
 * $deploymentTimestamp (optional)
 */
if (!isset($listerPageTitle, $listerMainHeading, $listerRequestPathDisplay)) {
  http_response_code(500);
  echo 'Lister error template misconfigured.';
  exit;
}
include __DIR__ . '/_error_chrome_top.php';
?>
    <article class="lister-http-error">
      <header>
        <h1><?= htmlspecialchars($listerMainHeading, ENT_QUOTES, 'UTF-8') ?></h1>
      </header>
      <section class="lister-http-error-body">
        <p>It looks like nothing was found at this location. Try one of the links below.</p>
        <p class="lister-http-error-path"><span class="lister-http-error-path-label">Requested path:</span> <code><?= htmlspecialchars($listerRequestPathDisplay, ENT_QUOTES, 'UTF-8') ?></code></p>
        <nav class="lister-http-error-nav" aria-label="Next steps">
          <h2 class="lister-http-error-nav-heading">More information</h2>
          <ul>
            <li><a href="/">Listing root</a></li>
            <li><a href="https://jonplummer.com/">Jon Plummer (site)</a></li>
          </ul>
        </nav>
      </section>
    </article>
<?php
include __DIR__ . '/_error_chrome_bottom.php';
