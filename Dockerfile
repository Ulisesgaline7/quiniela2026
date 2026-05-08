FROM php:8.3-cli-alpine

# System deps
RUN apk add --no-cache \
    git curl libpng-dev libxml2-dev \
    zip unzip nodejs npm sqlite sqlite-dev \
    oniguruma-dev icu-dev

# PHP extensions
RUN docker-php-ext-install \
    pdo pdo_sqlite mbstring xml ctype \
    fileinfo bcmath intl

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP deps (cached layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Install Node deps & build assets
COPY package.json package-lock.json vite.config.js ./
COPY resources/css resources/css
COPY resources/js resources/js
RUN npm ci && npm run build

# Copy full app
COPY . .

# Post-install scripts
RUN composer run-script post-autoload-dump 2>/dev/null || true

# Storage & permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs bootstrap/cache database \
    && touch database/database.sqlite \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 8080

CMD ["/bin/sh", "-c", "\
    php artisan migrate --force && \
    php artisan db:seed --force 2>/dev/null; \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
