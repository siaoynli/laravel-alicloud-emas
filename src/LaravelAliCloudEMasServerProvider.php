<?php

namespace Siaoynli\AliCloud\EMas;

use Illuminate\Support\ServiceProvider;

class LaravelAliCloudEMasServerProvider extends ServiceProvider
{
    protected $defer = true;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('emas', function ($app) {
            return new EMas($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/alicloud-emas.php' => config_path('alicloud-emas.php'),
        ]);
    }

    public function provides()
    {
        return ['emas'];
    }

}
