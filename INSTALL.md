# Lister Installation Guide

## Quick Installation (Under 1 Minute)

### Step 1: Upload Files
Upload only the following files and folders to your web server's root (or target) directory:

- `index.php`
- `install_error.php` (installation error page; keep next to `index.php`)
- `.htaccess`
- `lister/` folder

Do not upload documentation files or other non-essential items from the repository.

### Step 2: Set Permissions
```bash
chmod 644 index.php
chmod 644 install_error.php
chmod 644 .htaccess
chmod 644 lister/config/*.json
```

### Step 3: Test
Visit `https://yourdomain.com/` in your browser.

## File Structure
```
your-domain.com/
├── index.php              # Main application
├── install_error.php      # Missing-file install error (loaded by index.php)
├── .htaccess              # Security rules
└── lister/
    ├── api.php            # AJAX API endpoint
    ├── config/
    │   ├── default.json   # Configuration (required)
    │   └── extensions.json # File type mappings (optional)
    ├── includes/          # PHP classes
    │   ├── App.php
    │   ├── DirectoryLister.php
    │   └── Security.php
    ├── templates/
    │   ├── index.php      # Main template
    │   └── runtime_error.php # Fatal error page (loaded by index.php)
    └── assets/            # CSS, JS, icons
```

## Configuration
Edit `lister/config/default.json` to customize:
- Display settings
- Security options
- Theme preferences

## Removal
To remove Lister, you can either:

### Option 1: Use the teardown script
```bash
./scripts/teardown.sh
```

### Option 2: Manual removal
Delete the following files from your server:
- `index.php`
- `.htaccess`
- `lister/` directory

## Support
See `docs/notes.md` for development and troubleshooting information.
