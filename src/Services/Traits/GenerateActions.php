<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

use Illuminate\Support\Facades\File;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;

trait GenerateActions
{
    public function actions(): void
    {
        $actionsPath = app_path('/Filament/Actions').'/';
        $actionsNamespace = "App\\Filament\\Actions";
        if($this->module){
            $modulePath = module_path($this->module);
            $actionsPath = $modulePath . "/app/Filament/Actions/";
            $actionsNamespace = "Modules\\". $this->module . "\\Filament\\Actions";
        }
        if($this->path){
            $actionsPath = $this->path . "/Filament/Actions/";
            $actionsNamespace = $this->namespace . "\\Filament\\Actions";
        }
        if($this->resource){
            $actionsPath = $this->path . "/Filament/Resources/".$this->resource . "/Actions/";
            $actionsNamespace = $this->namespace ."\\Filament\\Resources\\" . $this->resource . "\\Actions";
        }

        $filename = $actionsPath . $this->name . "Actions.php";
        if(File::exists($filename)){
            error("The Action file already exists");
            $overwrite = confirm("Do you want to overwrite the file?", default: false);
            if(!$overwrite){
                return;
            }
        }
        $this->generateStubs(
            from: $this->stubPath . "actions.stub",
            to: $filename,
            replacements: [
                'name' => $this->name,
                'namespace' => $actionsNamespace
            ],
            directory: [
                $actionsPath
            ],
        );
    }
}
