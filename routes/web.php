<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecetteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
})->middleware(['auth', 'verified'])->name('home');

// Routes publiques pour voir les recettes
Route::get('/recettes', [RecetteController::class, 'index'])->name('recettes.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes protégées pour gérer les recettes (create AVANT show pour éviter les conflits)
    Route::get('/recettes/create', [RecetteController::class, 'create'])->name('recettes.create');
    Route::post('/recettes', [RecetteController::class, 'store'])->name('recettes.store');
    Route::get('/recettes/{recette}/edit', [RecetteController::class, 'edit'])->name('recettes.edit');
    Route::put('/recettes/{recette}', [RecetteController::class, 'update'])->name('recettes.update');
    Route::delete('/recettes/{recette}', [RecetteController::class, 'destroy'])->name('recettes.destroy');
    
    // Route pour les recettes de l'utilisateur connecté
    Route::get('/mes-recettes', [RecetteController::class, 'mesRecettes'])->name('mes-recettes.index');
});

// Route publique pour voir une recette (doit être après /recettes/create)
Route::get('/recettes/{recette}', [RecetteController::class, 'show'])->name('recettes.show');

require __DIR__.'/auth.php';
