FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    unzip git libpq-dev curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --optimize-autoloader

# Nettoyer le cache Laravel pour les variables d'environnement prod
RUN php artisan config:clear
RUN php artisan cache:clear

RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache
RUN php artisan storage:link

# Expose port pour Render
EXPOSE 10000

# CMD shell pour que $PORT soit évalué au runtime
CMD php -S 0.0.0.0:$PORT -t public
