# Utilise PHP CLI (8.2) et installe pdo_sqlite + outils
FROM php:8.2-cli

# Dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    zip unzip git libzip-dev libsqlite3-dev \
 && docker-php-ext-install pdo pdo_sqlite zip \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copier Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définit le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers du projet
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Générer la clé (note: en prod tu peux définir APP_KEY via RENDER env vars)
RUN php artisan key:generate --ansi || true

# Permissions (storage & cache)
RUN chown -R www-data:www-data storage bootstrap/cache || true
RUN chmod -R 755 storage bootstrap/cache || true

# Exposer un port par défaut (Render fournira $PORT)
EXPOSE 8080

# Démarrage : utilise la variable d'environnement PORT si présente
CMD ["sh", "-c", "php artisan migrate --force || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
