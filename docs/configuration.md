# Configuration Guide

## Environment Setup

Create a `.env` file in the project root with your hosting details:

```bash
# Hosting Configuration
HOST_SERVER=your-server.com
HOST_USERNAME=your-username
HOST_PASSWORD=your-password
HOST_REMOTE_PATH=/path/to/your/website/
HOST_DOMAIN=your-domain.com
```

## Supported Hosting Providers

Lister works with any hosting provider that supports:
- PHP 8.x
- Apache with mod_php
- SFTP access
- .htaccess support

You do **not** need the host’s built-in **directory listing** (autoindex) turned on. Lister lists files via PHP.

### Examples

**Dreamhost:**
```bash
HOST_SERVER=iad1-shared-e1-29.dreamhost.com
HOST_USERNAME=your-username
HOST_PASSWORD=your-password
HOST_REMOTE_PATH=/home/your-username/your-domain.com/
HOST_DOMAIN=your-domain.com
```

**cPanel/WHM:**
```bash
HOST_SERVER=your-domain.com
HOST_USERNAME=your-cpanel-username
HOST_PASSWORD=your-cpanel-password
HOST_REMOTE_PATH=/public_html/
HOST_DOMAIN=your-domain.com
```

**DigitalOcean/AWS:**
```bash
HOST_SERVER=your-server-ip
HOST_USERNAME=root
HOST_PASSWORD=your-password
HOST_REMOTE_PATH=/var/www/html/
HOST_DOMAIN=your-domain.com
```

## Security Notes

- Never commit your `.env` file to version control
- Use strong passwords for hosting accounts
- Consider using SSH keys instead of passwords when possible
- Regularly update your hosting credentials

## Lister `config/default.json`

  - **`app.debug`** (boolean, default `false`): When **false**, uncaught errors handled by root `index.php` show a **generic message** to visitors and write the real exception (type, message, file, line) to the **PHP `error_log`**. When **true**, the runtime error page shows full **Details** and install-oriented suggestions—use only on trusted local/dev hosts.
  - **`display.readme_preview`** (boolean, default `true`): When a recognized README exists in the directory being listed (e.g. `README.md`, `README.txt`), render it below the file table. Set to `false` to disable. Files larger than 512 KiB are skipped.
  - **`display.preview_text_max_bytes`** (integer, default `524288`): Maximum size for text/code/markdown content loaded into the modal preview via `lister/preview.php`. Larger files show an error in the modal.

## Server-level 404 vs Lister’s 404

Lister returns its **HTML 404** only when the request is **handled by PHP** (e.g. Apache rewrites an unknown path to `index.php` per the shipped `.htaccess`).

If the web server answers **before** PHP runs, you see the **host’s** 404 (or plain “Not Found”), not Lister:

- **Missing static file** under the docroot (e.g. typo in `.css` / image URL) — often **not** rewritten to `index.php`.
- **nginx / Caddy** without an equivalent “try unknown URIs through `index.php`” rule.

**Apache (optional):** You can set `ErrorDocument 404 /index.php` so more requests hit Lister; that may change behavior for assets you want the server to 404 on its own—test after enabling.

**nginx (conceptual):** Use `try_files` so only existing files are served and other requests fall through to `index.php` (exact snippet depends on your `root`, PHP-FPM setup, and path prefix).

See `docs/error-smoke-tests.md` for quick checks after deploy.

## Troubleshooting

**Connection Issues:**
- Verify server details in `.env`
- Check SFTP port (usually 22)
- Ensure hosting provider allows SFTP access

**Permission Issues:**
- Verify remote path exists
- Check file permissions on server
- Ensure user has write access to target directory
