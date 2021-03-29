<?php

namespace Aslam\Bca\Providers;

use Aslam\Bca\Bca;
use Illuminate\Support\ServiceProvider;

class BcaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../config/bank-bca.php' => config_path('bank-bca.php'),
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/bank-bca.php', 'bank-bca');

        $this->app->singleton('BcaAPI', function () {
            return new Bca();
        });
    }
}
