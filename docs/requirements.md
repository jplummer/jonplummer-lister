# The basic idea 

Lister exposes the contents of any web-accessible folder and subfolders for browsing and sharing. With minimal configuration, installation takes less than a minute. It's meant to be a custom from-scratch work-alike of https://www.directorylister.com/

**Deployment context**: The product priority is **personal, convenient file access**. Automated abuse (bots, scrapers) matters more than hiding listings from casual humans. Multi-tenant access, sellable hosting, and similar scenarios are **out of scope** for the current deployment; they are captured in [section 5](#5-commercialization-and-multi-user-deferred) and **Phase 4** in `plan.md`.

## 1. Core Features

* Directory listing shows a sortable list of files for any web-accessible directory or subdirectory; column headers change sort. The active sort is stored in an HttpOnly cookie so navigation without query parameters stays consistent, while `sort` and `dir` query parameters override the cookie and refresh it so URLs can encode a specific view
* File type icons show file types at a glance
* Drag-and-drop installation allows you to be up and running in less than a minute and is straightforward to remove
* Shipped packages should minimize installer confusion: clear instructions for what to upload and where, a purposeful top-level layout in the delivered tree, and runtime-only contents in release artifacts. Total file count is not a primary packaging goal
* Sortable columns: default alphabetical by filename, with options to sort by type, size, or date
* Theme and styling integrate with https://jonplummer.com/, including dark mode support
* Direct URL access for sharing files
* Anti-abuse capabilities: exponential rate-limiting and bot scraping prevention
* Responsive design for mobile and desktop
* README preview: if the listed directory contains a recognized README file (`README.md`, `README.txt`, etc.), rendered content appears below the listing (Markdown via Parsedown safe mode, plain text escaped)

## 2. Features to be considered

* Text preview allows you to see a preview of a text or markdown document (single file, not the directory README)
* Image preview allows you to see a preview of a selected image and page through images in the directory

## 3. Features to be considered later

* File search to locate files
* File hashes for download verification
* Multi-file download: select multiple files/folders and download as zip
* MCP (Model Context Protocol) integration for AI agents

## 4. Nice to have features

* Shared design system package to coordinate theme data with jonplummer-11ty and other projects

## 5. Commercialization and multi-user (deferred)

Not required for the current personal deployment. Revisit if Lister is offered as a product or service, serves multiple tenants, or holds sensitive data. Roadmap: **Phase 4** in `plan.md`.

* Directory access control: ability to restrict access to specific directories
* OWASP-oriented security headers and related hardening (see plan Phase 4)
* Authenticated file management:
  * Upload files from computer to selected folder
  * Drag-and-drop file upload and rearrangement
  * Delete files or folders
  * Authentication via SSH key or user/pass (sshpass)
* Enhanced security admin dashboard features (beyond the current basic dashboard):
  * **Security concerns to prevent, trap, and/or log:**
    * Bot scraping and automated access (curl, wget, scrapers, crawlers, spiders)
    * Rate limit violations (excessive requests per minute)
    * Directory traversal attempts (path manipulation with `..` or `//`)
    * Suspicious query parameters (cmd, exec, system, eval, shell, passwd, shadow)
    * Suspicious file extension requests (.php, .asp, .jsp, .py, .sh, .exe in URLs)
    * Missing or suspicious user agents
    * IP-based blocking and access attempts from blocked IPs
  * **Dashboard should display:**
    * Total security incidents count
    * Recent security incidents with details (type, IP, timestamp, user agent, request URI, referer)
    * Current security configuration settings
    * Ability to view blocked IPs and their status
    * Filtering and search capabilities for incident logs
    * Export capabilities for security logs
  * **Access control:**
    * Password-protected access
    * Session management to avoid repeated password entry
    * Secure authentication mechanism

## Changelog

* **2026-03-21** — Installation packaging: requirements now favor clear upload instructions, sane top-level layout in shipped artifacts, and excluding non-runtime files from releases over minimizing total file count; `plan.md` “Shipping & installation” captures the optional backlog item (replacing a misplaced “reduce file count” polish note). *Rationale:* Drag-and-drop success depends on cognitive load and the correct target, not file-count metrics.
* **2026-03-21** — README preview below the directory table when a matching README exists in that folder; `display.readme_preview` in config toggles it; `lister/includes/Parsedown.php` (MIT) parses Markdown in safe mode; `.txt` readmes are escaped plain text. *Rationale:* GitHub-style folder documentation on the listing page without JavaScript.
* **2026-03-21** — File type and folder icons use Material Symbols Outlined loaded from Google Fonts (ligature names in `lister/includes/IconSymbols.php`). Listing pages request `fonts.googleapis.com` / `fonts.gstatic.com`. *Rationale:* Consistent outlined icon set; emoji removed from the name column.
* **2026-03-21** — Sort preference: clickable column headers; resolution order is query string (overrides), then cookie `lister_sort`, then `display.default_sort` / `display.sort_direction` in config. The cookie is updated when the URL carries `sort` or `dir`. AJAX directory expansion sends the same `sort`/`dir` as the page so nested listings match. *Rationale:* Stable sort across navigation, shareable URLs, and consistent behavior for in-page expansion.
* **2026-03-21** — When `security.enabled` is true in config, `lister/api.php` uses the same Security checks as HTML page loads (blocked IP, bot-style user agent, per-minute rate limit). Denied API responses use JSON with HTTP 403 so the expandable UI can surface errors consistently. *Rationale:* The listing UI loads children via POST to the API; leaving that endpoint unguarded weakened the intended anti-abuse behavior.
* **2026-03-21** — Documented deployment context (personal use; bots over human snooping). Moved directory access control, authenticated file management, enhanced security admin depth, and OWASP-style hardening into **section 5 (deferred)** and aligned with **Phase 4** in `plan.md`. Deduplicated MCP (listed once under section 3). *Rationale:* Separate current scope from commercialization / multi-user work so the backlog matches actual priorities.
