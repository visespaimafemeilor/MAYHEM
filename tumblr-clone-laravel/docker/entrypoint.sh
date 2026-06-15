#!/bin/sh

cd /var/www

if [ ! -f .env ]; then
    cp .env.example.docker .env
fi

php artisan key:generate --force --quiet 2>/dev/null || true
php artisan storage:link --force --quiet 2>/dev/null || true

exec php-fpm
