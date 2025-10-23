FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip git libsqlite3-dev libpq-dev curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN touch database/database.sqlite
RUN chmod 666 database/database.sqlite
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache
RUN php artisan storage:link

EXPOSE 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
