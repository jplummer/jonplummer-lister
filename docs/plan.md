# Lister Development Plan

## Current Status: PRODUCTION READY ✅
- **Deployed**: misc.jonplummer.com
- **Phase 1**: Complete (MVP + additional features)
- **Phase 2**: Partially complete (security, UX improvements)
- **Next**: File previews, keyboard navigation, README rendering

## Phase 1: Core Foundation (MVP)
**Goal**: Basic directory listing with sorting and theming

### 1.1 Project Structure & Setup
- [x] Create minimal PHP application structure
- [x] Set up .htaccess for clean URLs and security
- [x] Create configuration system for easy installation
- [x] Implement basic error handling and logging

### 1.2 Directory Listing Engine
- [x] Build core directory scanning functionality
- [x] Implement file type detection and icon mapping
- [x] Create file size formatting utilities
- [x] Add modification date handling

### 1.3 User Interface
- [x] Design semantic HTML structure with minimal classes
- [x] Implement sortable table with minimal JavaScript
- [x] Create file type icon system (CSS-based or icon font)
- [x] Build navigation breadcrumbs using semantic HTML

### 1.4 Theming & Styling
- [x] Analyze jonplummer.com design patterns
- [x] Integrate existing CSS framework
- [x] Implement dark mode support
- [x] Ensure mobile-first responsive design

### 1.5 Security & Performance
- [x] Implement basic rate limiting
- [x] Add bot detection (User-Agent filtering)
- [x] Create .htaccess rules to hide infrastructure files
- [x] Add basic input sanitization
- [x] Add security logging and admin panel
- [x] Implement suspicious request detection

### 1.6 Enhanced Directory Navigation
- [x] Implement expandable directory navigation with AJAX
- [x] Add empty folder detection and display
- [x] Create progressive indentation for nested folders
- [x] Add loading states for directory expansion

### 1.7 File Type System
- [x] Implement comprehensive file type detection (700+ extensions)
- [x] Add proper file type capitalization
- [x] Create file type icon system with emoji fallbacks
- [x] Add MIME type detection

### 1.8 Development Tools
- [x] Create deployment scripts (deploy.sh, teardown.sh)
- [x] Add security testing script
- [x] Implement pattern matching tests
- [x] Create development router for PHP built-in server

## Phase 2: Enhanced Features
**Goal**: Improved UX and additional functionality

### 2.1 Styling Completeness
- [x] HEAD matter, including
    - [x] Favicons from jonplummer.com
- [x] Design system from jonplummer.com
- [x] Nav to jonplummer.com

### 2.2 File Management
- [x] Implement hidden file functionality (.listerignore)
- [ ] Add README rendering for directories
- [ ] Create text file preview system
- [ ] Build image preview with navigation

### 2.3 User Experience
- [x] Add loading states and transitions
- [ ] Implement keyboard navigation
- [x] Create better error messages
- [x] Add file sharing URL generation (files accessible via direct URL)
- [ ] Accessibility audit

### 2.4 Advanced Security
- [ ] Implement directory access control
- [x] Add more sophisticated rate limiting
- [x] Create IP-based blocking system
- [x] Add request logging and monitoring
- [x] Add security admin dashboard (basic implementation)
- [ ] Block known and detected malicious bots and crawlers 
- [ ] https://owasp.org/www-project-secure-headers/index.html#div-bestpractices

### 2.5 Polish & Refinement
- [ ] Fix caret shape in directory navigation
- [ ] Diagnose favicon issue (diagnostic script exists)
- [ ] Styling adjustments to better match jonplummer.com
- [ ] Consider file type icons (optional)
- [ ] Ensure deploy script and other development conveniences are not part of delivered package
- [ ] Verify drag-and-drop installation works as promised
- [ ] Reduce file count (optional)
- [ ] Check whether directory listing capability is required for the tool to work

### 2.6 Error Experiences
- [ ] Create 404 error page for non-existent files/directories
  - [ ] Match jonplummer.com design system
  - [ ] Include navigation back to parent directory or root
  - [ ] Show helpful message with requested path
  - [ ] Provide search suggestions if applicable
- [ ] Enhance 500 error page for server errors
  - [ ] Match jonplummer.com design system
  - [ ] Replace basic error handler in index.php
  - [ ] Include navigation back to root
  - [ ] Show user-friendly message (hide technical details in production)
  - [ ] Log detailed error information for debugging
- [ ] Ensure error pages work in both development and production environments
- [ ] Test error pages with various edge cases (malformed URLs, missing directories, etc.)

## Phase 3: Future Enhancements
**Goal**: Advanced features and integrations

### 3.1 Search & Discovery
- Implement client-side file search
- Add file filtering by type/size (nah)
- Create advanced sorting options

### 3.2 File Operations
- Build multi-file selection interface
- Implement zip download functionality
- Add file hash generation and display

### 3.3 Authentication System
- Design user authentication framework
- Implement SSH key-based auth
- Create file upload interface
- Build drag-and-drop functionality

## Technical Architecture

### Technology Stack
- **Backend**: PHP 8.x (Dreamhost compatible)
- **Frontend**: Vanilla JavaScript, CSS3, HTML5
- **Styling**: Custom CSS with jonplummer.com integration
- **Configuration**: JSON-based config files
- **Security**: .htaccess rules, PHP input validation

### Design Principles
- **HTML**: Semantic HTML with minimal classes/IDs, no utility classes
- **CSS**: Clean, elegant, human-readable styles with custom properties
- **JavaScript**: Minimal and purposeful - only what's truly necessary
- **Accessibility**: Leverage semantic HTML behavior over ARIA attributes
- **Mobile-first**: Responsive design starting from mobile

### File Structure
```
lister/
├── index.php              # Main application entry point
├── config/
│   ├── default.json       # Default configuration
│   └── .htaccess          # Security rules
├── assets/
│   ├── css/
│   │   ├── lister.css     # Core styles
│   │   └── themes/        # Theme variations
│   ├── js/
│   │   └── lister.js      # Core functionality
│   └── icons/             # File type icons
├── includes/
│   ├── DirectoryLister.php # Core listing class
│   ├── Security.php       # Security utilities
│   └── Theme.php          # Theme management
└── .listerignore          # Hidden files config
```

### Installation Process
1. Upload lister files to target directory
2. Copy .htaccess rules to hide infrastructure
3. Optionally customize config.json
4. Directory is immediately browsable

## Success Criteria
- [x] Lists files in any directory with proper sorting
- [x] Integrates seamlessly with jonplummer.com design
- [x] Works on mobile and desktop
- [x] Handles common file types with appropriate icons
- [x] Provides direct file sharing URLs
- [x] Includes basic anti-abuse protection
- [x] Installs in under 1 minute
- [x] Hides infrastructure files from listing
