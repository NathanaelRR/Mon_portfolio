# Utilise l'image officielle PHP avec extensions nécessaires
FROM php:8.2-cli

# Installer les extensions nécessaires pour SQLite et unzip/git
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libsqlite3-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_sqlite

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier tout le projet
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer les dépendances Node et compiler les assets Vite
RUN npm install
RUN npm run build

# Créer le fichier SQLite si nécessaire et donner les permissions
RUN touch database/database.sqlite
RUN chmod 666 database/database.sqlite

# Créer les dossiers de storage et bootstrap/cache avec les permissions correctes
RUN mkdir -p storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Créer le lien symbolique pour les fichiers publics depuis storage
RUN php artisan storage:link

# Exposer le port utilisé par Render
EXPOSE 10000

# Démarrer le serveur PHP intégré sur le dossier public
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
