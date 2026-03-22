# Accessibility audit — Lister listing UI

**Date**: 2026-03-21  
**Scope**: `lister/templates/index.php` listing page, `lister/assets/lister.css`, inline behaviors (keyboard, modal preview, expandable folders).  
**Method**: Static review against WCAG 2.2 AA-oriented patterns; no formal screen-reader matrix or automated axe run in CI (recommended as follow-up).

## Summary

The listing page uses semantic regions, a single page `<h1>`, table structure with a caption, sort state on headers, `aria-expanded` on folder toggles, a skip link, and keyboard support documented in `requirements.md`. Residual gaps are called out below.

## Changes made (this pass)

* **Skip link** to `#lister-main` (visible on focus).
* **One `h1` per page**: site name in the global header is a paragraph with class `site-title` (visual styling unchanged); article title remains `<h1>`.
* **Table `<caption>`** (visually hidden) naming the directory table.
* **Sort**: `aria-current="true"` on the active column’s sort link (with existing `aria-sort` on `<th>`).
* **Folder toggles**: `aria-expanded` synced on expand/collapse (server-rendered and AJAX rows); decorative caret/folder glyphs `aria-hidden="true"`.
* **Loading**: Visually hidden text in the expanding indicator live region (“Loading folder contents.”) when active.

## Residual risks / follow-ups

* **Screen readers + `tr` focus**: Roving `tabindex` on `<tr>` works in several browsers but support varies; if issues appear, consider focusing a cell or an inner proxy element while keeping the same keymap.
* **Modal PDF**: When focus moves into an `<iframe>`, parent shortcuts may not apply; documented in requirements.
* **Contrast**: Palette follows DR10 / `light-dark()`; run a formal contrast check if theme tokens change.
* **Automated testing**: Add optional `axe-core` or Lighthouse CI on a built/staged URL for regressions.
* **README Markdown in page**: Rendered HTML should stay safe (Parsedown safe mode); heading levels inside readme could nest under page outline—monitor if long READMEs ship.

## Plan

Tracked as completed in `plan.md` (Phase 2 → archive **2.12**).
