# Use the PHP image that matches your local version (change 8.2 or 8.3 if needed)
FROM php:8.3-fpm-alpine

# Install system dependencies Laravel usually needs
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www/html

# Copy entire project
COPY . .

# Give correct ownership & permissions to storage & cache
# App Platform runs as non-root user (usually uid 1000)
RUN chown -R 1000:1000 storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Cache Laravel stuff (helps performance)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan optimize

# Expose port (App Platform ignores this but good practice)
EXPOSE 9000

CMD ["php-fpm"]
