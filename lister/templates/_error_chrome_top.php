<?php
/**
 * Opens document, site header, and <main> for Lister HTTP error pages (404, runtime).
 * Expects: $listerPageTitle (string).
 */
$__listerPt = $listerPageTitle ?? 'Error';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($__listerPt, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="icon" href="/lister/assets/images/2021/02/jp_round-48x48.jpg?v=2" sizes="32x32">
  <link rel="icon" href="/lister/assets/images/2021/02/jp_round.jpg?v=2" sizes="192x192">
  <link rel="apple-touch-icon" href="/lister/assets/images/2021/02/jp_round-180x180.jpg?v=2">
  <link rel="stylesheet" href="/lister/assets/lister.css">
</head>
<body>
  <a class="lister-skip-link" href="#lister-main">Skip to main content</a>
  <header>
    <hgroup>
      <p class="site-title"><a href="https://jonplummer.com">Jon Plummer</a></p>
      <p>Here are some things</p>
    </hgroup>
  </header>

  <main id="lister-main">
