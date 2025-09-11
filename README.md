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
- Authentification (voir les recettes d’un utilisateur spécifique)
- Affichage des recettes de tous les utilisateurs


## 🚀 Installation et utilisation

### Prérequis
- PHP 8+ 
- Composer

### Installation
```bash
# Cloner le projet
git clone https://github.com/LulDrako/LaravelRecette.git
cd LaravelRecette

# Installer les dépendances
composer install

# Créer la base de données
php artisan migrate

# Lancer le serveur
php artisan serve
```

Le projet sera accessible sur `http://localhost:8000`


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