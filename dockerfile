# 1. Choisir l'image de base PHP 8.2 avec CLI
FROM php:8.2-cli

# 2. Installation des dépendances du système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install intl opcache pdo pdo_mysql pdo_pgsql zip gd

# 3. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# 4. Définir le répertoire de travail
WORKDIR /app

# 5. Copier les fichiers de l'application dans le conteneur
COPY . /app

# 6. Installer les dépendances PHP via Composer
RUN composer install --no-scripts --no-autoloader --no-dev

# 7. Compiler le code (générer l'autoloader, optimiser)
RUN composer dump-autoload --optimize && composer install --optimize-autoloader --classmap-authoritative

# 8. Donner les droits d'exécution sur les répertoires de cache et de logs
RUN chown -R www-data:www-data /app/var/cache /app/var/log

# 9. Configuration des droits
RUN chmod -R 755 /app/var

# 10. Exposer le port 8000 (ou celui utilisé par ton serveur)
EXPOSE 8000

# 11. Commande par défaut pour lancer le serveur Symfony (si nécessaire)
CMD ["php", "bin/console", "server:run", "0.0.0.0:8000"]
