FROM php:8.2-apache

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    zip unzip curl git libzip-dev libpq-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring bcmath

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
COPY . /var/www/html

WORKDIR /var/www/html

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Installer les dépendances PHP (hors dev)
RUN composer install --no-dev --optimize-autoloader

# Expose port 80 (Apache)
EXPOSE 80

# Démarrage par défaut d'Apache (gère le serveur HTTP)
CMD ["apache2-foreground"]
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
