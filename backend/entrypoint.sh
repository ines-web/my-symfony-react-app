#!/bin/bash
set -e

echo "Démarrage du conteneur Symfony..."

# Attente de la base de données uniquement
echo "Attente de la base de données..."
until pg_isready -h db -p 5432 -U catalog 2>/dev/null; do
  echo "En attente de la base de données..."
  sleep 2
done

echo "Base de données prête!"

# Migrations Doctrine si nécessaire (optionnel)
if [ "$APP_ENV" = "dev" ]; then
    echo "Exécution des migrations en mode développement..."
    php bin/console doctrine:migrations:migrate --no-interaction || true
fi

# Démarrage du serveur Symfony
echo "Démarrage de Symfony sur le port 8000..."
exec php -S 0.0.0.0:8000 -t public