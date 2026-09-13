FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Avoid interactive prompts during apt install
ENV DEBIAN_FRONTEND=noninteractive

# Install system packages, Nginx, and build dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libsqlite3-dev \
    nginx \
    supervisor \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install required PHP extensions for Laravel 11
RUN docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Install latest stable Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source
COPY . /var/www/html

# Copy Docker configurations
COPY docker/nginx.conf /etc/nginx/sites-available/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Fix CRLF line endings and set execution permissions
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

# Ensure .env exists with APP_KEY during build
RUN cp .env.example .env && php -r "file_exists('.env') || copy('.env.example', '.env');"

# Install production PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Generate application key in build
RUN php artisan key:generate --force

# Set directory permissions for web server
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Port exposure (dynamically mapped by Render / Railway via $PORT)
EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
