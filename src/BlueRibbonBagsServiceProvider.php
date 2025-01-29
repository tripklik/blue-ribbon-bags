<?php

namespace Tripklik\BlueRibbonBags;

use Illuminate\Support\ServiceProvider;

class BlueRibbonBagsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/blue-ribbon-bags.php' => config_path('blue-ribbon-bags.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/blue-ribbon-bags.php', 'blue-ribbon-bags'
        );

        $this->app->singleton(BlueRibbonBagsClient::class, function ($app) {
            return new BlueRibbonBagsClient(
                config('blue-ribbon-bags.base_url'),
                config('blue-ribbon-bags.auth_token')
            );
        });
    }
}
