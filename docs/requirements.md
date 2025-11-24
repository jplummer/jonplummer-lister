# The basic idea 

Lister is the easiest way to expose the contents of any web-accessible folder and subfolders for browsing and sharing. With a zero configuration drag-and-drop installation you'll be up and running in less than a minute. It's meant to be a custom from-scratch work-alike of https://www.directorylister.com/

## 1. Core Features

* Directory listing shows a sortable list of files for any web-accessible directory or subdirectory
* File type icons show file types at a glance
* Simple drag-and-drop installation allows you to be up and running in less than a minute and is easy to remove
* Sortable columns: default alphabetical by filename, with options to sort by type, size, or date
* Theme and styling integrate with https://jonplummer.com/, including dark mode support
* Easy file sharing via direct URL access
* Anti-abuse capabilities: exponential rate-limiting and bot scraping prevention
* Responsive design for mobile and desktop

## 2. Features to be considered

* Readme rendering allows exposing the contents of READMEs directly on the page
* Text preview allows you to see a preview of a text or markdown document
* Image preview allows you to see a preview of a selected image and page through images in the directory
* File hiding: configurable options to hide dotfiles, sensitive files, and OS-specific cruft from directory listings while keeping them accessible via direct URL
* Directory access control: ability to restrict access to specific directories

## 3. Features to be considered later

* File search helps you locate the files you need quickly and efficiently
* File hashes instill confidence when downloading files through verification
* Multi-file download: select multiple files/folders and download as zip
* Authenticated file management:
  * Upload files from computer to selected folder
  * Drag-and-drop file upload and rearrangement
  * Delete files or folders
  * Authentication via SSH key or user/pass (sshpass)
* MCP (Model Context Protocol) integration for AI agents
* Security admin dashboard provides visibility into security incidents and system health:
  * **Security concerns to prevent, trap, and/or log:**
    * Bot scraping and automated access (curl, wget, scrapers, crawlers, spiders)
    * Rate limit violations (excessive requests per minute)
    * Directory traversal attempts (path manipulation with `..` or `//`)
    * Suspicious query parameters (cmd, exec, system, eval, shell, passwd, shadow)
    * Suspicious file extension requests (.php, .asp, .jsp, .py, .sh, .exe in URLs)
    * Missing or suspicious user agents
    * IP-based blocking and access attempts from blocked IPs
  * **Dashboard should display:**
    * Total security incidents count
    * Recent security incidents with details (type, IP, timestamp, user agent, request URI, referer)
    * Current security configuration settings
    * Ability to view blocked IPs and their status
    * Filtering and search capabilities for incident logs
    * Export capabilities for security logs
  * **Access control:**
    * Password-protected access
    * Session management to avoid repeated password entry
    * Secure authentication mechanism

## 4. Nice to have features

* Shared design system package to coordinate theme data with jonplummer-11ty and other projects

## Available technology

It's meant to be installed in a web folder on any PHP-compatible hosting provider, so the technologies we could use include
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

# Requirements Document Changelog

This changelog tracks changes to the requirements specification document itself. For package release history, see [CHANGELOG.md](CHANGELOG.md).

## 2025-11-23
- **Added security admin dashboard specification**: Documented security concerns to prevent, trap, and log (bot scraping, rate limiting, directory traversal, suspicious parameters, etc.), dashboard display requirements (incident logs, statistics, configuration), and access control requirements (password protection, session management).

## 2025-10-10
- **Added file hiding configuration**: Documented configurable options to hide dotfiles, sensitive files, OS-specific cruft, and application files from directory listings. All four categories are enabled by default and use pattern matching for flexible file filtering. Application files include the `/lister` directory, `index.php`, `api.php`, and other app-related files.

## 2025-10-08
- **Added design principles**: Specified preference for semantic HTML with minimal classes/IDs, no utility classes, and minimal JavaScript
- **Clarified sorting**: Made default alphabetical sorting explicit with type/size/date options
- **Moved responsive design**: Moved from "features to be considered" to core features
- **Added directory access control**: Moved to "features to be considered" section
- **Reorganized authentication**: Grouped upload/management features under clear hierarchy
- **Removed MCP from immediate scope**: Moved to future features
- **Clarified installation structure**: Installation requires `index.php` and `.htaccess` at root level for directory listing functionality, with application components organized in `/lister` subdirectory for clean deployment and maintenance