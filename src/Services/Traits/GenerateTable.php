<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

trait GenerateTable
{
    public function table():void
    {
        $tablesPath = app_path('/Filament/Tables');
        $tablesNamespace = "App\\Filament\\Tables";
        if($this->module){
            $modulePath = module_path($this->module);
            $tablesPath = $modulePath . "app/Filament/Tables/";
            $tablesNamespace = "Modules\\". $this->module . "\\App\\Filament\\Tables";
        }
        if($this->path){
            $tablesPath = $this->path . "/Filament/Tables/";
            $tablesNamespace = $this->namespace . "\\Filament\\Tables";
        }

        $this->generateStubs(
            from: $this->stubPath . "table.stub",
            to: $tablesPath . $this->name . "Table.php",
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
