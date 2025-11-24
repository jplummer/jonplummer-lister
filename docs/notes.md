# Lister Development Notes

## Quick Start

### Local Development
```bash
# Start local PHP server with router (recommended)
php -S localhost:8000 router.php

# Or start without router (basic)
php -S localhost:8000

# Test the application
curl http://localhost:8000
# or visit http://localhost:8000 in browser

# Stop server (if running in background)
pkill -f "php -S localhost:8000"
```

**Router.php**: The `router.php` file enables proper URL routing for the PHP built-in server, making it behave like Apache with mod_rewrite. It routes directory requests to `index.php` and serves static files directly. Only needed for local development - production uses `.htaccess` for routing.

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

### 1. Making Changes
1. Edit files in your IDE
2. Test locally: `php -S localhost:8000`
3. Check syntax: `php -l index.php`
4. Test functionality in browser
5. Commit changes when ready

### 2. Testing
- **Local Testing**: Use `php -S localhost:8000 router.php`
- **Syntax Check**: `php -l filename.php`
- **Browser Testing**: Visit `http://localhost:8000`
- **Security Testing**: Run `php scripts/test_security.php`
- **API Testing**: Use `curl` or browser dev tools

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

## Current Status

- **Production**: Deployed and working on misc.jonplummer.com
- **Security**: Active monitoring via admin panel
- **Development**: Ready for additional features
- **Documentation**: Complete and up-to-date

## Useful Resources

- [PHP Manual](https://www.php.net/manual/)
- [Apache .htaccess Guide](https://httpd.apache.org/docs/current/howto/htaccess.html)
- [Dreamhost PHP Documentation](https://help.dreamhost.com/hc/en-us/categories/115000252511-PHP)
- [JSON Configuration](https://www.json.org/)

---

*Last updated: 2025-01-10*
*PHP Version: 8.4.13*
*Project: Lister Directory Listing Application*
*Status: Production Ready*
