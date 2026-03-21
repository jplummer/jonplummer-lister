# Lister - Directory Listing Application

A simple, clean directory listing application for web servers.

## 📦 Install Package

**For installation, you only need these files:**

- `index.php` - Main application entry point (includes preflight install error UI)
- `.htaccess` - Apache security rules
- `lister/` - Application directory (all contents)
- `INSTALL.md` - Installation instructions

**Do not upload these development files:**
- `docs/` - Development documentation
- `scripts/` - Development and deployment scripts
- `router.php` - Development server router
- `test_security.php` - Test file

See [INSTALL.md](INSTALL.md) for detailed installation instructions.

## Quick Start

### Local Development
```bash
# Start development server with router (recommended)
php -S localhost:8000 router.php

# Test the application
curl http://localhost:8000
```

### Deployment
```bash
# Deploy to web server
./scripts/deploy.sh

# Remove from web server
./scripts/teardown.sh
```

## Project Structure

```
.
├── index.php              # Main application entry point (preflight install errors)
├── .htaccess              # Apache security rules
├── lister/                # Application directory
│   ├── config/
│   │   └── default.json   # Configuration
│   ├── includes/          # PHP classes
│   └── assets/            # CSS, JS, icons
├── scripts/
│   └── deploy.sh          # Deployment script
└── docs/
    ├── plan.md            # Development plan
    ├── requirements.md    # Project requirements
    └── notes.md           # Development notes
```

## Configuration

See [docs/configuration.md](docs/configuration.md) for hosting setup and environment configuration.

## Development

See [docs/notes.md](docs/notes.md) for development workflow and troubleshooting.

## Requirements

- PHP 8.x
- Apache with mod_php

## Acknowledgments

- **File Type Detection**: Uses [dyne/file-extension-list](https://github.com/dyne/file-extension-list) for comprehensive file extension to type mapping
- **Icons**: [Material Symbols Outlined](https://fonts.google.com/icons) via Google Fonts for file-type and folder glyphs
- **README rendering**: [Parsedown](https://github.com/erusev/parsedown) (MIT) for Markdown previews

## License

MIT License (Parsedown is bundled under its own MIT license in `lister/includes/Parsedown.php`)
