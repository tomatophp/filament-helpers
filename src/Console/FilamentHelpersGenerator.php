<?php

namespace TomatoPHP\FilamentHelpers\Console;

use Illuminate\Console\Command;
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
            options: ['form', 'table','actions', 'filters'],
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
        }
        else {
            $path = $this->argument('path') ?? confirm('Do you want to specify the path of the class?', default: false);
            if($path){
                $path = text('What is the path you when to generate class in?', required: true);
                $namespace = text('What is the path namespace you when to generate class in?', required: true);
            }
        }

        $generator = new Generator(
            type: $type,
            name: $name,
            module: $module,
            path: $path??null,
            namespace: $namespace??null
        );
        $generator->generate();

        info('Actions created successfully.');

        return 1;
    }
}
