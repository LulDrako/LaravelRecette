<div align="center">

# LaravelCook

[![last commit](https://img.shields.io/github/last-commit/LulDrako/LaravelRecette?color=blue)](https://github.com/LulDrako/LaravelRecette)
[![top language](https://img.shields.io/github/languages/top/LulDrako/LaravelRecette?color=blue)](https://github.com/LulDrako/LaravelRecette)
[![languages count](https://img.shields.io/github/languages/count/LulDrako/LaravelRecette?color=grey)](https://github.com/LulDrako/LaravelRecette)

Premier projet Laravel réalisé dans le cadre de mon apprentissage personnel du framework.  
Ce projet me permet de découvrir les bases de Laravel à travers un cas concret : la gestion de recettes culinaires.

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8+-purple?style=flat-square&logo=php)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-12+-blue?style=flat-square&logo=postgresql)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![Composer](https://img.shields.io/badge/Composer-885630?style=flat-square&logo=composer&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?style=flat-square&logo=vite&logoColor=white)
</div>

## ✨ Fonctionnalités

- Ajouter, modifier, supprimer une recette
- Upload d'image pour chaque recette
- Filtrer les recettes par ingrédient ou type de plat ou tags.
- Authentification (voir les recettes d'un utilisateur spécifique)
- Affichage des recettes de tous les utilisateurs


## Installation rapide

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

# 4. Créer le fichier .env
Copier le fichier .env.example et le renommer en .env, puis remplir vos informations.

⚠️ Laissez APP_KEY= vide, elle sera générée à l'étape 5 !

# 5. Générer la clé d'application
php artisan key:generate

# 6. Exécuter les migrations
php artisan migrate

# 7. Installer les dépendances Node.js et compiler les assets
npm install
npm run build

# 8. Lancer le serveur
php artisan serve
```

🎉 **C'est tout !** L'application est accessible sur `http://localhost:8000`

### Pour le développement
```bash
# Lancer Vite en parallèle (dans un autre terminal)
npm run dev
```


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
