# Changelog

All notable changes to Lister will be documented in this file.

**Note**: As of 2025-11-23, this project uses git commit hashes for deployment tracking instead of semantic versioning. The deployed commit hash is displayed in the application footer.

## Recent Changes

### 2025-11-23

#### Changed
- Update color palette to DR10 with WCAG AA contrast adjustments
- Update documentation and configuration files
- Replaced versioning system with git commit hash tracking for deployment verification
- Move data directory to lister/data/ for safer deployment
- Fixed URL encoding for files and directories with spaces in names
  - Changed from `urlencode` to `rawurlencode` to properly encode spaces as %20 instead of +

#### Added
- Add favicon and apple-touch-icon links to template
- Add favicon image assets
- Add git hooks to scripts/ with setup script

#### Security
- Enhance security and documentation: block sensitive files, add changelog, document security dashboard

## [1.0.0] - 2025-10-10

### Added
- **File Hiding System**: Configurable options to hide files from directory listings
  - Hide dotfiles (enabled by default)
  - Hide sensitive files (SSH keys, certificates, .env files, etc.)
  - Hide OS-specific cruft (.DS_Store, Thumbs.db, etc.)
  - Hide application files (index.php, lister/ directory, etc.)
  - Pattern-based matching for flexible filtering
  - Hidden files remain accessible via direct URL

### Security
- **IP-based Blocking System**: Automatic blocking of IPs that exceed rate limits
  - Temporary blocks (default 5 minutes)
  - Block expiration and automatic cleanup
  - Logging of blocked IP access attempts

- **Enhanced Rate Limiting**: More sophisticated rate limiting implementation
  - Configurable requests per minute (default: 30)
  - Per-IP tracking with automatic cleanup
  - Exponential backoff for violations

- **Request Logging and Monitoring**: Comprehensive security incident logging
  - Logs all security incidents with full context
  - Tracks IP, user agent, request URI, referer, timestamp
  - Incident types: BOT_DETECTED, RATE_LIMIT_EXCEEDED, SUSPICIOUS_REQUEST, BLOCKED_IP_ACCESS_ATTEMPT

- **Security Admin Dashboard**: Basic admin interface for security monitoring
  - Password-protected access (basic implementation)
  - View total security incidents count
  - Display recent security incidents with details
  - View current security configuration
  - Accessible at `/lister/admin.php`

### User Experience
- **Loading States and Transitions**: Improved visual feedback during directory operations
- **Better Error Messages**: More informative error handling and user-facing messages
- **Empty Folder Detection**: Proper display of empty directories in navigation

### Development
- **Deployment Scripts**: Automated deployment and teardown scripts
  - `scripts/deploy.sh` for deployment to web server
  - `scripts/teardown.sh` for removal from web server
- **Security Testing**: Security testing script for validation
- **Pattern Matching Tests**: Tests for file pattern matching functionality
- **Development Router**: PHP built-in server router for local development

## [0.9.0] - 2025-10-08

### Added
- **Enhanced Directory Navigation**: Expandable directory tree with AJAX
  - Progressive indentation for nested folders
  - Loading states for directory expansion
  - Empty folder detection and display

- **Comprehensive File Type System**: Extensive file type detection and display
  - Support for 700+ file extensions
  - Proper file type capitalization
  - Emoji-based icon system with fallbacks
  - MIME type detection

### Changed
- **Requirements Documentation**: Updated and clarified project requirements
  - Added design principles (semantic HTML, minimal classes, minimal JavaScript)
  - Clarified sorting behavior (default alphabetical with type/size/date options)
  - Moved responsive design to core features
  - Added directory access control to features to be considered
  - Reorganized authentication features
  - Clarified installation structure

## [0.8.0] - Initial Release

### Added
- **Core Directory Listing**: Basic directory listing functionality
  - Sortable table (name, type, size, date)
  - File type icons
  - File size formatting
  - Modification date display
  - Breadcrumb navigation

- **Security Features**:
  - Basic rate limiting (30 requests/minute)
  - Bot detection via User-Agent filtering
  - .htaccess rules to hide infrastructure files
  - Input sanitization
  - Suspicious request detection
  - Security logging

- **Theming & Styling**:
  - Integration with jonplummer.com design patterns
  - Dark mode support
  - Mobile-first responsive design
  - Semantic HTML structure

- **Configuration System**: JSON-based configuration
  - Default configuration file
  - Easy customization
  - Security settings
  - Display preferences

- **Project Structure**: Clean, organized codebase
  - Modular PHP classes (App, DirectoryLister, Security)
  - Template system
  - Asset organization
  - Development tools
