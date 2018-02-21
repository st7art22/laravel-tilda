<?php

namespace IncOre\Tilda;

use Illuminate\Support\ServiceProvider;

class TildaServiceProvider extends ServiceProvider
{

    public function boot()
    {
    }

    public function register()
    {

        $this->app->singleton(TildaLoader::class, function () {
            $client = new TildaApi;
            return new TildaLoader($client, config('tilda.path'));
        });
        $this->app->alias(TildaLoader::class, 'tilda');

        $this->publishes([
            __DIR__ . '/Config/tilda.php' => config_path('tilda.php'),
        ]);
    }

}