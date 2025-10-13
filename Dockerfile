FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Créer les dossiers de cache et vues
RUN mkdir -p storage/framework/cache storage/framework/views bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD ["sh", "-c", "\
    php artisan migrate --force || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080} \
"]
