# 1. Utiliser une image de base PHP avec Composer préinstallé
FROM php:8.2-cli

# 2. Installer les dépendances système nécessaires (si besoin)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 3. Définir le répertoire de travail
WORKDIR /app

# 4. Copier le code source dans le conteneur
COPY . /app

# 5. Installer les dépendances PHP via Composer
# Assurer que Composer est déjà installé dans l'image de base
RUN if [ ! -f "/usr/local/bin/composer" ]; then \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    fi

# Installation des dépendances sans les packages de développement
# et en désactivant l'exécution des scripts Composer pour éviter les erreurs liées aux bundles dev
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 6. Exposer le port 8000 (ou celui utilisé par ton serveur)
EXPOSE 8000

# 7. Commande par défaut (peut être modifiée selon ton application)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
