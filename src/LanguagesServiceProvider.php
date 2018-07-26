<?php

namespace D3jn\LaravelLanguages;

use Illuminate\Support\ServiceProvider;

class LanguagesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/languages.php' => config_path('languages.php'),
        ]);
    }

    /**
     * Registers this package's services.
     */
    public function register()
    {
        $this->app->bind('languages', Languages::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/languages.php',
            'languages'
        );
    }
}
