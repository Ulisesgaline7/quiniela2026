#!/bin/bash
# Script de actualización rápida (después del primer deploy)
set -e

APP_DIR="/var/www/quiniela2026"
PHP_VER="8.3"

echo "▶ Actualizando Quiniela 2026..."
cd "$APP_DIR"

git pull origin main

composer install --no-dev --optimize-autoloader --no-interaction
npm ci && npm run build

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

chown -R www-data:www-data "$APP_DIR"
chmod -R 775 storage bootstrap/cache database

systemctl reload nginx
systemctl restart php${PHP_VER}-fpm

echo "✅ Actualización completada"
