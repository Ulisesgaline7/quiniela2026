# ── Stage 1: Build assets ──────────────────────────────────────────
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
COPY resources/css resources/css
COPY resources/js resources/js
RUN npm ci --prefer-offline && npm run build

# ── Stage 2: PHP app ───────────────────────────────────────────────
FROM php:8.3-cli-alpine

RUN apk add --no-cache \
    sqlite sqlite-dev libpng-dev libxml2-dev \
    oniguruma-dev zip unzip curl

RUN docker-php-ext-install \
    pdo pdo_sqlite mbstring xml ctype fileinfo bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# PHP deps (cached layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy built assets from node stage
COPY --from=node-builder /app/public/build public/build

# Copy full application
COPY . .

# Post-install
RUN composer run-script post-autoload-dump 2>/dev/null || true

# Storage setup
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

EXPOSE 8080

CMD ["/bin/sh", "-c", "\
    php artisan migrate --force && \
    php artisan db:seed --force 2>/dev/null; \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=8080"]
