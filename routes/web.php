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
