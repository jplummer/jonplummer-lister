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
# Try SSH key first, fallback to password if needed
sftp -o StrictHostKeyChecking=no -o PreferredAuthentications=publickey,password $HOST_USERNAME@$HOST_SERVER << EOF
cd $HOST_REMOTE_PATH
rm index.php
rm .htaccess
rm -r lister/
quit
EOF

echo "Teardown complete!"
echo "Lister has been removed from $HOST_DOMAIN"