#!/bin/bash

# Script pour corriger les chemins de vues sur Heroku
echo "🔧 Correction des chemins de vues..."

# Supprimer le cache corrompu
rm -rf bootstrap/cache/*

# Créer le répertoire de cache des vues s'il n'existe pas
mkdir -p storage/framework/views

# Forcer la configuration des vues
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Recréer le cache avec les bons chemins
php artisan config:cache

echo "✅ Chemins de vues corrigés !"
