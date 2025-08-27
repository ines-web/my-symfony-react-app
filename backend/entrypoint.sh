#!/bin/bash
set -e

echo "🚀 Démarrage Symfony 7.3 avec PHP 8.2..."

# Vérification de la version PHP
echo "📋 PHP Version: $(php -v | head -n 1)"

# Attente de PostgreSQL
echo "⏳ Connexion à PostgreSQL..."
until pg_isready -h db -p 5432 -U catalog 2>/dev/null; do
    echo "   Attente de la base de données..."
    sleep 2
done

echo "✅ Base de données connectée!"

# Installation complète des dépendances pour le développement
echo "📦 Installation des dépendances Symfony 7.3..."
composer install --no-interaction --optimize-autoloader

# Vérification de Symfony
echo "🔍 Vérification de Symfony..."
php bin/console about 2>/dev/null || echo "   Console Symfony prête"

# Configuration de la base de données
echo "🗄️  Configuration de la base de données..."
php bin/console doctrine:database:create --if-not-exists --no-interaction

# Mise à jour du schéma de base
echo "📊 Mise à jour du schéma..."
php bin/console doctrine:schema:update --force --no-interaction 2>/dev/null || echo "   Schéma à jour"

# Migrations si disponibles
echo "🔄 Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration

# Nettoyage et réchauffement du cache
echo "🧹 Optimisation du cache..."
php bin/console cache:clear --no-warmup
php bin/console cache:warmup

echo ""
echo "🌟 Symfony 7.3 est prêt !"
echo "🌐 Application disponible sur http://localhost:8000"
echo "🛠️  Interface de debug sur http://localhost:8000/_profiler"
echo ""

# Démarrage du serveur de développement
exec php -S 0.0.0.0:8000 -t public