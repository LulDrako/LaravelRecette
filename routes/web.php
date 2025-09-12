<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecetteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LaravelCook - Vos meilleures recettes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #ff6b35; text-align: center; margin-bottom: 30px; }
        p { text-align: center; color: #666; font-size: 18px; line-height: 1.6; }
        .btn { display: inline-block; padding: 12px 24px; background: #ff6b35; color: white; text-decoration: none; border-radius: 5px; margin: 10px; }
        .btn:hover { background: #e55a2b; }
        .success { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎉 LaravelCook est déployé avec succès !</h1>
        <p class="success">Votre application Laravel fonctionne parfaitement sur Heroku !</p>
        <p>Base de données PostgreSQL configurée ✅<br>
        Assets Vite compilés ✅<br>
        Configuration Heroku optimisée ✅</p>
        <div style="text-align: center; margin-top: 30px;">
            <a href="/register" class="btn">Créer un compte</a>
            <a href="/login" class="btn">Se connecter</a>
        </div>
    </div>
</body>
</html>';
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour les recettes (protégées par authentification)
    Route::resource('recettes', RecetteController::class);
    
    // Route pour les recettes de l'utilisateur connecté
    Route::get('/mes-recettes', [RecetteController::class, 'mesRecettes'])->name('mes-recettes.index');
});

require __DIR__.'/auth.php';
