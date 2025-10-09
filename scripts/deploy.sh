#!/bin/bash
# Lister Deployment Script
# Uploads files to web server root directory

# Load environment variables from project root
source "$(dirname "$0")/../.env"

echo "Deploying Lister to web server..."
echo "Host: $HOST_SERVER"
echo "User: $HOST_USERNAME"
echo "Path: $HOST_REMOTE_PATH"

# Upload files to root directory
# Try SSH key first, fallback to password if needed
sftp -o StrictHostKeyChecking=no -o PreferredAuthentications=publickey,password $HOST_USERNAME@$HOST_SERVER << EOF
cd $HOST_REMOTE_PATH
put index.php
put .htaccess
put -r lister/
quit
EOF

echo "Deployment complete!"
echo "Visit: https://$HOST_DOMAIN/"
