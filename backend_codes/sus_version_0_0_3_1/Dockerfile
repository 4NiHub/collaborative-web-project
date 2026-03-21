FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pgsql pdo_pgsql zip

WORKDIR /app

COPY . .

RUN chown -R 1000:1000 storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && mkdir -p storage/framework/{cache,sessions,views} \
    && chown -R 1000:1000 storage/framework \
    && chmod -R 775 storage/framework

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan optimize --force || true

EXPOSE 8080

CMD ["heroku-php-apache2", "public/"]
