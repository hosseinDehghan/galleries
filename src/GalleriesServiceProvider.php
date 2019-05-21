<?php

namespace Hosein\Galleries;

use Illuminate\Support\ServiceProvider;

class GalleriesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'GalleriesView');
        $this->publishes([
            __DIR__.'/Views' => resource_path('views/vendor/GalleriesView'),
        ],"galleriesview");
        $this->publishes([
            __DIR__.'/Migrations' => database_path('/migrations')
        ], 'galleriesmigrations');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }
}
