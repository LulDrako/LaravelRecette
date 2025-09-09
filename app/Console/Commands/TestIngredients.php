<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Recette;

class TestIngredients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ingredients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test les ingrédients des recettes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== TEST DES INGREDIENTS ===');
        
        // Récupérer toutes les recettes
        $recettes = Recette::all();
        
        foreach ($recettes as $recette) {
            $this->info("Recette #{$recette->id}: {$recette->titre}");
            $this->info("Ingrédients bruts:");
            $this->line($recette->ingredients);
            $this->info("Nombre de caractères: " . strlen($recette->ingredients));
            $this->info("---");
        }
        
        return 0;
    }
}
