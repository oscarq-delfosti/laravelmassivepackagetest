<?php

namespace Oscar\Massive;

use Illuminate\Support\ServiceProvider;

class MassivePackageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->app->make('Oscar\Massive\Controllers\MainController');
        $this->app->make('Oscar\Massive\Controllers\MassiveController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
