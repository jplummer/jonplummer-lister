# Changelog

Notable changes to Lister. Deployment builds show a **compact ID** (last 8 digits of Unix time) in the footer instead of semantic versions.

## Recent changes

### 2026-03-21

- **Apache routing**: `.htaccess` catch-all sends non-file, non-directory URLs (e.g. `/neef`) to `index.php` so Lister’s in-app 404 runs; previously only `/` and real dirs were rewritten.
- **404 / errors**: Missing path under lister root returns HTTP 404 (`http_error_404.php`, shared chrome); `runtime_error.php` uses `lister.css` + same header/footer as listing; `DirectoryLister` no longer falls back to root for bad paths.
- **Accessibility**: Audit doc (`docs/accessibility-audit.md`); skip link; single page `h1`; table caption; `aria-expanded` on folder buttons; `aria-current` on sort; SR text for folder load; `plan.md` archive **2.12**.
- **Plan**: Shipping task to investigate non-Apache equivalents to `.htaccess` (nginx/Caddy/IIS, etc.) and honest drag-and-drop scope per host.
- **Plan / docs**: Optional anti-abuse expansion (curated bot lists, stricter rate limits) moved under Phase 4; autoindex not required for Lister (PHP enumeration) noted in plan, `requirements.md`, and `configuration.md`.
- **Plan**: Active Phase 2 trimmed to remaining work; shipped items stay in archive; **Explicitly cancelled** section for file filter by type/size (dropped).
- **Listing**: File and folder names use normal font weight (not bold). Preview modal prev/next applies to all previewable files in table order, not only images; capture-phase **←** / **→** on `<dialog>` unchanged.
- **Keyboard**: Roving row focus (↑↓, → / ← expand-collapse, Enter); **Space** opens preview when available, otherwise same as Enter; initial focus on first row; Esc closes preview; name-cell controls `tabindex="-1"`. Row focus: subtle cell background. Documented in `requirements.md`.
- **Modal previews**: `preview_kind` / `data-preview`; text via `lister/preview.php` (e.g. `.md`); PDF, **and** server-rendered web (`.html`, `.htm`, `.php`, `.shtml`, ASP/JSP/CFML/CGI where applicable) via `<iframe>` so output is rendered, not source; images; prev/next across all preview kinds in list order; `display.preview_text_max_bytes`.
- **Install surface**: Preflight install error rendered from root `index.php` only (removed extra root `install_error.php`). Docs: packaging favors clear upload target and runtime-only zips over minimizing file count.
- **Listing UX**: README block under the table (`ReadmePreview`, `display.readme_preview`); DR10 palette via `light-dark()`; Material Symbols file/folder icons (`IconSymbols.php`); sortable columns with query → cookie → config; API POST sends same `sort`/`dir` for expanded rows.
- **Expand / API**: Viewport loading indicator (≥500ms, overlapping fetches); `PathSanitizer`; when `security.enabled`, `lister/api.php` runs the same checks as pages and returns JSON 403 on deny.
- **Docs / scope**: `plan.md` + `requirements.md` reorganized (Phase 4 deferred commercialization); deployment context note in requirements.

### 2025-11-24

- Ongoing fixes and hook-driven deploy metadata updates (no single theme).

### 2025-11-23

- **URLs**: `rawurlencode` path segments so spaces become `%20` in links.
- **Deploy**: Data under `lister/data/`; footer deployment ID; favicon links + assets; git hooks in `scripts/`.
- **Docs / security**: Changelog and security dashboard documentation; sensitive-file hiding patterns.

## [1.0.0] — 2025-10-10

### Added

- **File hiding**: Dotfiles, sensitive names, OS cruft, app paths; patterns; hidden files still reachable by direct URL.
- **Security**: IP block after rate limit; exponential / per-IP limits; incident log (IP, UA, URI, referer, time); basic password admin at `/lister/admin.php`.
- **UX**: Loading states; clearer errors; empty-folder rows.
- **Dev**: `deploy.sh`, `teardown.sh`, security test script, pattern tests, `router.php` for `php -S`.

## [0.9.0] — 2025-10-08

### Added

- Expandable directories (AJAX), nesting, loading states, empty folders.
- Large extension map (~700), MIME detection; emoji icons (since replaced by Material Symbols in 2026-03 work).

### Changed

- Requirements: design principles, sorting, responsive as core, access-control noted as future.

## [0.8.0] — Initial release

- Core listing (sort, icons, sizes, dates, breadcrumbs).
- Basic rate limit, bot UA filter, `.htaccess` hardening, sanitization, suspicious-request logging.
- Theming aligned with jonplummer.com, dark mode, responsive layout.
- JSON config (`lister/config/default.json`).
