# 1. Utiliser une image de base PHP avec Composer préinstallé
FROM php:8.2-cli

# 2. Installer les dépendances système nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    && docker-php-ext-install zip intl pdo pdo_pgsql

# 3. Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 4. Définir le répertoire de travail
WORKDIR /app

# 5. Copier le code source dans le conteneur
COPY . /app

# 6. Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader

# 7. Exposer le port 8000
EXPOSE 8000

# 8. Commande par défaut pour démarrer le serveur
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
