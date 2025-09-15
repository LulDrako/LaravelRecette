<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recette extends Model
{
    /**
     * Les champs qu'on peut remplir en masse (protection contre les attaques)
     */
    protected $fillable = [
        'titre',
        'description', 
        'ingredients',
        'instructions',
        'image',
        'type',
        'temps_preparation',
        'portions',
        'user_id',
        'tags'
    ];

    /**
     * Les types de données pour certains champs
     */
    protected $casts = [
        'tags' => 'array'
    ];

    /**
     * Relation : Une recette appartient à un utilisateur
     * (chaque recette a un propriétaire)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor pour l'URL de l'image
     * Retourne directement l'image (base64 ou URL)
     */
    public function getImageUrlAttribute()
    {
        return $this->image;
    }
}
