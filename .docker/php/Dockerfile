FROM php:8.2-fpm-buster
ARG TIMEZONE

# Copier le fichier php.ini pour la configuration
COPY php.ini /usr/local/etc/php/conf.d/docker-php-config.ini

# Installer les dépendances nécessaires et mettre à jour Git
RUN apt-get update && apt-get install -y \
    gnupg \
    g++ \
    procps \
    openssl \
    git \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libicu-dev \
    libonig-dev \
    libxslt1-dev \
    acl \
    # Installer les outils pour ajouter des dépôts PPA
    software-properties-common \
    # Ajouter le PPA pour Git
    && add-apt-repository ppa:git-core/ppa \
    && apt-get update \
    # Installer la dernière version de Git
    && apt-get install -y git \
    # Nettoyer le cache pour réduire l'image
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Configurer et installer les extensions PHP
RUN docker-php-ext-configure gd --with-jpeg --with-freetype \
    && docker-php-ext-install \
    pdo pdo_mysql zip xsl gd intl opcache exif mbstring

# Configurer le fuseau horaire
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
    && printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
    && date

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définir le répertoire de travail
WORKDIR /var/www/symfony
