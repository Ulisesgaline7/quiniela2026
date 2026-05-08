#!/bin/bash
# ═══════════════════════════════════════════════════════════════
#  Quiniela FIFA World Cup 2026 — VPS Install Script
#  Tested on: Ubuntu 22.04 / Debian 12
#  Run as: root
# ═══════════════════════════════════════════════════════════════
set -e

APP_DIR="/var/www/quiniela2026"
DOMAIN="${1:-187.124.94.233}"  # Pass domain as arg or use IP
PHP_VER="8.3"

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║  QUINIELA 2026 — Instalación automática  ║"
echo "╚══════════════════════════════════════════╝"
echo ""

# ── 1. Update system ──────────────────────────────────────────
echo "▶ Actualizando sistema..."
apt-get update -qq
apt-get upgrade -y -qq

# ── 2. Install Nginx ──────────────────────────────────────────
echo "▶ Instalando Nginx..."
apt-get install -y -qq nginx

# ── 3. Install PHP 8.3 ────────────────────────────────────────
echo "▶ Instalando PHP ${PHP_VER}..."
apt-get install -y -qq software-properties-common
add-apt-repository -y ppa:ondrej/php 2>/dev/null || true
apt-get update -qq
apt-get install -y -qq \
    php${PHP_VER}-fpm \
    php${PHP_VER}-cli \
    php${PHP_VER}-sqlite3 \
    php${PHP_VER}-mbstring \
    php${PHP_VER}-xml \
    php${PHP_VER}-curl \
    php${PHP_VER}-zip \
    php${PHP_VER}-bcmath \
    php${PHP_VER}-gd \
    php${PHP_VER}-intl \
    php${PHP_VER}-tokenizer \
    php${PHP_VER}-ctype \
    php${PHP_VER}-fileinfo

# ── 4. Install Composer ───────────────────────────────────────
echo "▶ Instalando Composer..."
if ! command -v composer &>/dev/null; then
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
    chmod +x /usr/local/bin/composer
fi

# ── 5. Install Node.js 20 ─────────────────────────────────────
echo "▶ Instalando Node.js 20..."
if ! command -v node &>/dev/null; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y -qq nodejs
fi

# ── 6. Install Git ────────────────────────────────────────────
apt-get install -y -qq git sqlite3

# ── 7. Clone / update repo ────────────────────────────────────
echo "▶ Clonando repositorio..."
if [ -d "$APP_DIR" ]; then
    echo "  Actualizando repo existente..."
    cd "$APP_DIR"
    git pull origin main
else
    git clone https://github.com/Ulisesgaline7/quiniela2026.git "$APP_DIR"
    cd "$APP_DIR"
fi

# ── 8. PHP dependencies ───────────────────────────────────────
echo "▶ Instalando dependencias PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# ── 9. Node dependencies & build ─────────────────────────────
echo "▶ Compilando assets..."
npm ci
npm run build

# ── 10. Environment setup ─────────────────────────────────────
echo "▶ Configurando entorno..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Set production values
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|APP_URL=.*|APP_URL=http://${DOMAIN}|" .env
sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=sqlite|" .env
sed -i "s|SESSION_DRIVER=.*|SESSION_DRIVER=file|" .env
sed -i "s|CACHE_STORE=.*|CACHE_STORE=file|" .env
sed -i "s|LOG_CHANNEL=.*|LOG_CHANNEL=stack|" .env

# Generate app key if not set
if grep -q "APP_KEY=$" .env || grep -q "APP_KEY=base64:$" .env; then
    php artisan key:generate --force
fi

# ── 11. Database setup ────────────────────────────────────────
echo "▶ Configurando base de datos..."
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force

# ── 12. Permissions ───────────────────────────────────────────
echo "▶ Configurando permisos..."
chown -R www-data:www-data "$APP_DIR"
chmod -R 755 "$APP_DIR"
chmod -R 775 "$APP_DIR/storage" "$APP_DIR/bootstrap/cache" "$APP_DIR/database"

# ── 13. Cache ─────────────────────────────────────────────────
echo "▶ Cacheando configuración..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ── 14. Nginx config ──────────────────────────────────────────
echo "▶ Configurando Nginx..."
cat > /etc/nginx/sites-available/quiniela2026 << 'NGINX'
server {
    listen 80;
    listen [::]:80;
    server_name _;

    root /var/www/quiniela2026/public;
    index index.php index.html;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/phpPHP_VER-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
NGINX

# Replace PHP version placeholder
sed -i "s/PHP_VER/${PHP_VER}/g" /etc/nginx/sites-available/quiniela2026

# Enable site
ln -sf /etc/nginx/sites-available/quiniela2026 /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test and reload nginx
nginx -t && systemctl reload nginx

# ── 15. PHP-FPM ───────────────────────────────────────────────
echo "▶ Iniciando PHP-FPM..."
systemctl enable php${PHP_VER}-fpm
systemctl restart php${PHP_VER}-fpm
systemctl enable nginx
systemctl start nginx

# ── 16. Done ──────────────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════════════════╗"
echo "║  ✅  INSTALACIÓN COMPLETADA                          ║"
echo "╠══════════════════════════════════════════════════════╣"
echo "║  🌐  URL:    http://${DOMAIN}                        "
echo "║  👤  Admin:  usuario 'admin' (sin contraseña)        ║"
echo "║  📁  App:    ${APP_DIR}                              "
echo "╚══════════════════════════════════════════════════════╝"
echo ""
