#!/bin/bash
set -e

echo "==> Setting up storage..."
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Ensure SQLite DB exists
touch database/database.sqlite

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding database (skip if already seeded)..."
php artisan db:seed --force 2>/dev/null || true

echo "==> Caching config..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
