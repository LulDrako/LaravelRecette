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
        'user_id'
    ];

    /**
     * Relation : Une recette appartient à un utilisateur
     * (chaque recette a un propriétaire)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
