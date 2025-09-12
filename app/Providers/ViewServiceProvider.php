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
        $this->app->make(Factory::class)->addLocation(resource_path('views'));
    }
}
