
FROM php:8.2-cli

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    postgresql-client \
    libxml2-dev \
    libonig-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    xml \
    intl \
    opcache

# Configuration d'OPcache optimisée
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=512'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.validate_timestamps=1'; \
        echo 'opcache.revalidate_freq=0'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Variables d'environnement pour Composer
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

# SOLUTION: Ne copier QUE composer.json (pas le .lock)
COPY composer.json ./

# Générer un nouveau composer.lock avec les bonnes versions
RUN composer update --no-scripts --no-autoloader

# Copier le reste du code
COPY . .

# Installation finale optimisée
RUN composer dump-autoload --optimize

# Configuration des permissions
RUN chmod +x ./entrypoint.sh

EXPOSE 8000

CMD ["./entrypoint.sh"]