<?php

namespace TomatoPHP\FilamentHelpers;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentHelpers\Console\FilamentHelpersGenerator;


class FilamentHelpersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
            FilamentHelpersGenerator::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/filament-helpers.php', 'filament-helpers');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/filament-helpers.php' => config_path('filament-helpers.php'),
        ], 'filament-helpers-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'filament-helpers-migrations');
        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-helpers');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/filament-helpers'),
        ], 'filament-helpers-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-helpers');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => base_path('lang/vendor/filament-helpers'),
        ], 'filament-helpers-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

    }

    public function boot(): void
    {
        //you boot methods here
    }
}
