<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;

trait GenerateTable
{
    public function table():void
    {
        $tablesPath = app_path('/Filament/Tables').'/';
        $tablesNamespace = "App\\Filament\\Tables";
        if($this->module){
            $modulePath = module_path($this->module);
            $tablesPath = $modulePath . "/app/Filament/Tables/";
            $tablesNamespace = "Modules\\". $this->module . "\\Filament\\Tables";
        }
        if($this->path){
            $tablesPath = $this->path . "/Filament/Tables/";
            $tablesNamespace = $this->namespace . "\\Filament\\Tables";
        }
        if($this->resource){
            $tablesPath = $this->path . "/Filament/Resources/".$this->resource . "/Tables/";
            $tablesNamespace = $this->namespace ."\\Filament\\Resources\\" . $this->resource . "\\Tables";
        }

        $filename = $tablesPath . $this->name . "Table.php";
        if(File::exists($filename)){
            error("The Table file already exists");
            $overwrite = confirm("Do you want to overwrite the file?", default: false);
            if(!$overwrite){
                return;
            }
        }

        $this->generateStubs(
            from: $this->stubPath . "table.stub",
            to: $filename,
            replacements: [
                'name' => $this->name,
                'namespace' => $tablesNamespace
            ],
            directory: [
                $tablesPath
            ],
        );
    }
}
