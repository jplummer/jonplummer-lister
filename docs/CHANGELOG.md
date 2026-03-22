# Changelog

Notable changes to Lister. Deployment builds show a **compact ID** (last 8 digits of Unix time) in the footer instead of semantic versions.

## Recent changes

### 2026-03-21

- **Modal previews**: `preview_kind` / `data-preview` on rows; `<dialog>` for text (`lister/preview.php` + Parsedown for `.md`), PDF (`iframe`), images with prev/next within the visible table; `display.preview_text_max_bytes`.
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
