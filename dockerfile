# ── Stage 1 : build des assets front-end (Vite) ──
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── Stage 2 : image PHP finale ──
FROM php:8.2-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libpq-dev libonig-dev libxml2-dev default-mysql-client \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql mysqli zip mbstring bcmath

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
COPY . /var/www/html

# Copier les assets compilés depuis le stage Node
COPY --from=assets /app/public/build /var/www/html/public/build

WORKDIR /var/www/html

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Installer les dépendances PHP (hors dev)
RUN composer install --no-dev --optimize-autoloader

# Pointer le DocumentRoot vers /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Copier le script d'entrée qui gère le port dynamique de Render
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port (indicatif — Render utilisera $PORT dynamiquement au runtime)
EXPOSE 80

# Démarrage via le script qui adapte le port au démarrage
CMD ["docker-entrypoint.sh"]