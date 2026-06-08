#!/bin/bash
set -e

echo "=== VaksinKu Setup Script ==="

# Fix circular symlink jika ada
echo "[1/7] Fixing storage symlinks..."
find /var/www/html/storage -type l -delete 2>/dev/null || true

# Buat struktur folder storage yang benar
echo "[2/7] Creating storage directories..."
mkdir -p /var/www/html/storage/app/public
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# Fix permissions
echo "[3/7] Fixing permissions..."
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Copy .env jika belum ada
echo "[4/7] Checking .env file..."
if [ ! -f /var/www/html/.env ]; then
    echo ".env not found, copying from .env.docker..."
    cp /var/www/html/.env.docker /var/www/html/.env
fi

# Generate APP_KEY hanya jika belum ada atau kosong
echo "[5/7] Checking APP_KEY..."
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "APP_KEY not found or invalid, generating..."
    php artisan key:generate --force
else
    echo "APP_KEY already set, skipping."
fi

# Run composer install jika vendor belum ada
echo "[6/7] Checking vendor..."
if [ ! -d /var/www/html/vendor ]; then
    echo "Running composer install..."
    composer install --no-dev --optimize-autoloader
else
    echo "Vendor already exists, skipping."
fi

# Artisan setup
echo "[7/7] Running artisan setup..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan storage:link --force 2>/dev/null || true
php artisan migrate --force 2>/dev/null || true

echo "=== Setup Complete ==="

# Jalankan PHP-FPM
exec php-fpm