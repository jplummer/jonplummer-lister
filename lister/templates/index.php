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
                          <a href="<?= htmlspecialchars($item['url']) ?>" class="file-link">
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

    document.addEventListener('DOMContentLoaded', function() {
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
          tdName.appendChild(btn);
        }
      } else {
        const a = document.createElement('a');
        a.className = 'file-link';
        const fileUrl = item.url || ('/' + encodeURIComponent(item.name));
        a.setAttribute('href', fileUrl);
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
