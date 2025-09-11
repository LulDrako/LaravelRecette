# LaravelRecette

🎯 Premier projet Laravel réalisé dans le cadre de mon apprentissage personnel du framework.  
Ce projet me permet de découvrir les bases de Laravel à travers un cas concret : la gestion de recettes culinaires.


## ⚙️ Stack technique

- Laravel 11
- Laravel Breeze (authentification)
- Blade (front-end)
- PostgreSQL
- PHP 8+


## ✨ Fonctionnalités

- Ajouter, modifier, supprimer une recette
- Upload d'image pour chaque recette
- Filtrer les recettes par ingrédient ou type de plat ou tags.
- Authentification (voir les recettes d'un utilisateur spécifique)
- Affichage des recettes de tous les utilisateurs


## 🚀 Installation rapide

### Prérequis
- PHP 8+ 
- Composer
- PostgreSQL 12+

### Installation
```bash
# 1. Cloner le projet
git clone https://github.com/LulDrako/LaravelRecette.git
cd LaravelRecette

# 2. Installer les dépendances
composer install

# 3. Créer la base de données PostgreSQL
createdb laravel_recettes

# 4. Créer le fichier .env avec ce contenu :

Créer un fichier .env à la racine avec :

APP_NAME=LaravelRecette
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_recettes
DB_USERNAME=postgres
DB_PASSWORD=votre_mot_de_passe_postgresql

⚠️ Laissez APP_KEY= vide, elle sera générée à l'étape 5 !

# 5. Générer la clé d'application
php artisan key:generate

# 6. Exécuter les migrations
php artisan migrate

# 7. Lancer le serveur
php artisan serve
```

🎉 **C'est tout !** L'application est accessible sur `http://localhost:8000`


## 🧪 En cours d'apprentissage

Ce projet est encore en construction, il me sert à :
- M'entraîner à manipuler les modèles, contrôleurs, vues
- Comprendre le système de routes Laravel
- Travailler la logique de filtrage dans les requêtes
- Gérer l'upload d'images
- Implémenter une interface propre et simple


## 🙋‍♂️ Auteur

Ce projet est développé par [LulDrako](https://github.com/LulDrako) dans le cadre de mon auto-formation Laravel.  
Il est public pour montrer mon avancée et recevoir des retours !