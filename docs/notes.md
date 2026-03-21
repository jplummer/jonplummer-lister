# Lister Development Notes

## Quick Start

### Local Development
```bash
# Start local PHP server with router (required)
php -S localhost:8000 router.php

# Test the application
curl http://localhost:8000
# or visit http://localhost:8000 in browser

# Stop server (if running in background)
pkill -f "php -S localhost:8000"
```

**Note: router.php**: Required for local development. Located in your project root (same folder as `index.php`). The PHP built-in server doesn't handle directory URLs the same way Apache does, so router.php ensures directory requests are properly routed to `index.php`. **Don't deploy to production** as remote servers use `.htaccess` for routing instead.

### PHP Syntax Check
```bash
# Check PHP syntax
php -l index.php

# Check all PHP files
find . -name "*.php" -exec php -l {} \;
```

## Project Structure

```
lister/
├── index.php              # Main application entry point – DEPLOYABLE
├── .htaccess              # Apache security rules – DEPLOYABLE
├── router.php             # PHP built-in server router (dev only)
├── lister/                # Main application directory – DEPLOYABLE
│   ├── config/
│   │   ├── default.json   # Default configuration
│   │   └── extensions.json # File type mappings
│   ├── includes/          # PHP classes
│   │   ├── App.php        # Main application class
│   │   ├── DirectoryLister.php # Directory scanning
│   │   └── Security.php   # Security & rate limiting
│   ├── templates/
│   │   └── index.php      # Main template
│   ├── assets/
│   │   └── lister.css     # Stylesheet
│   ├── api.php            # AJAX API endpoint
│   ├── admin.php          # Security admin panel
│   └── data/              # Runtime data (git ignored)
│       ├── security.log   # Security incidents
│       └── rate_*.json    # Rate limiting data
├── scripts/               # Utility scripts
│   ├── deploy.sh          # Deployment script
│   ├── teardown.sh        # Removal script
│   ├── test_security.php  # Security testing
│   └── test_pattern.php   # Pattern matching tests
└── docs/
    ├── plan.md            # Development plan
    ├── requirements.md    # Project requirements
    ├── notes.md           # This file
    └── configuration.md   # Configuration guide
```

## Development Workflow

### Standard Process
1. **Edit files** in your IDE
2. **Test locally**: Start server with `php -S localhost:8000 router.php`, then visit `http://localhost:8000` in browser
3. **Test on host**: If local testing looks good, deploy and test on production server
4. **Commit changes**: If everything works, commit your changes

**About the router**: `router.php` is in your local project root (same folder as `index.php` and `lister/`) and is only used for local development. Always use it when starting the local server - the PHP built-in server doesn't handle directory URLs the same way Apache does, so without the router, directory listings won't work properly. **Do not deploy `router.php` to your remote server** - production uses `.htaccess` for routing instead.

### Optional: Syntax Checking
Before testing, you can check for PHP syntax errors: `php -l filename.php`. This catches typos and parse errors that would prevent the server from starting. Useful if the server won't start and you're not sure why.

### Optional: API Testing
The app uses `lister/api.php` for AJAX requests when expanding directories. If directory expansion isn't working, you can test the API directly:
```bash
curl -X POST -d 'path=some/relative/folder' http://localhost:8000/lister/api.php
```
Or use browser dev tools Network tab to inspect API responses. Only needed when debugging directory expansion issues.

### 3. Deployment to Web Server
```bash
# Deploy to web server
./scripts/deploy.sh

# Remove from web server
./scripts/teardown.sh

# Option 1: Direct upload via SFTP
# Upload all files to target directory on your web server

# Option 2: Git-based deployment
git add .
git commit -m "Your commit message"
git push origin main
# Then pull on your web server

# Option 3: Automated deployment (future)
# Set up CI/CD pipeline
```

### 4. Configuration
- Edit `lister/config/default.json` for app settings
- Modify `.htaccess` for Apache rules
- Update `lister/includes/App.php` for main functionality
- Access security admin at `yourdomain.com/lister/admin.php`

## Common Commands

### PHP Development
```bash
# Start development server with router
php -S localhost:8000 router.php

# Check PHP version
php --version

# Run PHP with specific ini file
php -c /path/to/php.ini -S localhost:8000 router.php

# Check loaded extensions
php -m

# Test security system
php scripts/test_security.php
```

### File Operations
```bash
# Check file permissions
ls -la

# Set proper permissions (if needed)
chmod 644 *.php
chmod 644 lister/config/*.json
chmod 644 .htaccess
chmod 755 lister/
chmod 755 lister/includes/
chmod 755 lister/templates/
chmod 755 lister/assets/

# Check security logs
tail -f lister/data/security.log
```

### Git Operations
```bash
# Check status
git status

# Add changes
git add .

# Commit changes
git commit -m "Descriptive commit message"

# Push to remote
git push origin main

# Pull latest changes
git pull origin main
```

## Troubleshooting

### Common Issues

**PHP Server Won't Start**
- Check if port 8000 is in use: `lsof -i :8000`
- Try different port: `php -S localhost:8080`
- Check PHP installation: `which php`

**Syntax Errors**
- Run `php -l filename.php` to check syntax
- Check for missing semicolons, brackets, quotes
- Verify PHP version compatibility

**Permission Issues**
- Check file permissions: `ls -la`
- Ensure web server can read files
- Check `.htaccess` rules

**Configuration Issues**
- Verify `lister/config/default.json` is valid JSON
- Check file paths are correct
- Ensure all required directories exist
- Check security configuration in admin panel

### Debug Mode
```bash
# Enable error reporting in PHP
php -d display_errors=1 -S localhost:8000

# Check PHP error log
tail -f /opt/homebrew/var/log/php/error.log
```


## Environment Setup

### Local Development
- **PHP**: 8.4.13 (via Homebrew)
- **Server**: Built-in PHP development server
- **OS**: macOS (darwin 25.0.0)

### Production (Dreamhost)
- **PHP**: 8.x (Dreamhost compatible)
- **Server**: Apache with mod_php
- **OS**: Linux

## File Types & Extensions

### PHP Files
- `.php` - Main application files
- `.json` - Configuration files
- `.htaccess` - Apache configuration

### Frontend Files
- `.css` - Stylesheets
- `.js` - JavaScript
- `.html` - HTML templates (if needed)

### Documentation
- `.md` - Markdown documentation
- `.txt` - Plain text files

## Security Considerations

### .htaccess Rules
- Deny access to config files
- Hide PHP includes from web access
- Block hidden files
- Enable clean URLs

### PHP Security
- Input validation and sanitization
- Rate limiting (30 requests/minute)
- Bot detection (blocks curl, wget, etc.)
- Suspicious request detection
- Security logging and admin panel
- Error handling

## Performance Tips

### Local Development
- Use PHP's built-in server for development
- Enable opcache in production
- Minimize file I/O operations
- Use efficient data structures

### Production
- Enable gzip compression
- Use CDN for static assets
- Implement caching strategies
- Monitor server resources


## Technology Choices

The application is installed in a web folder on any PHP-compatible hosting provider. Technologies considered during development:
* cURL
* Git
* SOAP
* CGI
* .htaccess (selected)
* JSON
* Perl
* PHP (selected)
* Laravel
* Cake
* Python
* Ruby
* mod-rewrite
* SFTP
* SSH

## Useful Resources

- [PHP Manual](https://www.php.net/manual/)
- [Apache .htaccess Guide](https://httpd.apache.org/docs/current/howto/htaccess.html)
- [Dreamhost PHP Documentation](https://help.dreamhost.com/hc/en-us/categories/115000252511-PHP)
- [JSON Configuration](https://www.json.org/)

