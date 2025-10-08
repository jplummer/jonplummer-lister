# Lister Development Plan

## Phase 1: Core Foundation (MVP)
**Goal**: Basic directory listing with sorting and theming

### 1.1 Project Structure & Setup
- Create minimal PHP application structure
- Set up .htaccess for clean URLs and security
- Create configuration system for easy installation
- Implement basic error handling and logging

### 1.2 Directory Listing Engine
- Build core directory scanning functionality
- Implement file type detection and icon mapping
- Create file size formatting utilities
- Add modification date handling

### 1.3 User Interface
- Design semantic HTML structure with minimal classes
- Implement sortable table with minimal JavaScript
- Create file type icon system (CSS-based or icon font)
- Build navigation breadcrumbs using semantic HTML

### 1.4 Theming & Styling
- Analyze jonplummer.com design patterns
- Integrate existing CSS framework
- Implement dark mode support
- Ensure mobile-first responsive design

### 1.5 Security & Performance
- Implement basic rate limiting
- Add bot detection (User-Agent filtering)
- Create .htaccess rules to hide infrastructure files
- Add basic input sanitization

## Phase 2: Enhanced Features
**Goal**: Improved UX and additional functionality

### 2.1 File Management
- Implement hidden file functionality (.listerignore)
- Add README rendering for directories
- Create text file preview system
- Build image preview with navigation

### 2.2 User Experience
- Add loading states and transitions
- Implement keyboard navigation
- Create better error messages
- Add file sharing URL generation

### 2.3 Advanced Security
- Implement directory access control
- Add more sophisticated rate limiting
- Create IP-based blocking system
- Add request logging and monitoring

## Phase 3: Future Enhancements
**Goal**: Advanced features and integrations

### 3.1 Search & Discovery
- Implement client-side file search
- Add file filtering by type/size
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
- [ ] Lists files in any directory with proper sorting
- [ ] Integrates seamlessly with jonplummer.com design
- [ ] Works on mobile and desktop
- [ ] Handles common file types with appropriate icons
- [ ] Provides direct file sharing URLs
- [ ] Includes basic anti-abuse protection
- [ ] Installs in under 1 minute
- [ ] Hides infrastructure files from listing
