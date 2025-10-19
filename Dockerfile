# Image PHP officielle
FROM php:8.2-cli

# Installer outils système et PostgreSQL
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libsqlite3-dev \
    libpq-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Installer Node.js et npm (dernière version LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Installer extensions PHP
RUN docker-php-ext-install pdo pdo_sqlite pdo_pgsql pgsql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier tout le projet
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installer dépendances Node et compiler assets Vite
RUN npm install
RUN npm run build

# Créer fichier SQLite si nécessaire
RUN touch database/database.sqlite
RUN chmod 666 database/database.sqlite

# Créer dossiers storage et bootstrap/cache avec permissions correctes
RUN mkdir -p storage/framework/views \
    storage/framework/cache \
    storage/framework/sessions \
    bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Créer le lien symbolique pour les fichiers publics depuis storage
RUN php artisan storage:link

# Exposer le port Render
EXPOSE 10000

# Démarrer serveur PHP intégré sur dossier public
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
