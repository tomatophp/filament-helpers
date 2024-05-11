<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

trait GenerateFilters
{
    public function filters(): void
    {
        $filtersPath = app_path('/Filament/Filters');
        $filtersNamespace = "App\\Filament\\Filters";
        if($this->module){
            $modulePath = module_path($this->module);
            $filtersPath = $modulePath . "app/Filament/Filters/";
            $filtersNamespace = "Modules\\". $this->module . "\\App\\Filament\\Filters";
        }
        if($this->path){
            $filtersPath = $this->path . "/Filament/Filters/";
            $filtersNamespace = $this->namespace . "\\Filament\\Filters";
        }

        $this->generateStubs(
            from: $this->stubPath . "filters.stub",
            to: $filtersPath . $this->name . "Filters.php",
            replacements: [
                'name' => $this->name,
                'namespace' => $filtersNamespace
            ],
            directory: [
                $filtersPath
            ],
        );
    }
}
