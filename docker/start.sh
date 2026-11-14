#!/bin/sh
set -e

# Render gives the port in $PORT (default 10000)
PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true
php artisan config:cache
php artisan route:cache || true
php artisan view:cache || true

# Run migrations on every deploy
php artisan migrate --force

exec apache2-foreground