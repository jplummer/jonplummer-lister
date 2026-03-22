<!DOCTYPE html>
<html lang="en" data-lister-sort="<?= htmlspecialchars($sortBy, ENT_QUOTES, 'UTF-8') ?>" data-lister-sort-dir="<?= htmlspecialchars($sortDir, ENT_QUOTES, 'UTF-8') ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miscellaneous - Directory Listing</title>
  <link rel="icon" href="/lister/assets/images/2021/02/jp_round-48x48.jpg?v=2" sizes="32x32">
  <link rel="icon" href="/lister/assets/images/2021/02/jp_round.jpg?v=2" sizes="192x192">
  <link rel="apple-touch-icon" href="/lister/assets/images/2021/02/jp_round-180x180.jpg?v=2">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0">
  <link rel="stylesheet" href="/lister/assets/lister.css">
</head>
<body>
  <header>
    <hgroup>
      <h1><a href="https://jonplummer.com">Jon Plummer</a></h1>
      <p>Here are some things</p>
    </hgroup>
  </header>

  <main>
    <article>
      <header>
        <h1>Miscellaneous</h1>
      </header>
      
      <section>
        <?php if (isset($error)): ?>
          <div class="error">
            <h2>Error</h2>
            <p><?= htmlspecialchars($error) ?></p>
          </div>
        <?php elseif ($data): ?>
          <div class="directory-listing">
            <?php if (!empty($data['directories']) || !empty($data['files'])): ?>
              <table>
                <thead>
                  <tr>
                    <th scope="col"<?php if ($sortBy === 'name'): ?> aria-sort="<?= $sortDir === 'asc' ? 'ascending' : 'descending' ?>"<?php endif; ?>>
                      <a class="sort-header" href="<?= htmlspecialchars(SortPreference::sortColumnHref('name', $sortBy, $sortDir), ENT_QUOTES, 'UTF-8') ?>">Name</a>
                    </th>
                    <th scope="col"<?php if ($sortBy === 'size'): ?> aria-sort="<?= $sortDir === 'asc' ? 'ascending' : 'descending' ?>"<?php endif; ?>>
                      <a class="sort-header" href="<?= htmlspecialchars(SortPreference::sortColumnHref('size', $sortBy, $sortDir), ENT_QUOTES, 'UTF-8') ?>">Size</a>
                    </th>
                    <th scope="col"<?php if ($sortBy === 'modified'): ?> aria-sort="<?= $sortDir === 'asc' ? 'ascending' : 'descending' ?>"<?php endif; ?>>
                      <a class="sort-header" href="<?= htmlspecialchars(SortPreference::sortColumnHref('modified', $sortBy, $sortDir), ENT_QUOTES, 'UTF-8') ?>">Modified</a>
                    </th>
                    <th scope="col"<?php if ($sortBy === 'type'): ?> aria-sort="<?= $sortDir === 'asc' ? 'ascending' : 'descending' ?>"<?php endif; ?>>
                      <a class="sort-header" href="<?= htmlspecialchars(SortPreference::sortColumnHref('type', $sortBy, $sortDir), ENT_QUOTES, 'UTF-8') ?>">Type</a>
                    </th>
                  </tr>
                </thead>
                <tbody id="directory-contents">
                  <?php 
                  // Combine directories and files, directories first
                  $allItems = array_merge($data['directories'] ?? [], $data['files'] ?? []);
                  foreach ($allItems as $item): 
                  ?>
                    <tr class="item-row" data-type="<?= $item['is_directory'] ? 'directory' : 'file' ?>" data-path="<?= htmlspecialchars($item['web_path']) ?>" data-nesting-level="0">
                      <td>
                        <?php if ($item['is_directory']): ?>
                          <?php if (isset($item['is_empty']) && $item['is_empty']): ?>
                            <span class="empty-folder">
                              <span class="icon folder"></span>
                              <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
                            </span>
                          <?php else: ?>
                            <button class="directory-toggle" data-path="<?= htmlspecialchars($item['web_path']) ?>">
                              <span class="toggle-icon"></span>
                              <span class="icon folder"></span>
                              <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
                            </button>
                          <?php endif; ?>
                        <?php else: ?>
                          <?php $previewKind = $item['preview_kind'] ?? null; ?>
                          <a href="<?= htmlspecialchars($item['url']) ?>" class="file-link"<?php if ($previewKind): ?> data-preview="<?= htmlspecialchars($previewKind, ENT_QUOTES, 'UTF-8') ?>"<?php endif; ?>>
                            <span class="icon material-symbols-outlined <?= htmlspecialchars($item['icon']) ?>" aria-hidden="true"><?= htmlspecialchars($getIconSymbol($item['icon']), ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
                          </a>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($item['size_formatted'] ?? '-') ?></td>
                      <td><?= htmlspecialchars($item['modified_formatted']) ?></td>
                      <td><?= htmlspecialchars($item['type'] ?? 'Unknown') ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <div class="empty">
                <p>This directory is empty.</p>
              </div>
            <?php endif; ?>
            <?php if (!empty($data['readme_preview'])): ?>
              <?php
              $readme = $data['readme_preview'];
              $readmeMtime = (int) ($readme['modified'] ?? 0);
              $readmeDt = $readmeMtime > 0 ? date('c', $readmeMtime) : '';
              ?>
              <section class="lister-readme" aria-label="Readme: <?= htmlspecialchars($readme['filename'], ENT_QUOTES, 'UTF-8') ?>">
                <p class="lister-readme-meta">
                  <span class="lister-readme-meta-filename"><?= htmlspecialchars($readme['filename'], ENT_QUOTES, 'UTF-8') ?></span>
                  <span class="lister-readme-meta-sep" aria-hidden="true"> · </span>
                  <?php if ($readmeDt !== ''): ?>
                    <time class="lister-readme-meta-time" datetime="<?= htmlspecialchars($readmeDt, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($readme['modified_formatted'] ?? '', ENT_QUOTES, 'UTF-8') ?></time>
                  <?php else: ?>
                    <span class="lister-readme-meta-time"><?= htmlspecialchars($readme['modified_formatted'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                  <?php endif; ?>
                </p>
                <div class="lister-readme-panel">
                  <div class="lister-readme-body readme-markdown">
                    <?= $readme['html'] ?>
                  </div>
                </div>
              </section>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </section>
    </article>
  </main>

  <div id="lister-expanding-indicator" class="lister-expanding-indicator" role="status" aria-live="polite" aria-atomic="true" aria-hidden="true">
    <span class="lister-expanding-indicator-panel" aria-hidden="true">⏳</span>
  </div>

  <dialog id="lister-preview-dialog" class="lister-preview-dialog" aria-labelledby="lister-preview-title">
    <div class="lister-preview-chrome">
      <h2 id="lister-preview-title" class="lister-preview-title"></h2>
      <div class="lister-preview-nav" id="lister-preview-nav" hidden>
        <button type="button" class="lister-preview-prev" aria-label="Previous preview">←</button>
        <button type="button" class="lister-preview-next" aria-label="Next preview">→</button>
      </div>
      <button type="button" class="lister-preview-close" aria-label="Close">Close</button>
    </div>
    <div class="lister-preview-body" id="lister-preview-body"></div>
  </dialog>

  <footer>
    <p>Lister © <?= date('Y') ?> Jon Plummer</p>
    <?php if ($deploymentTimestamp): ?>
      <p class="deploy-id"><?= htmlspecialchars($deploymentTimestamp) ?></p>
    <?php endif; ?>
  </footer>

  <script>
    const LISTER_ICON_SYMBOLS = <?= $listerIconSymbolsJson ?>;

    // Expandable directory functionality with nested containers
    const EXPANDING_INDICATOR_MIN_MS = 500;

    let expandingIndicatorDepth = 0;
    let expandingIndicatorShownAt = 0;
    let expandingIndicatorHideTimeoutId = null;

    function setExpandingIndicator(active) {
      const el = document.getElementById('lister-expanding-indicator');
      if (!el) {
        return;
      }
      el.classList.toggle('is-active', active);
      el.setAttribute('aria-hidden', active ? 'false' : 'true');
    }

    function beginExpandingIndicator() {
      if (expandingIndicatorDepth === 0) {
        clearTimeout(expandingIndicatorHideTimeoutId);
        expandingIndicatorHideTimeoutId = null;
        expandingIndicatorShownAt = performance.now();
        setExpandingIndicator(true);
      }
      expandingIndicatorDepth++;
    }

    function endExpandingIndicator() {
      expandingIndicatorDepth = Math.max(0, expandingIndicatorDepth - 1);
      if (expandingIndicatorDepth > 0) {
        return;
      }
      const elapsed = performance.now() - expandingIndicatorShownAt;
      const remaining = Math.max(0, EXPANDING_INDICATOR_MIN_MS - elapsed);
      clearTimeout(expandingIndicatorHideTimeoutId);
      expandingIndicatorHideTimeoutId = setTimeout(() => {
        setExpandingIndicator(false);
        expandingIndicatorHideTimeoutId = null;
      }, remaining);
    }

    // —— Modal preview (text / PDF / images) ——
    let previewDialog = null;
    let previewTitle = null;
    let previewBody = null;
    let previewNav = null;
    let previewPrev = null;
    let previewNext = null;
    let previewCarouselLinks = [];
    let previewCarouselIndex = -1;

    // —— Listing keyboard (roving row focus; see requirements.md) ——
    let listFocusedRow = null;

    function getListingRows() {
      const tbody = document.getElementById('directory-contents');
      if (!tbody) {
        return [];
      }
      return Array.from(tbody.querySelectorAll('tr.item-row'));
    }

    function setListFocusedRow(row) {
      if (!row || !row.closest('#directory-contents')) {
        return;
      }
      getListingRows().forEach((r) => {
        r.setAttribute('tabindex', '-1');
      });
      row.setAttribute('tabindex', '0');
      listFocusedRow = row;
    }

    function moveListingFocus(delta) {
      const rows = getListingRows();
      if (!rows.length) {
        return;
      }
      let idx = listFocusedRow ? rows.indexOf(listFocusedRow) : 0;
      if (idx < 0) {
        idx = 0;
      }
      idx = Math.max(0, Math.min(rows.length - 1, idx + delta));
      setListFocusedRow(rows[idx]);
      rows[idx].focus();
    }

    function onListingFocusIn(e) {
      if (previewDialog && previewDialog.open) {
        return;
      }
      const row = e.target.closest && e.target.closest('#directory-contents tr.item-row');
      if (!row || e.target === row) {
        return;
      }
      if (e.target.closest('a.file-link') || e.target.closest('button.directory-toggle')) {
        setListFocusedRow(row);
        row.focus();
      }
    }

    function activateListingRow(row) {
      const toggle = row.querySelector('.directory-toggle');
      const link = row.querySelector('a.file-link');
      if (toggle) {
        const path = toggle.getAttribute('data-path');
        const icon = toggle.querySelector('.toggle-icon');
        if (row.classList.contains('expanded')) {
          collapseDirectory(row, icon);
        } else {
          expandDirectory(row, path, icon);
        }
        return;
      }
      if (link) {
        const kind = link.getAttribute('data-preview');
        if (kind) {
          openFilePreview(link);
        } else {
          window.location.href = link.href;
        }
      }
    }

    function spaceOpenPreviewIfAvailable(row, e) {
      const link = row.querySelector('a.file-link[data-preview]');
      if (!link) {
        return false;
      }
      if (e) {
        e.preventDefault();
      }
      openFilePreview(link);
      return true;
    }

    function getListingRowFromActiveElement() {
      const el = document.activeElement;
      if (!el || !el.closest) {
        return null;
      }
      return el.closest('#directory-contents tr.item-row');
    }

    function onListingKeydown(e) {
      if (e.metaKey || e.ctrlKey || e.altKey) {
        return;
      }
      if (previewDialog && previewDialog.open) {
        return;
      }
      const tag = e.target.tagName;
      if (tag === 'INPUT' || tag === 'TEXTAREA' || (e.target.isContentEditable && e.target.isContentEditable !== 'false')) {
        return;
      }
      const row = getListingRowFromActiveElement();
      if (!row) {
        return;
      }

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        moveListingFocus(1);
        return;
      }
      if (e.key === 'ArrowUp') {
        e.preventDefault();
        moveListingFocus(-1);
        return;
      }
      if (e.key === 'ArrowRight') {
        const toggle = row.querySelector('.directory-toggle');
        if (!toggle) {
          return;
        }
        e.preventDefault();
        if (!row.classList.contains('expanded')) {
          expandDirectory(row, toggle.getAttribute('data-path'), toggle.querySelector('.toggle-icon'));
        }
        return;
      }
      if (e.key === 'ArrowLeft') {
        const toggle = row.querySelector('.directory-toggle');
        if (!toggle || !row.classList.contains('expanded')) {
          return;
        }
        e.preventDefault();
        collapseDirectory(row, toggle.querySelector('.toggle-icon'));
        return;
      }
      if (e.key === 'Enter') {
        e.preventDefault();
        activateListingRow(row);
        return;
      }
      if (e.key === ' ') {
        if (spaceOpenPreviewIfAvailable(row, e)) {
          return;
        }
        e.preventDefault();
        activateListingRow(row);
      }
    }

    function initListingKeyboard() {
      const tbody = document.getElementById('directory-contents');
      if (!tbody) {
        return;
      }
      const rows = getListingRows();
      if (!rows.length) {
        return;
      }
      tbody.querySelectorAll('a.file-link, button.directory-toggle').forEach((el) => {
        el.setAttribute('tabindex', '-1');
      });
      rows.forEach((r, i) => {
        r.setAttribute('tabindex', i === 0 ? '0' : '-1');
      });
      listFocusedRow = rows[0];
      tbody.addEventListener('focusin', onListingFocusIn);
      document.addEventListener('keydown', onListingKeydown);
      rows[0].focus({ preventScroll: true });
    }

    function restoreListingFocusAfterPreviewClose() {
      if (listFocusedRow && listFocusedRow.isConnected) {
        setListFocusedRow(listFocusedRow);
        listFocusedRow.focus();
      }
    }

    function webPathFromFileHref(href) {
      const u = new URL(href, window.location.href);
      let p = u.pathname;
      if (p.startsWith('/')) {
        p = p.slice(1);
      }
      return p;
    }

    function escapeHtml(s) {
      const d = document.createElement('div');
      d.textContent = s;
      return d.innerHTML;
    }

    function collectPreviewLinkElements() {
      const tbody = document.getElementById('directory-contents');
      if (!tbody) {
        return [];
      }
      return Array.from(tbody.querySelectorAll('a.file-link[data-preview]'));
    }

    function findPreviewCarouselIndexByHref(links, hrefAttr) {
      const target = new URL(hrefAttr, window.location.href).href;
      return links.findIndex((a) => a.href === target);
    }

    function syncPreviewCarouselForLink(link) {
      previewCarouselLinks = collectPreviewLinkElements();
      let idx = previewCarouselLinks.indexOf(link);
      if (idx < 0) {
        idx = findPreviewCarouselIndexByHref(previewCarouselLinks, link.href);
      }
      previewCarouselIndex = idx >= 0 ? idx : 0;
    }

    function updatePreviewCarouselNav() {
      if (!previewNav || !previewPrev || !previewNext) {
        return;
      }
      const n = previewCarouselLinks.length;
      previewNav.hidden = n <= 1;
      previewPrev.disabled = previewCarouselIndex <= 0;
      previewNext.disabled = previewCarouselIndex >= n - 1;
    }

    function stepPreviewCarousel(delta) {
      if (previewCarouselLinks.length < 2) {
        return;
      }
      previewCarouselIndex = Math.max(0, Math.min(previewCarouselLinks.length - 1, previewCarouselIndex + delta));
      const nextLink = previewCarouselLinks[previewCarouselIndex];
      if (nextLink) {
        openFilePreview(nextLink);
      }
    }

    function onPreviewDialogClose() {
      const iframe = previewBody && previewBody.querySelector('iframe');
      if (iframe) {
        iframe.src = 'about:blank';
      }
      const img = previewBody && previewBody.querySelector('img');
      if (img) {
        img.removeAttribute('src');
      }
      previewCarouselLinks = [];
      previewCarouselIndex = -1;
      restoreListingFocusAfterPreviewClose();
    }

    function onPreviewKeydown(e) {
      if (!previewDialog || !previewDialog.open) {
        return;
      }
      if (e.metaKey || e.ctrlKey || e.altKey) {
        return;
      }
      if (previewCarouselLinks.length < 2) {
        return;
      }
      if (e.key === 'ArrowLeft') {
        e.preventDefault();
        e.stopPropagation();
        stepPreviewCarousel(-1);
      } else if (e.key === 'ArrowRight') {
        e.preventDefault();
        e.stopPropagation();
        stepPreviewCarousel(1);
      }
    }

    function openFilePreview(link) {
      const kind = link.getAttribute('data-preview');
      const href = link.getAttribute('href');
      const label = (link.querySelector('.item-name') && link.querySelector('.item-name').textContent || '').trim();
      if (!kind || !href) {
        return;
      }

      const previewRow = link.closest && link.closest('tr.item-row');
      if (previewRow) {
        setListFocusedRow(previewRow);
      }

      syncPreviewCarouselForLink(link);

      previewTitle.textContent = label;
      previewBody.innerHTML = '';

      if (kind === 'image') {
        const img = document.createElement('img');
        img.className = 'lister-preview-img';
        img.alt = label;
        img.src = link.href;
        previewBody.appendChild(img);
        updatePreviewCarouselNav();
        previewDialog.showModal();
        return;
      }

      if (kind === 'pdf' || kind === 'iframe') {
        const iframe = document.createElement('iframe');
        iframe.className = 'lister-preview-iframe';
        iframe.title = label;
        iframe.src = link.href;
        previewBody.appendChild(iframe);
        updatePreviewCarouselNav();
        previewDialog.showModal();
        return;
      }

      if (kind === 'text') {
        const loading = document.createElement('p');
        loading.className = 'lister-preview-loading';
        loading.textContent = 'Loading…';
        previewBody.appendChild(loading);
        updatePreviewCarouselNav();
        previewDialog.showModal();
        const path = webPathFromFileHref(link.href);
        fetch('/lister/preview.php?path=' + encodeURIComponent(path))
          .then((r) => r.json())
          .then((data) => {
            previewBody.innerHTML = '';
            if (!data.success) {
              const err = document.createElement('p');
              err.className = 'lister-preview-error';
              err.textContent = data.error || 'Could not load preview';
              previewBody.appendChild(err);
              return;
            }
            const wrap = document.createElement('div');
            wrap.className = 'lister-preview-scroll';
            wrap.innerHTML = data.html;
            previewBody.appendChild(wrap);
          })
          .catch((err) => {
            previewBody.innerHTML = '';
            const p = document.createElement('p');
            p.className = 'lister-preview-error';
            p.textContent = String(err && err.message ? err.message : err);
            previewBody.appendChild(p);
          });
      }
    }

    function initListerPreview() {
      previewDialog = document.getElementById('lister-preview-dialog');
      previewTitle = document.getElementById('lister-preview-title');
      previewBody = document.getElementById('lister-preview-body');
      previewNav = document.getElementById('lister-preview-nav');
      if (!previewDialog || !previewTitle || !previewBody) {
        return;
      }
      previewPrev = previewDialog.querySelector('.lister-preview-prev');
      previewNext = previewDialog.querySelector('.lister-preview-next');
      const closeBtn = previewDialog.querySelector('.lister-preview-close');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => previewDialog.close());
      }
      if (previewPrev) {
        previewPrev.addEventListener('click', () => stepPreviewCarousel(-1));
      }
      if (previewNext) {
        previewNext.addEventListener('click', () => stepPreviewCarousel(1));
      }
      previewDialog.addEventListener('click', (e) => {
        if (e.target === previewDialog) {
          previewDialog.close();
        }
      });
      previewDialog.addEventListener('close', onPreviewDialogClose);
      // Capture on the dialog so ← / → work while focus is on chrome (e.g. Close); bubbling on document is unreliable with showModal().
      previewDialog.addEventListener('keydown', onPreviewKeydown, true);
      document.addEventListener('click', (event) => {
        const link = event.target.closest('#directory-contents a.file-link[data-preview]');
        if (!link) {
          return;
        }
        if (event.button !== 0) {
          return;
        }
        if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) {
          return;
        }
        event.preventDefault();
        openFilePreview(link);
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      initListerPreview();
      initListingKeyboard();
      // Use event delegation to handle all directory toggles
      document.addEventListener('click', function(event) {
        if (event.target.closest('.directory-toggle')) {
          const toggle = event.target.closest('.directory-toggle');
          const path = toggle.getAttribute('data-path');
          const row = toggle.closest('tr');
          const toggleIcon = toggle.querySelector('.toggle-icon');
          
          // Toggle the expanded state
          if (row.classList.contains('expanded')) {
            // Currently expanded - collapse it
            collapseDirectory(row, toggleIcon);
          } else {
            // Currently collapsed - expand it
            expandDirectory(row, path, toggleIcon);
          }
        }
      });
    });
    
    function expandDirectory(row, path, toggleIcon) {
      // Check if already expanded
      if (row.classList.contains('expanded')) {
        return;
      }
      
      beginExpandingIndicator();

      // POST body: nested web paths may break as GET query (slashes / %2F) on some hosts.
      const body = new URLSearchParams();
      body.set('path', path);
      const root = document.documentElement;
      if (root.dataset.listerSort) {
        body.set('sort', root.dataset.listerSort);
      }
      if (root.dataset.listerSortDir) {
        body.set('dir', root.dataset.listerSortDir);
      }
      
      fetch('/lister/api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body.toString()
      })
        .then(async (response) => {
          const data = await response.json().catch(() => ({}));
          if (!response.ok) {
            const msg = data.error || `HTTP ${response.status}`;
            console.error('Lister API:', msg);
            throw new Error(msg);
          }
          return data;
        })
        .then(data => {
          if (data.success) {
            // Create nested container
            createNestedContainer(row, data.data);
            row.classList.add('expanded');
          } else {
            console.error('Error loading directory:', data.error);
          }
        })
        .catch(error => {
          console.error('Error:', error);
        })
        .finally(() => {
          endExpandingIndicator();
        });
    }
    
    function collapseDirectory(row, toggleIcon) {
      // Find all expanded content rows that belong to this directory
      const tbody = row.parentNode;
      const allRows = Array.from(tbody.querySelectorAll('tr'));
      const currentIndex = allRows.indexOf(row);
      const rowsToRemove = [];
      
      // Get the nesting level of the current row
      const currentNestingLevel = parseInt(row.getAttribute('data-nesting-level')) || 0;
      
      // Find all rows after this one that are descendants of this directory
      for (let i = currentIndex + 1; i < allRows.length; i++) {
        const nextRow = allRows[i];
        if (nextRow.classList.contains('expanded-content')) {
          const nextNestingLevel = parseInt(nextRow.getAttribute('data-nesting-level')) || 1;
          
          // Remove ALL descendants (any level deeper than current)
          if (nextNestingLevel > currentNestingLevel) {
            rowsToRemove.push(nextRow);
          } else if (nextNestingLevel <= currentNestingLevel) {
            // Hit a row at same or higher level, stop here
            break;
          }
        } else {
          // Hit a non-expanded-content row, stop here
          break;
        }
      }
      
      // Remove all the descendant rows
      rowsToRemove.forEach(rowToRemove => {
        rowToRemove.remove();
      });
      
      row.classList.remove('expanded');
      toggleIcon.textContent = ''; // Clear text since CSS handles the icon

      queueMicrotask(() => {
        const ae = document.activeElement;
        if (row.isConnected && (!ae || ae === document.body || !ae.isConnected)) {
          setListFocusedRow(row);
          row.focus();
        }
      });
    }
    
    function itemWebPath(item) {
      return item.web_path != null ? item.web_path : item.path;
    }

    function appendTextCell(row, text) {
      const td = document.createElement('td');
      td.textContent = text == null ? '' : String(text);
      row.appendChild(td);
    }

    /**
     * One table row for an item returned by lister/api.php (expanded subtree).
     * Uses DOM APIs so names and metadata are not parsed as HTML.
     */
    function buildExpandedContentRow(item, webPath, nestingLevel, parentWebPath) {
      const tr = document.createElement('tr');
      tr.className = 'item-row expanded-content';
      tr.setAttribute('data-type', item.is_directory ? 'directory' : 'file');
      tr.setAttribute('data-path', webPath);
      tr.setAttribute('data-nesting-level', String(nestingLevel));
      tr.setAttribute('data-parent-path', parentWebPath);
      tr.setAttribute('tabindex', '-1');

      const tdName = document.createElement('td');

      if (item.is_directory) {
        if (item.is_empty) {
          const wrap = document.createElement('span');
          wrap.className = 'empty-folder';
          const iconEl = document.createElement('span');
          iconEl.className = 'icon folder';
          const nameSpan = document.createElement('span');
          nameSpan.className = 'item-name';
          nameSpan.textContent = item.name;
          wrap.appendChild(iconEl);
          wrap.appendChild(nameSpan);
          tdName.appendChild(wrap);
        } else {
          const btn = document.createElement('button');
          btn.type = 'button';
          btn.className = 'directory-toggle';
          btn.setAttribute('data-path', webPath);
          const toggleIcon = document.createElement('span');
          toggleIcon.className = 'toggle-icon';
          const iconEl = document.createElement('span');
          iconEl.className = 'icon folder';
          const nameSpan = document.createElement('span');
          nameSpan.className = 'item-name';
          nameSpan.textContent = item.name;
          btn.appendChild(toggleIcon);
          btn.appendChild(iconEl);
          btn.appendChild(nameSpan);
          btn.setAttribute('tabindex', '-1');
          tdName.appendChild(btn);
        }
      } else {
        const a = document.createElement('a');
        a.className = 'file-link';
        const fileUrl = item.url || ('/' + encodeURIComponent(item.name));
        a.setAttribute('href', fileUrl);
        if (item.preview_kind) {
          a.setAttribute('data-preview', item.preview_kind);
        }
        a.setAttribute('tabindex', '-1');
        const iconSpan = document.createElement('span');
        const iconKey = item.icon || 'file';
        iconSpan.className = 'icon material-symbols-outlined ' + iconKey;
        iconSpan.setAttribute('aria-hidden', 'true');
        iconSpan.textContent = getIconSymbol(iconKey);
        const nameSpan = document.createElement('span');
        nameSpan.className = 'item-name';
        nameSpan.textContent = item.name;
        a.appendChild(iconSpan);
        a.appendChild(nameSpan);
        tdName.appendChild(a);
      }

      tr.appendChild(tdName);
      appendTextCell(tr, item.size_formatted || '-');
      appendTextCell(tr, item.modified_formatted != null ? item.modified_formatted : '');
      let typeLabel;
      if (item.is_directory) {
        typeLabel = item.is_empty ? (item.type || 'Empty folder') : (item.type || 'Folder');
      } else {
        typeLabel = item.type || 'Unknown';
      }
      appendTextCell(tr, typeLabel);

      return tr;
    }

    function createNestedContainer(parentRow, data) {
      const tbody = parentRow.parentNode;
      const allItems = [...(data.directories || []), ...(data.files || [])];
      
      // Calculate nesting level based on parent row
      const parentNestingLevel = parseInt(parentRow.getAttribute('data-nesting-level')) || 0;
      const nestingLevel = parentNestingLevel + 1;
      const parentWebPath = parentRow.getAttribute('data-path') || '';
      
      // Find where to insert (after the parent row)
      let insertAfter = parentRow;
      
      allItems.forEach(item => {
        const webPath = itemWebPath(item);
        const newRow = buildExpandedContentRow(item, webPath, nestingLevel, parentWebPath);
        tbody.insertBefore(newRow, insertAfter.nextSibling);
        insertAfter = newRow;
      });
    }
    
    function getIconSymbol(iconType) {
      return LISTER_ICON_SYMBOLS[iconType] ?? 'draft';
    }
  </script>
</body>
</html>
