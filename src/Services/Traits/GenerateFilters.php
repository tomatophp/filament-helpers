<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;

trait GenerateFilters
{
    public function filters(): void
    {
        $filtersPath = app_path('/Filament/Filters').'/';
        $filtersNamespace = "App\\Filament\\Filters";
        if($this->module){
            $modulePath = module_path($this->module);
            $filtersPath = $modulePath . "/app/Filament/Filters/";
            $filtersNamespace = "Modules\\". $this->module . "\\Filament\\Filters";
        }
        if($this->path){
            $filtersPath = $this->path . "/Filament/Filters/";
            $filtersNamespace = $this->namespace . "\\Filament\\Filters";
        }
        if($this->resource){
            $filtersPath = $this->path . "/Filament/Resources/".$this->resource . "/Filters/";
            $filtersNamespace = $this->namespace ."\\Filament\\Resources\\" . $this->resource . "\\Filters";
        }

        $filename = $filtersPath . $this->name . "Filters.php";
        if(File::exists($filename)){
            error("The Filters file already exists");
            $overwrite = confirm("Do you want to overwrite the file?", default: false);
            if(!$overwrite){
                return;
            }
        }

        $this->generateStubs(
            from: $this->stubPath . "filters.stub",
            to: $filename,
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
