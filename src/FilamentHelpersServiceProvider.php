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

    }

    public function boot(): void
    {
        //you boot methods here
    }
}
