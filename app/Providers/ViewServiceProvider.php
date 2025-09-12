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
        
        // Remplacer complètement le finder
        $finder = $view->getFinder();
        $finder->setPaths([resource_path('views')]);
    }
}
