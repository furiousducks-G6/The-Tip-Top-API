# 1. Utiliser une image de base PHP avec Composer préinstallé
FROM php:8.2-cli

# 2. Installer les dépendances système nécessaires (si besoin)
RUN apt-get update && apt-get install -y \
    git \
    unzip

# 3. Copier le code source dans le conteneur
WORKDIR /app
COPY . /app

# 4. Installer les dépendances PHP via Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# 5. Exposer le port 8000 (ou celui utilisé par ton serveur)
EXPOSE 8000

# 6. Commande par défaut (peut être modifiée selon ton application)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
