#!/bin/bash
# Setup git hooks for version management
# Copies hooks from scripts/git-hooks/ to .git/hooks/

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
REPO_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
HOOKS_SOURCE="$REPO_ROOT/scripts/git-hooks"
HOOKS_TARGET="$REPO_ROOT/.git/hooks"

if [ ! -d "$HOOKS_SOURCE" ]; then
  echo "Error: Git hooks directory not found: $HOOKS_SOURCE"
  exit 1
fi

if [ ! -d "$HOOKS_TARGET" ]; then
  echo "Error: .git/hooks directory not found: $HOOKS_TARGET"
  echo "Are you in a git repository?"
  exit 1
fi

echo "Installing git hooks..."

for hook in "$HOOKS_SOURCE"/*; do
  if [ -f "$hook" ] && [ -x "$hook" ]; then
    hook_name=$(basename "$hook")
    target_hook="$HOOKS_TARGET/$hook_name"
    
    # Backup existing hook if it exists and is different
    if [ -f "$target_hook" ] && ! cmp -s "$hook" "$target_hook"; then
      echo "  Backing up existing $hook_name to ${hook_name}.backup"
      cp "$target_hook" "${target_hook}.backup"
    fi
    
    # Copy the hook
    cp "$hook" "$target_hook"
    chmod +x "$target_hook"
    echo "  ✓ Installed $hook_name"
  fi
done

echo "Git hooks installed successfully!"
