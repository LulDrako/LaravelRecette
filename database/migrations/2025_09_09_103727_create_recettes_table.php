<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recettes', function (Blueprint $table) {
            $table->id();
            $table->string('titre'); // Nom de la recette
            $table->text('description'); // Description de la recette
            $table->text('ingredients'); // Liste des ingrédients
            $table->text('instructions'); // Étapes de préparation
            $table->string('image')->nullable(); // Photo de la recette (optionnelle)
            $table->string('type')->nullable(); // Type de recette (entrée, plat, dessert)
            $table->integer('temps_preparation')->nullable(); // Temps en minutes
            $table->integer('portions')->default(1); // Nombre de portions
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Lien vers l'utilisateur
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recettes');
    }
};
