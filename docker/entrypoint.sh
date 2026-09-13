#!/bin/bash
set -e

# Default PORT to 80 if not set (Render provides $PORT, Railway provides $PORT)
PORT="${PORT:-80}"
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen 80;/listen ${PORT};/g" /etc/nginx/sites-available/default

# Ensure .env exists and has an APP_KEY
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
    php artisan key:generate --force || true
fi

# Ensure storage directories exist with right permissions
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/logs
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create SQLite database directory and file with full read/write permissions
mkdir -p /var/www/html/database
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "Creating database.sqlite..."
    touch /var/www/html/database/database.sqlite
fi
chmod 777 /var/www/html/database
chmod 666 /var/www/html/database/database.sqlite
chown -R www-data:www-data /var/www/html/database

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
