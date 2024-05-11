<?php

namespace TomatoPHP\FilamentHelpers\Services\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;

trait GenerateForm
{
    public function form(): void
    {
        $formsPath = app_path('/Filament/Forms') .'/';
        $formsNamespace = "App\\Filament\\Forms";
        if($this->module){
            $modulePath = module_path($this->module);
            $formsPath = $modulePath . "/app/Filament/Forms/";
            $formsNamespace = "Modules\\". $this->module . "\\Filament\\Forms";
        }
        if($this->path){
            $formsPath = $this->path . "/Filament/Forms/";
            $formsNamespace = $this->namespace . "\\Filament\\Forms";
        }
        if($this->resource){
            $formsPath = $this->path . "/Filament/Resources/".$this->resource . "/Forms/";
            $formsNamespace = $this->namespace ."\\Filament\\Resources\\" . $this->resource . "\\Forms";
        }

        $filename = $formsPath . $this->name . "Form.php";
        if(File::exists($filename)){
            error("The Form file already exists");
            $overwrite = confirm("Do you want to overwrite the file?", default: false);
            if(!$overwrite){
                return;
            }
        }

        $this->generateStubs(
            from: $this->stubPath . "form.stub",
            to: $filename,
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
