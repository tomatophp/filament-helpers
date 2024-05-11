<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

trait GenerateActions
{
    public function actions(): void
    {
        $actionsPath = app_path('/Filament/Actions');
        $actionsNamespace = "App\\Filament\\Actions";
        if($this->module){
            $modulePath = module_path($this->module);
            $actionsPath = $modulePath . "app/Filament/Actions/";
            $actionsNamespace = "Modules\\". $this->module . "\\App\\Filament\\Actions";
        }
        if($this->path){
            $actionsPath = $this->path . "/Filament/Actions/";
            $actionsNamespace = $this->namespace . "\\Filament\\Actions";
        }

        $this->generateStubs(
            from: $this->stubPath . "actions.stub",
            to: $actionsPath . $this->name . "Actions.php",
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
