#!/bin/sh

if [ ! -d /var/www/vendor ]; then
    composer install --no-interaction --optimize-autoloader
fi

php artisan storage:link --force 2>/dev/null || true

php-fpm
