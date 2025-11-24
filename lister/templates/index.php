<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Miscellaneous - Directory Listing</title>
  <link rel="icon" href="lister/assets/images/2021/02/jp_round-48x48.jpg" sizes="32x32">
  <link rel="icon" href="lister/assets/images/2021/02/jp_round.jpg" sizes="192x192">
  <link rel="apple-touch-icon" href="lister/assets/images/2021/02/jp_round-180x180.jpg">
  <link rel="stylesheet" href="lister/assets/lister.css">
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
                    <th>Name</th>
                    <th>Size</th>
                    <th>Modified</th>
                    <th>Type</th>
                  </tr>
                </thead>
                <tbody id="directory-contents">
                  <?php 
                  // Combine directories and files, directories first
                  $allItems = array_merge($data['directories'] ?? [], $data['files'] ?? []);
                  foreach ($allItems as $item): 
                  ?>
                    <tr class="item-row" data-type="<?= $item['is_directory'] ? 'directory' : 'file' ?>" data-path="<?= htmlspecialchars($item['path']) ?>" data-nesting-level="0">
                      <td>
                        <?php if ($item['is_directory']): ?>
                          <?php if (isset($item['is_empty']) && $item['is_empty']): ?>
                            <span class="empty-folder">
                              <span class="icon folder"></span>
                              <?= htmlspecialchars($item['name']) ?>
                            </span>
                          <?php else: ?>
                            <button class="directory-toggle" data-path="<?= htmlspecialchars($item['path']) ?>">
                              <span class="toggle-icon"></span>
                              <span class="icon folder"></span>
                              <?= htmlspecialchars($item['name']) ?>
                            </button>
                          <?php endif; ?>
                        <?php else: ?>
                          <a href="<?= htmlspecialchars($item['url']) ?>" class="file-link">
                            <span class="icon <?= htmlspecialchars($item['icon']) ?>">
                              <?= $getIconSymbol($item['icon']) ?>
                            </span>
                            <?= htmlspecialchars($item['name']) ?>
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
          </div>
        <?php endif; ?>
      </section>
    </article>
  </main>

  <footer>
    <p>Lister v<?= htmlspecialchars($config['app']['version'] ?? '1.0.0') ?> © <?= date('Y') ?> Jon Plummer</p>
  </footer>

  <script>
    // Expandable directory functionality with nested containers
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
      
      // Show loading state
      toggleIcon.textContent = '⏳';
      
      // Make API request
      fetch(`lister/api.php?path=${encodeURIComponent(path)}`)
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Create nested container
            createNestedContainer(row, data.data);
            row.classList.add('expanded');
            toggleIcon.textContent = ''; // Clear text since CSS handles the icon
          } else {
            console.error('Error loading directory:', data.error);
            toggleIcon.textContent = ''; // Clear text since CSS handles the icon
          }
        })
        .catch(error => {
          console.error('Error:', error);
          toggleIcon.textContent = ''; // Clear text since CSS handles the icon
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
      const currentPath = row.getAttribute('data-path');
      
      // Find all rows after this one that are descendants of this directory
      for (let i = currentIndex + 1; i < allRows.length; i++) {
        const nextRow = allRows[i];
        if (nextRow.classList.contains('expanded-content')) {
          const nextNestingLevel = parseInt(nextRow.getAttribute('data-nesting-level')) || 1;
          const parentPath = nextRow.getAttribute('data-parent-path');
          
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
    
    function createNestedContainer(parentRow, data) {
      const tbody = parentRow.parentNode;
      const allItems = [...(data.directories || []), ...(data.files || [])];
      
      // Calculate nesting level based on parent row
      const parentNestingLevel = parseInt(parentRow.getAttribute('data-nesting-level')) || 0;
      const nestingLevel = parentNestingLevel + 1;
      
      // Find where to insert (after the parent row)
      let insertAfter = parentRow;
      
      allItems.forEach(item => {
        const newRow = document.createElement('tr');
        newRow.className = 'item-row expanded-content';
        newRow.setAttribute('data-type', item.is_directory ? 'directory' : 'file');
        newRow.setAttribute('data-path', item.path);
        newRow.setAttribute('data-nesting-level', nestingLevel);
        newRow.setAttribute('data-parent-path', parentRow.getAttribute('data-path'));
        
        if (item.is_directory) {
          if (item.is_empty) {
            newRow.innerHTML = `
              <td>
                <span class="empty-folder">
                  <span class="icon folder"></span>
                  ${item.name}
                </span>
              </td>
              <td>${item.size_formatted || '-'}</td>
              <td>${item.modified_formatted}</td>
              <td>${item.type || 'Empty folder'}</td>
            `;
          } else {
            newRow.innerHTML = `
              <td>
                <button class="directory-toggle" data-path="${item.path}">
                  <span class="toggle-icon"></span>
                  <span class="icon folder"></span>
                  ${item.name}
                </button>
              </td>
              <td>${item.size_formatted || '-'}</td>
              <td>${item.modified_formatted}</td>
              <td>${item.type || 'Folder'}</td>
            `;
          }
        } else {
          newRow.innerHTML = `
            <td>
              <a href="${item.url}" class="file-link">
                <span class="icon ${item.icon}">${getIconSymbol(item.icon)}</span>
                ${item.name}
              </a>
            </td>
            <td>${item.size_formatted || '-'}</td>
            <td>${item.modified_formatted}</td>
            <td>${item.type || 'Unknown'}</td>
          `;
        }
        
        // Insert after the current insertAfter position
        tbody.insertBefore(newRow, insertAfter.nextSibling);
        insertAfter = newRow;
      });
    }
    
    function getIconSymbol(iconType) {
      const icons = {
        // Folders
        'folder': '📁',
        
        // Basic file types
        'file': '📄',
        
        // Media types
        'image': '🖼️',
        'video': '🎬',
        'audio': '🎵',
        
        // Documents and text
        'document': '📄',
        'text': '📝',
        'pdf': '📕',
        'book': '📚',
        
        // Code and development
        'code': '💻',
        'web': '🌐',
        'exec': '⚙️',
        
        // Data and office
        'spreadsheet': '📊',
        'sheet': '📊',
        'presentation': '📽️',
        'slide': '📽️',
        
        // Archives and storage
        'archive': '📦',
        
        // System and fonts
        'font': '🔤',
        'config': '⚙️',
        'backup': '💾',
        'database': '🗄️',
        'cad': '📐',
        'ebook': '📖',
        'game': '🎮'
      };
      return icons[iconType] || '📄';
    }
  </script>
</body>
</html>
