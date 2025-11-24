#!/bin/bash
# Update CHANGELOG.md with new version entry
# Usage: update_changelog.sh <version> <type> [entry1] [entry2] ...

VERSION=$1
TYPE=$2  # patch, minor, major
shift 2
ENTRIES=("$@")

if [ -z "$VERSION" ] || [ -z "$TYPE" ]; then
  echo "Usage: $0 <version> <type> [entry1] [entry2] ..."
  exit 1
fi

CHANGELOG_FILE="$(dirname "$0")/../docs/CHANGELOG.md"

if [ ! -f "$CHANGELOG_FILE" ]; then
  echo "Error: CHANGELOG.md not found at $CHANGELOG_FILE"
  exit 1
fi

# Get current date
DATE=$(date +%Y-%m-%d)

# Determine section based on type
case $TYPE in
  major|minor)
    SECTION="### Added"
    ;;
  patch)
    SECTION="### Fixed"
    ;;
  *)
    SECTION="### Changed"
    ;;
esac

# Create temporary file with new version entry
TEMP_FILE=$(mktemp)

# Read the changelog and insert new version after [Unreleased]
INSERTED=false
while IFS= read -r line; do
  echo "$line" >> "$TEMP_FILE"
  
  # Insert new version entry after [Unreleased] section
  if [[ "$line" =~ ^##\ \[Unreleased\] ]] && [ "$INSERTED" = false ]; then
    echo "" >> "$TEMP_FILE"
    echo "## [$VERSION] - $DATE" >> "$TEMP_FILE"
    
    if [ ${#ENTRIES[@]} -gt 0 ]; then
      echo "" >> "$TEMP_FILE"
      echo "$SECTION" >> "$TEMP_FILE"
      for entry in "${ENTRIES[@]}"; do
        echo "- $entry" >> "$TEMP_FILE"
      done
    fi
    
    INSERTED=true
  fi
done < "$CHANGELOG_FILE"

# Replace original file
mv "$TEMP_FILE" "$CHANGELOG_FILE"

echo "✓ Updated CHANGELOG.md with version $VERSION"
