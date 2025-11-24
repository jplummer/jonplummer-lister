#!/bin/bash
# Lister Teardown Script
# Removes Lister files from web server

# Load environment variables from project root
source "$(dirname "$0")/../.env"

echo "Removing Lister from web server..."
echo "Host: $HOST_SERVER"
echo "User: $HOST_USERNAME"
echo "Path: $HOST_REMOTE_PATH"

# Confirm before proceeding
read -p "Are you sure you want to remove Lister from the remote server? This will delete all Lister files. (y/N): " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Teardown cancelled."
    exit 1
fi

# Remove files from server
# Use sshpass if available and password is set, otherwise try SSH key or prompt
if command -v sshpass >/dev/null 2>&1 && [ -n "$HOST_PASSWORD" ]; then
  # Use sshpass to provide password non-interactively
  sshpass -p "$HOST_PASSWORD" sftp -o StrictHostKeyChecking=no -o PreferredAuthentications=password,publickey $HOST_USERNAME@$HOST_SERVER << EOF
cd $HOST_REMOTE_PATH
rm index.php
rm .htaccess
rm -r lister/
quit
EOF
else
  # Try SSH key first, fallback to password prompt if needed
  if [ -n "$HOST_PASSWORD" ] && ! command -v sshpass >/dev/null 2>&1; then
    echo "Note: HOST_PASSWORD is set but sshpass is not installed."
    echo "Install sshpass to use password authentication automatically:"
    echo "  macOS: brew install hudochenkov/sshpass/sshpass"
    echo "  Linux: apt-get install sshpass (or yum install sshpass)"
    echo ""
    echo "Falling back to interactive password prompt or SSH key..."
    echo ""
  fi
  sftp -o StrictHostKeyChecking=no -o PreferredAuthentications=publickey,password $HOST_USERNAME@$HOST_SERVER << EOF
cd $HOST_REMOTE_PATH
rm index.php
rm .htaccess
rm -r lister/
quit
EOF
fi

echo "Teardown complete!"
echo "Lister has been removed from $HOST_DOMAIN"