<?php

namespace TomatoPHP\FilamentHelpers\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentHelpers\Services\Generator;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\info;

class FilamentHelpersGenerator extends Command
{
    protected $signature = 'filament:helpers {name?} {type?} {module?} {path?}';

    protected $description = 'Create a new Filament Helpers class in any path';

    public function handle(): int
    {
        $type = $this->argument('type') ?? select(
            label:'Select the type of the class',
            options: [
                'form' => "Form",
                'table' => "Table",
                'actions' => "Actions",
                'filters' => "Filters",
                'all' => "All",
            ],
            required: true
        );
        $name = $this->argument('name') ?? text('What is the name of the class?', required: true);
        $module = $this->argument('module') ?? confirm('Does this class belong to a module?', default: false);
        if($module){
            $modules = Module::all();
            $module = select(
                label:'What is the name of the module?',
                options: $modules,
                required: true
            );

            $moduleResourcesPath = module_path($module) .'/app/Filament/Resources';
            $getResourcesList = collect(File::directories($moduleResourcesPath));

            if(count($getResourcesList)){
                $resource = select(
                    label:'What is the resource name of the module?',
                    options: $getResourcesList->map(function($item){
                        return basename($item);
                    })->toArray()
                );
            }
        }
        else {
            $path = $this->argument('path') ?? confirm(
                label:'Do you want to specify the path of the class?',
                default: false

            );
            if($path){
                $path = text(
                    label:'What is the path you when to generate class in?',
                    required: true,
                    hint: 'e.g. /Users/tomatophp/Sites/filament/vendor/tomatophp/filament-accounts/src'
                );
                $namespace = text(
                    label:'What is the your app path namespace you when to generate class in?',
                    required: true,
                    hint: 'e.g. TomatoPHP\FilamentAccounts'
                );

                $pathResources = $path .'/Filament/Resources';
                $getResourcesList = collect(File::directories($pathResources));

                if(count($getResourcesList)){
                    $resource = select(
                        label:'What is the resource name of the path?',
                        options: $getResourcesList->map(function($item){
                            return basename($item);
                        })->toArray()
                    );
                }

            }
        }

        $generator = new Generator(
            type: $type,
            name: $name,
            module: $module,
            path: $path??null,
            namespace: $namespace??null,
            resource: $resource??null
        );
        $generator->generate();

        info('Class created successfully.');

        return 1;
    }
}
