<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

use Illuminate\Support\Str;

trait GenerateForm
{
    public function form(): void
    {
        $formsPath = app_path('/Filament/Forms');
        $formsNamespace = "App\\Filament\\Forms";
        if($this->module){
            $modulePath = module_path($this->module);
            $formsPath = $modulePath . "app/Filament/Forms/";
            $formsNamespace = "Modules\\". $this->module . "\\App\\Filament\\Forms";
        }
        if($this->path){
            $formsPath = $this->path . "/Filament/Forms/";
            $formsNamespace = $this->namespace . "\\Filament\\Forms";
        }

        $this->generateStubs(
            from: $this->stubPath . "form.stub",
            to: $formsPath . $this->name . "Form.php",
            replacements: [
                'name' => $this->name,
                'namespace' => $formsNamespace
            ],
            directory: [
                $formsPath
            ],
        );
    }
}
