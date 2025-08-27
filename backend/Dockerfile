FROM php:8.2-cli

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    postgresql-client

# Installation des extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configuration du répertoire de travail
WORKDIR /var/www/html

# Copie du script d'attente
COPY wait-for-db.sh /usr/local/bin/wait-for-db.sh
RUN chmod +x /usr/local/bin/wait-for-db.sh

# Copie des fichiers de configuration Composer
COPY composer.json composer.lock* ./

# Installation des dépendances PHP
RUN composer install --no-scripts --no-autoloader || echo "Pas de dépendances composer"

# Copie du code source
COPY . .

# Finalisation de l'installation Composer
RUN composer dump-autoload --optimize || echo "Autoload non nécessaire"

# Permissions
RUN chmod -R 755 /var/www/html

EXPOSE 8000

# Démarrage avec attente de la DB
CMD ["/usr/local/bin/wait-for-db.sh"]