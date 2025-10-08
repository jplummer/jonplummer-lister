# Lister Development Notes

## Quick Start

### Local Development
```bash
# Start local PHP server
php -S localhost:8000

# Test the application
curl http://localhost:8000
# or visit http://localhost:8000 in browser

# Stop server (if running in background)
pkill -f "php -S localhost:8000"
```

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
├── index.php              # Main application entry point
├── .htaccess              # Apache security rules
├── config/
│   └── default.json       # Default configuration
├── includes/              # PHP classes (future)
├── assets/
│   ├── css/               # Stylesheets
│   ├── js/                # JavaScript
│   └── icons/             # File type icons
└── docs/
    ├── plan.md            # Development plan
    ├── requirements.md    # Project requirements
    └── notes.md           # This file
```

## Development Workflow

### 1. Making Changes
1. Edit files in your IDE
2. Test locally: `php -S localhost:8000`
3. Check syntax: `php -l index.php`
4. Test functionality in browser
5. Commit changes when ready

### 2. Testing
- **Local Testing**: Use `php -S localhost:8000`
- **Syntax Check**: `php -l filename.php`
- **Browser Testing**: Visit `http://localhost:8000`
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
- Edit `config/default.json` for app settings
- Modify `.htaccess` for Apache rules
- Update `index.php` for main functionality

## Common Commands

### PHP Development
```bash
# Start development server
php -S localhost:8000

# Check PHP version
php --version

# Run PHP with specific ini file
php -c /path/to/php.ini -S localhost:8000

# Check loaded extensions
php -m
```

### File Operations
```bash
# Create directory structure
mkdir -p config includes assets/{css,js,icons}

# Check file permissions
ls -la

# Set proper permissions (if needed)
chmod 644 *.php
chmod 644 config/*.json
chmod 644 .htaccess
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
- Verify `config/default.json` is valid JSON
- Check file paths are correct
- Ensure all required directories exist

### Debug Mode
```bash
# Enable error reporting in PHP
php -d display_errors=1 -S localhost:8000

# Check PHP error log
tail -f /opt/homebrew/var/log/php/error.log
```

## Development Phases

### Phase 1: Core Foundation (Current)
- [x] Basic PHP structure
- [x] Configuration system
- [x] Security rules (.htaccess)
- [ ] Directory listing engine
- [ ] User interface
- [ ] Theming & styling

### Phase 2: Enhanced Features
- [ ] File management
- [ ] User experience improvements
- [ ] Advanced security

### Phase 3: Future Enhancements
- [ ] Search & discovery
- [ ] File operations
- [ ] Authentication system

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
- Rate limiting (future)
- Bot detection (future)
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

## Next Steps

1. **Deploy to Dreamhost** - Test in production environment
2. **Build Directory Engine** - Core listing functionality
3. **Create UI** - User interface and styling
4. **Add Security** - Rate limiting and bot detection
5. **Test & Iterate** - Continuous improvement

## Useful Resources

- [PHP Manual](https://www.php.net/manual/)
- [Apache .htaccess Guide](https://httpd.apache.org/docs/current/howto/htaccess.html)
- [Dreamhost PHP Documentation](https://help.dreamhost.com/hc/en-us/categories/115000252511-PHP)
- [JSON Configuration](https://www.json.org/)

---

*Last updated: 2025-10-08*
*PHP Version: 8.4.13*
*Project: Lister Directory Listing Application*
