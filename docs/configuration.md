# Configuration Guide

## Environment Setup

Create a `.env` file in the project root with your hosting details:

```bash
# Hosting Configuration
HOST_SERVER=your-server.com
HOST_USERNAME=your-username
HOST_PASSWORD=your-password
HOST_REMOTE_PATH=/path/to/your/website/
HOST_DOMAIN=your-domain.com
```

## Supported Hosting Providers

Lister works with any hosting provider that supports:
- PHP 8.x
- Apache with mod_php
- SFTP access
- .htaccess support

### Examples

**Dreamhost:**
```bash
HOST_SERVER=iad1-shared-e1-29.dreamhost.com
HOST_USERNAME=your-username
HOST_PASSWORD=your-password
HOST_REMOTE_PATH=/home/your-username/your-domain.com/
HOST_DOMAIN=your-domain.com
```

**cPanel/WHM:**
```bash
HOST_SERVER=your-domain.com
HOST_USERNAME=your-cpanel-username
HOST_PASSWORD=your-cpanel-password
HOST_REMOTE_PATH=/public_html/
HOST_DOMAIN=your-domain.com
```

**DigitalOcean/AWS:**
```bash
HOST_SERVER=your-server-ip
HOST_USERNAME=root
HOST_PASSWORD=your-password
HOST_REMOTE_PATH=/var/www/html/
HOST_DOMAIN=your-domain.com
```

## Security Notes

- Never commit your `.env` file to version control
- Use strong passwords for hosting accounts
- Consider using SSH keys instead of passwords when possible
- Regularly update your hosting credentials

## Troubleshooting

**Connection Issues:**
- Verify server details in `.env`
- Check SFTP port (usually 22)
- Ensure hosting provider allows SFTP access

**Permission Issues:**
- Verify remote path exists
- Check file permissions on server
- Ensure user has write access to target directory
