#!/bin/bash

echo "Attente de la base de données..."
until pg_isready -h db -p 5432 -U catalog 2>/dev/null; do
  echo "En attente de la base de données..."
  sleep 2
done

echo "Base de données prête! Démarrage de Symfony..."
exec php -S 0.0.0.0:8000 -t public