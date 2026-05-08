#!/bin/bash
set -e

echo "==> Setting up directories..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache
mkdir -p database

# Ensure SQLite DB exists
touch database/database.sqlite

# Fix permissions
chmod -R 775 storage bootstrap/cache

echo "==> Running migrations..."
php artisan migrate --force

# Only seed if users table is empty (first deploy)
USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
  echo "==> Seeding database (first deploy)..."
  php artisan db:seed --force
else
  echo "==> Database already seeded ($USER_COUNT users), skipping."
fi

echo "==> Caching..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
