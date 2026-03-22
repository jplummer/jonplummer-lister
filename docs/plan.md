# Lister Development Plan

## Current Status: WORKS GOOD ✅
- **Deployed**: misc.jonplummer.com
- **Phase 1**: Complete (MVP + additional features)
- **Phase 2**: In progress — modal previews shipped; remaining: keyboard/a11y, error pages, shipping/docs tasks, optional polish
- **Phase 3**: Not started (search, bulk file operations)
- **Phase 4**: Someday maybe — commercialization / multi-user (see [Phase 4](#phase-4-commercialization-and-multi-user))
- **Next**: Keyboard navigation, accessibility audit

## Phase 1: Core Foundation (MVP) – Basic directory listing with sorting and theming – Complete

## Phase 2: Enhanced Features – Improved UX and additional functionality

**Context**: Primary use is **personal, convenient file access**, not high-sensitivity hosting. **Threat model**: automated abuse (bots, scrapers) is the main concern—not fine-grained control over curious humans. Multi-tenant access, auth-as-product, and similar work are an optional **Phase 4** and do not drive the current deployment.

### Styling completeness (Phase 2.1) – Complete

### Product: interaction & quality
- [ ] Implement keyboard navigation
- [ ] Accessibility audit

### Abuse & automation
- [ ] Block known and detected malicious bots and crawlers

### Error experiences
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

### Operations: traffic, links, and delete safety
**Goal**: Use server-side evidence to avoid breaking external bookmarks, embeds, or automations that still request file URLs; discover inbound links where possible.

- [ ] Document how to use **web server access logs** to see whether specific listed paths (or URL prefixes) received requests over a chosen time window; include host-specific pointers where useful (e.g. DreamHost log location and format)
- [ ] Document a **safe-delete workflow** (e.g. check logs → optional quarantine period → delete) and what logs do *not* prove (non-HTTP access, clients that never hit the server)
- [ ] Document **Referer** in logs: when it appears, what it suggests about off-site links, and why it is incomplete (direct navigation, apps, stripped referrers); complement with **Search Console** (or similar) and occasional URL search for link discovery

### Shipping & installation
- [ ] Ensure deploy script and other development conveniences are not part of delivered package
- [ ] Verify drag-and-drop installation works as promised
- [ ] Check whether directory listing capability is required for the tool to work
- [ ] **Low priority**: Investigate OpenSSH “post-quantum key exchange” warning when running `deploy.sh` / SFTP (what client and DreamHost `sshd` support; optional hardening, not urgent for this threat model) — https://openssh.com/pq.html
- [ ] **Optional**: Refine drag-and-drop install experience by **install surface**, not raw file count: README states an unambiguous upload target; shipped tree keeps top-level items purposeful; release zip omits dev-only files. Treat merging PHP for fewer files as low value unless it also reduces confusion.

### Visual polish (incremental / optional)
- [ ] Styling adjustments to better match jonplummer.com

### Customization & integration
- [ ] Make it easier to integrate Lister with custom website styling
  - [ ] Document CSS custom properties and how to override them
  - [ ] Create minimal/stripped CSS version for custom styling
  - [ ] Provide clear separation between core functionality and visual styling
  - [ ] Add configuration options for header/footer customization
  - [ ] Document template structure for modification
  - [ ] Consider providing CSS class hooks for external styling

## Phase 3: Future Enhancements
**Goal**: Advanced features that still fit **personal** browsing and sharing (no multi-user product assumptions).

### 3.1 Search & Discovery
- Implement client-side file search
- Add file filtering by type/size (nah)
- Create advanced sorting options

### 3.2 File Operations
- Build multi-file selection interface
- Implement zip download functionality
- Add file hash generation and display

### 3.3 Other
- MCP (Model Context Protocol) integration for AI agents

### 3.4 Log analysis helpers (optional)
- [ ] Add documented **grep/awk (or small script) recipes** for common log formats to aggregate hits by path or list distinct referrers for a path—stays optional if raw log access is enough

## Phase 4: Commercialization and multi-user
**Goal**: Product-grade hosting, tenants, or sensitive data—**out of scope** for the current personal deployment. Revisit only if Lister is sold, multi-tenant, or holds data you treat as confidential.

**Related requirements**: [Commercialization and multi-user (deferred)](requirements.md#5-commercialization-and-multi-user-deferred) in `requirements.md`.

### Access and hardening
- [ ] Implement directory access control
- [ ] OWASP secure headers — https://owasp.org/www-project-secure-headers/index.html#div-bestpractices

### Authentication and file management
- [ ] Design user authentication framework
- [ ] Implement SSH key-based auth
- [ ] Create file upload interface
- [ ] Build drag-and-drop functionality (upload / rearrangement; align with requirements when scoped)

### Security admin (product-depth)
- [ ] Enhanced security admin dashboard (beyond current basics), including:
  - [ ] Incident totals, detailed recent incidents, config summary, blocked-IP views
  - [ ] Filtering, search, and export for security logs
  - [ ] Password-protected access to admin features where applicable
  - [ ] Session management to avoid repeated password entry
  - [ ] Secure authentication mechanism for admin

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

---

## Archive: completed work

### Phase 1: Core Foundation (MVP)

#### 1.1 Project Structure & Setup
- [x] Create minimal PHP application structure
- [x] Set up .htaccess for clean URLs and security
- [x] Create configuration system for easy installation
- [x] Implement basic error handling and logging

#### 1.2 Directory Listing Engine
- [x] Build core directory scanning functionality
- [x] Implement file type detection and icon mapping
- [x] Create file size formatting utilities
- [x] Add modification date handling

#### 1.3 User Interface
- [x] Design semantic HTML structure with minimal classes
- [x] Implement sortable table with minimal JavaScript
- [x] Create file type icon system (CSS-based or icon font)
- [x] Build navigation breadcrumbs using semantic HTML

#### 1.4 Theming & Styling
- [x] Analyze jonplummer.com design patterns
- [x] Integrate existing CSS framework
- [x] Implement dark mode support
- [x] Ensure mobile-first responsive design

#### 1.5 Security & Performance
- [x] Implement basic rate limiting
- [x] Add bot detection (User-Agent filtering)
- [x] Create .htaccess rules to hide infrastructure files
- [x] Add basic input sanitization
- [x] Add security logging and admin panel
- [x] Implement suspicious request detection

#### 1.6 Enhanced Directory Navigation
- [x] Implement expandable directory navigation with AJAX
- [x] Add empty folder detection and display
- [x] Create progressive indentation for nested folders
- [x] Add loading states for directory expansion

#### 1.7 File Type System
- [x] Implement comprehensive file type detection (700+ extensions)
- [x] Add proper file type capitalization
- [x] Create file type icon system (Material Symbols Outlined via Google Fonts)
- [x] Add MIME type detection

#### 1.8 Development Tools
- [x] Create deployment scripts (deploy.sh, teardown.sh)
- [x] Add security testing script
- [x] Implement pattern matching tests
- [x] Create development router for PHP built-in server

### Phase 2: completed items

#### 2.1 Styling Completeness
- [x] HEAD matter, including
    - [x] Favicons from jonplummer.com
- [x] Design system from jonplummer.com
- [x] Nav to jonplummer.com

#### 2.2 File Management
- [x] Implement hidden file functionality (.listerignore)

#### 2.3 User Experience
- [x] Add loading states and transitions
- [x] Create better error messages
- [x] Add file sharing URL generation (files accessible via direct URL)

#### 2.4 Advanced Security
- [x] Add more sophisticated rate limiting
- [x] Create IP-based blocking system
- [x] Add request logging and monitoring
- [x] Add security admin dashboard (basic implementation)

#### 2.5 Polish & Refinement
- [x] Fix caret shape in directory navigation

#### 2.6 Directory table & README presentation
- [x] README rendering for directories (Markdown via Parsedown safe mode, `.txt` escaped; `display.readme_preview` in config; bordered panel with grey meta line: filename · modified time)
- [x] Name column layout: flex row, 16px disclosure gutter for files and empty folders, caret/icon/name aligned to first line when names wrap
- [x] Metadata columns: size right-aligned, muted text on size/modified/type, extra horizontal padding between columns
- [x] Empty folder row height aligned with other rows (no extra wrapper padding)

#### 2.7 Modal file previews
- [x] **Modal previews** (`<dialog>`): text / markdown via `lister/preview.php` (Parsedown safe / escaped plain / `<pre>` for other text kinds), PDF in `<iframe>`, images with `<img>` and ← / → among image rows in the current table (including expanded rows); `data-preview` + `preview_kind`; `display.preview_text_max_bytes`
- [x] Primary click opens modal; modified clicks (e.g. ⌘/Ctrl) keep normal navigation

#### 2.8 Install & packaging (done)
- [x] Preflight install error UI inlined in root `index.php` (removed separate `install_error.php` from deploy surface)
- [x] Requirements + plan: install packaging goals emphasize upload target and runtime-only artifacts over raw file count

#### 2.9 Favicon verification (checklist completed in repo)
- [x] Documented steps: `scripts/diagnose_favicon.php`, Network tab 200s, Safari/Chrome tab + iOS Add to Home Screen; optional later: `site.webmanifest` + maskable icons for Android shortcuts

### Success criteria (met)
- [x] Lists files in any directory with proper sorting
- [x] Integrates seamlessly with jonplummer.com design
- [x] Works on mobile and desktop
- [x] Handles common file types with appropriate icons
- [x] Provides direct file sharing URLs
- [x] Includes basic anti-abuse protection
- [x] Installs in under 1 minute
- [x] Hides infrastructure files from listing
- [x] Works equally well when visiting subfolder URLs directly in browser
