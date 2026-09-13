#!/bin/bash
set -e

# Default PORT to 80 if not set (Render provides $PORT, Railway provides $PORT)
PORT="${PORT:-80}"
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/sites-available/default

# Create SQLite database directory and file if SQLite is configured
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    mkdir -p /var/www/html/database
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        echo "Creating database.sqlite..."
        touch /var/www/html/database/database.sqlite
    fi
    chown -R www-data:www-data /var/www/html/database
fi

# Ensure storage directories exist with right permissions
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    echo "Generating Application Key..."
    php artisan key:generate --force || true
fi

# Create public storage symlink
php artisan storage:link --force || true

# Run database migrations and seeders if needed
echo "Running migrations..."
php artisan migrate --force || true
php artisan db:seed --force || true

# Cache configs and routes
echo "Caching Laravel configuration, routes and views..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Supervisor (Nginx + PHP-FPM)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
