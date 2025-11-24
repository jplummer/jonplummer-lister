# The basic idea 

Lister exposes the contents of any web-accessible folder and subfolders for browsing and sharing. With minimal configuration, installation takes less than a minute. It's meant to be a custom from-scratch work-alike of https://www.directorylister.com/

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
* Directory access control: ability to restrict access to specific directories

## 3. Features to be considered later

* File search to locate files
* File hashes for download verification
* Multi-file download: select multiple files/folders and download as zip
* Authenticated file management:
  * Upload files from computer to selected folder
  * Drag-and-drop file upload and rearrangement
  * Delete files or folders
  * Authentication via SSH key or user/pass (sshpass)
* MCP (Model Context Protocol) integration for AI agents
* Enhanced security admin dashboard features:
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

