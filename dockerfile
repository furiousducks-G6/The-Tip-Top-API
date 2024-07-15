# Utiliser une image PHP spécifique avec PHP-FPM
FROM php:8.2-fpm

# Installer les dépendances nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier les fichiers de l'application Symfony dans le conteneur
COPY . /var/www/html

# Installer les dépendances Symfony avec Composer
RUN composer install --no-scripts --no-autoloader

# Exposer le port 9000 utilisé par PHP-FPM
EXPOSE 9000

# Commande par défaut pour lancer PHP-FPM
CMD ["php-fpm"]
