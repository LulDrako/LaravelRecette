#!/bin/bash

# Script de déploiement Heroku
echo "=== Début du déploiement ==="

# Vérifier la structure
echo "Vérification de la structure..."
ls -la
ls -la resources/
ls -la resources/views/

# Les assets Vite sont compilés localement
echo "Assets Vite compilés localement"

# Nettoyer le cache
echo "Nettoyage du cache..."
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear

# Recréer le cache (sans view:cache qui cause le problème)
echo "Recréation du cache..."
php artisan config:cache
php artisan route:cache
# php artisan view:cache  # Désactivé car cause l'erreur /tmp/build_xxx
php artisan optimize

# Vérifier les migrations
echo "Vérification des migrations..."
php artisan migrate:status

echo "=== Déploiement terminé ==="