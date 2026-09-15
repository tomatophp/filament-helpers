<?php

namespace TomatoPHP\FilamentHelpers;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\FilamentHelpers\Console\FilamentHelpersGenerator;

class FilamentHelpersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/filament-helpers.php', 'filament-helpers');
    }

    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            FilamentHelpersGenerator::class,
        ]);

        $this->publishes([
            __DIR__ . '/../config/filament-helpers.php' => config_path('filament-helpers.php'),
        ], 'filament-helpers-config');

        $this->publishes([
            __DIR__ . '/../stubs' => base_path('stubs/filament-helpers'),
        ], 'filament-helpers-stubs');
    }
}
