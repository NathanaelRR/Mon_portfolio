FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Créer SQLite si nécessaire
RUN mkdir -p database && touch database/database.sqlite

RUN php artisan key:generate --ansi || true

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache || true
RUN chmod -R 755 storage bootstrap/cache || true

EXPOSE 10000

CMD ["sh", "-c", "php artisan migrate --force || true; php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]
