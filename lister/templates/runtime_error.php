<?php
/**
 * Fatal / bootstrap error after App.php loads (config, template, environment).
 * Included from index.php only.
 */
if (!defined('LISTER_RUNTIME_ERROR_RENDER')) {
  http_response_code(403);
  exit;
}

$listerPageTitle = $errorType . ' — Directory listing';
$deploymentTimestamp = null;
include __DIR__ . '/_error_chrome_top.php';
?>
    <article class="lister-http-error lister-http-error--runtime">
      <header>
        <h1><?= htmlspecialchars($errorType, ENT_QUOTES, 'UTF-8') ?></h1>
      </header>
      <section class="lister-http-error-body">
        <p class="lister-runtime-error-message"><strong>Details:</strong> <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?></p>

        <?php if (!empty($suggestions)): ?>
        <div class="lister-runtime-suggestions">
          <h2 class="lister-http-error-nav-heading">Suggested next steps</h2>
          <ul>
            <?php foreach ($suggestions as $suggestion): ?>
            <li><?= htmlspecialchars($suggestion, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <p class="lister-runtime-help">For more help, see <code>INSTALL.md</code> or <code>docs/notes.md</code> in the repository.</p>
      </section>
    </article>
<?php
include __DIR__ . '/_error_chrome_bottom.php';
