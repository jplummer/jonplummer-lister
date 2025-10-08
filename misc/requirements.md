# The basic idea 

Lister is the easiest way to expose the contents of any web-accessible folder and subfolders for browsing and sharing. With a zero configuration drag-and-drop installation you'll be up and running in less than a minute. It's meant to be a custom from-scratch work-alike of https://www.directorylister.com/

## 1. Core Features

* Directory listing shows a sortable list of files for any web-accessible directory or subdirectory
* File type icons show file types at a glance
* Simple installation allows you to be up and running in less than a minute
* Sortable columns: default alphabetical by filename, with options to sort by type, size, or date
* Theme and styling integrate with https://jonplummer.com/, including dark mode support
* Easy file sharing via direct URL access
* Anti-abuse capabilities: exponential rate-limiting and bot scraping prevention
* Responsive design for mobile and desktop

## 2. Features to be considered

* Readme rendering allows exposing the contents of READMEs directly on the page
* Text preview allows you to see a preview of a text or markdown document
* Image preview allows you to see a preview of a selected image and page through images in the directory
* Hidden files: ability to hide files from listing while keeping them accessible via direct URL
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

## Available technology

It's meant to be installed in a web folder at Dreamhost, so the technologies we could use include
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

# Changelog

## 2025-10-08
- **Added design principles**: Specified preference for semantic HTML with minimal classes/IDs, no utility classes, and minimal JavaScript
- **Clarified sorting**: Made default alphabetical sorting explicit with type/size/date options
- **Moved responsive design**: Moved from "features to be considered" to core features
- **Added directory access control**: Moved to "features to be considered" section
- **Reorganized authentication**: Grouped upload/management features under clear hierarchy
- **Removed MCP from immediate scope**: Moved to future features