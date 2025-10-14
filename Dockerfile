# Utilise l'image officielle PHP avec extensions nécessaires
FROM php:8.2-cli

# Installer les extensions et outils nécessaires
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Installer Node et NPM si nécessaire
RUN apt-get update && apt-get install -y nodejs npm

# Copier package.json et package-lock.json
COPY package.json package-lock.json ./

# Installer les dépendances Node
RUN npm install

# Compiler les assets pour la production
RUN npm run build

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier le projet
WORKDIR /var/www
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Créer le fichier SQLite si nécessaire
RUN touch database/database.sqlite
RUN chmod 666 database/database.sqlite

# Créer les dossiers storage et cache avec permissions
RUN mkdir -p storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    bootstrap/cache


# Donner les permissions à Laravel pour écrire
RUN chmod -R 777 storage bootstrap/cache

# Exécuter les migrations (ignore si déjà fait)
RUN php artisan migrate --force || true

# Exposer le port de Render
EXPOSE 10000

# Commande pour démarrer Laravel
RUN php artisan storage:link
# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
