#!/bin/sh
set -e
cd /var/www/html

su www-data -s /bin/sh -c "php artisan config:cache && php artisan view:cache"

exec apache2-foreground
