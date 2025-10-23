# Utiliser l'image PHP officielle avec CLI et extensions
FROM php:8.2-cli

# Installer outils système et PostgreSQL
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    curl \
    && rm -rf /var/lib/apt/lists/*

# Installer extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_pgsql

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www

# Copier tout le projet
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Créer dossiers storage et bootstrap/cache avec permissions correctes
RUN mkdir -p storage/framework/views storage/framework/cache storage/framework/sessions bootstrap/cache
RUN chmod -R 777 storage bootstrap/cache

# Créer le lien symbolique pour les fichiers publics depuis storage
RUN php artisan storage:link

# Exposer le port utilisé par Render
EXPOSE 10000

# Démarrer le serveur PHP intégré sur le dossier public
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
