#!/bin/sh
set -e

echo "=== Alibaton Payroll — Frontend Startup ==="

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Create SQLite database file if using SQLite
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    DB_FILE="${DB_DATABASE:-/var/www/database/database.sqlite}"
    if [ ! -f "$DB_FILE" ]; then
        echo "Creating SQLite database at $DB_FILE..."
        touch "$DB_FILE"
        chown www-data:www-data "$DB_FILE"
    fi
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction || echo "⚠️ Warning: Migrations failed (DB may not be ready). Continuing startup..."

# Clear & cache config for production
echo "Optimizing..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

if [ "${APP_ENV:-production}" = "production" ]; then
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Ensure correct permissions on storage
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true
chmod -R 775 /var/www/storage /var/www/bootstrap/cache 2>/dev/null || true

echo "Starting services (nginx + php-fpm)..."
exec supervisord -c /etc/supervisord.conf
