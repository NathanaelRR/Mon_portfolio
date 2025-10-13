# Utilise PHP CLI (8.2) et installe pdo_sqlite + outils nécessaires
FROM php:8.2-cli

# Dépendances système
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copier Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Permissions pour storage et cache
RUN chmod -R 755 storage bootstrap/cache

# Exposer le port par défaut (Render fournit $PORT)
EXPOSE 8080

# Commande de démarrage
CMD ["sh", "-c", "\
    php artisan migrate --force || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080} \
"]
