  </main>

  <footer>
    <p>Lister © <?= date('Y') ?> Jon Plummer</p>
    <?php if (!empty($deploymentTimestamp)): ?>
      <p class="deploy-id"><?= htmlspecialchars($deploymentTimestamp, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
  </footer>
</body>
</html>
