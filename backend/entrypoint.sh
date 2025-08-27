#!/bin/bash
set -e

echo "Configuration des permissions..."
chmod +x /var/www/html/wait-for-db.sh 2>/dev/null || true

echo "Installation des dépendances Composer..."
cd /var/www/html

# Vérifier si composer.json existe
if [ -f "composer.json" ]; then
    echo "Installation en cours..."
    composer install --no-interaction --optimize-autoloader

    # Générer l'autoload si nécessaire
    composer dump-autoload --optimize
else
    echo "Erreur: composer.json non trouvé dans /var/www/html"
    exit 1
fi

echo "Attente de la base de données..."
until pg_isready -h db -p 5432 -U catalog 2>/dev/null; do
  echo "En attente de la base de données..."
  sleep 2
done

echo "Base de données prête! Démarrage de Symfony..."
exec php -S 0.0.0.0:8000 -t public