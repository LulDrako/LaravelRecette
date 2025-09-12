<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Factory;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Forcer les bons chemins de vues
        $view = $this->app->make(Factory::class);
        
        // Vider tous les chemins existants
        $paths = $view->getFinder()->getPaths();
        foreach ($paths as $path) {
            $view->getFinder()->removeLocation($path);
        }
        
        // Ajouter le bon chemin
        $view->addLocation(resource_path('views'));
    }
}
