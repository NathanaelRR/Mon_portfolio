FROM php:8.2-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copier Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Créer tous les dossiers nécessaires à Laravel et définir les permissions
RUN mkdir -p storage/framework/cache \
    storage/framework/views \
    storage/framework/sessions \
    storage/logs \
    bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# Exposer le port par défaut (Render fournit $PORT)
EXPOSE 8080

# Commande de démarrage
CMD ["sh", "-c", "\
    php artisan migrate --force || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080} \
"]
