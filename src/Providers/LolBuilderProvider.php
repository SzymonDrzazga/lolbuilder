<?php

namespace SzymonDrzazga\Lolbuilder\Providers;

use Illuminate\Support\ServiceProvider;
use SzymonDrzazga\Lolbuilder\Lolbuilder;

class LolBuilderProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Lolbuilder::class, function ($app) {
            return new Lolbuilder();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'lolbuilder');
        $this->publishes([
            __DIR__.'/views' => resource_path('views/vendor/lolbuilder'),
        ]);
    }
}
