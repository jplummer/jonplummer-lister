#!/bin/bash
# Setup git hooks for the project

SCRIPT_DIR="$(dirname "$0")"
REPO_ROOT="$(dirname "$SCRIPT_DIR")"
GIT_HOOKS_DIR="$REPO_ROOT/.git/hooks"

# Make pre-commit hook executable
chmod +x "$SCRIPT_DIR/pre-commit-changelog.sh"

# Create symlink to pre-commit hook
ln -sf "../../scripts/pre-commit-changelog.sh" "$GIT_HOOKS_DIR/pre-commit"

echo "✓ Git hooks installed"
echo "  Pre-commit hook will automatically update CHANGELOG.md"

