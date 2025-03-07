#!/bin/bash

# Navigate to the repository
cd /var/www/html/soccer || exit

# Pull the latest changes
git fetch origin add-ghant
git reset --hard origin/add-ghant

# Set correct permissions for files
chown -R www-data:www-data /var/www/html/soccer
chmod -R 755 /var/www/html/soccer

# Restart web server (if necessary)
systemctl restart apache2  # Use 'nginx' if running Nginx

echo "Deployment completed at $(date)" >> /var/www/html/soccer/deploy.log
