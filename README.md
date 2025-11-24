# Lister - Directory Listing Application

A simple, clean directory listing application for web servers.

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
├── index.php              # Main application entry point
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

## Installation

See [INSTALL.md](INSTALL.md) for detailed installation instructions.

## Configuration

See [docs/configuration.md](docs/configuration.md) for hosting setup and environment configuration.

## Development

See [docs/notes.md](docs/notes.md) for development workflow and troubleshooting.

## Requirements

- PHP 8.x
- Apache with mod_php

## Acknowledgments

- **File Type Detection**: Uses [dyne/file-extension-list](https://github.com/dyne/file-extension-list) for comprehensive file extension to type mapping
- **Icons**: Emoji-based file type icons for clean, universal display

## License

MIT License
