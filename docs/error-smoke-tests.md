# Error handling — smoke tests

Run on a **deployed** host after changes to routing, `index.php`, or error templates. Tick when verified.

## Lister in-app 404

- [ ] Visit a path that is **not** a file or directory under the listing (e.g. `/neef`). Expect **HTTP 404**, Lister chrome, “Page not found”, requested path shown, links to listing root and jonplummer.com.

## Lister runtime error (optional, dev only)

- [ ] Set `app.debug` to **true** in `lister/config/default.json`, trigger a **safe** failure (e.g. temporarily rename `lister/templates/index.php`). Expect **HTTP 500**, page shows **Details** and suggestions.
- [ ] Set `app.debug` to **false**, repeat. Expect **generic** message, no internal paths in the page; confirm **error_log** on the server contains the real exception line (`Lister […]`).

## Server 404 (no PHP)

- [ ] Request a **obviously missing static** URL that your server does not rewrite to `index.php` (e.g. `/no-such-asset-12345.css` if that file does not exist). Expect the **host** 404 (or default body), not Lister’s template—this is expected unless you add `ErrorDocument` / nginx rules.

## Preflight install error (optional)

- [ ] On a **throwaway copy**, remove one required file from `lister/` and load the site. Expect preflight **500** install UI (inline styles; does not use `lister.css`).
