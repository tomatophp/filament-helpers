<?php

namespace TomatoPHP\FilamentHelpers\Console;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use TomatoPHP\FilamentHelpers\Services\Generator;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class FilamentHelpersGenerator extends Command
{
    protected $signature = 'filament:helpers
        {name? : The class name, e.g. User}
        {type? : form, table, actions, filters or all}
        {module? : Generate inside this nwidart/laravel-modules module}
        {path? : Generate inside this source folder instead of app/}
        {--namespace= : The namespace of the source folder given as path}
        {--resource= : Generate inside this resource folder, e.g. Users}
        {--force : Overwrite classes that already exist}';

    protected $description = 'Create a new Filament Helpers class in any path';

    public function handle(): int
    {
        $types = [...array_keys(Generator::TYPES), 'all'];

        $type = $this->argument('type') ?? select(
            label: 'Select the type of the class',
            options: [
                'form' => 'Form',
                'table' => 'Table',
                'actions' => 'Actions',
                'filters' => 'Filters',
                'all' => 'All',
            ],
            required: true,
        );

        if (! in_array($type, $types, true)) {
            $this->components->error("Unknown type [{$type}], use one of: " . implode(', ', $types) . '.');

            return self::FAILURE;
        }

        $name = $this->argument('name') ?? text('What is the name of the class?', required: true);
        $module = $this->argument('module');
        $path = $this->argument('path');
        $namespace = $this->option('namespace');

        if (blank($module) && blank($path) && $this->input->isInteractive()) {
            if (class_exists(Module::class) && confirm('Does this class belong to a module?', default: false)) {
                $module = select(
                    label: 'What is the name of the module?',
                    options: array_keys(Module::all()),
                    required: true,
                );
            } elseif (confirm('Do you want to specify the path of the class?', default: false)) {
                $path = text(
                    label: 'What is the path you want to generate the class in?',
                    required: true,
                    hint: 'e.g. /Users/tomatophp/Sites/filament/vendor/tomatophp/filament-accounts/src',
                );
            }
        }

        if (filled($module) && ! class_exists(Module::class)) {
            $this->components->error('Generating inside a module needs nwidart/laravel-modules.');

            return self::FAILURE;
        }

        if (filled($path) && blank($namespace)) {
            if (! $this->input->isInteractive()) {
                $this->components->error('Pass --namespace with the namespace of the path.');

                return self::FAILURE;
            }

            $namespace = text(
                label: 'What is the namespace of that path?',
                required: true,
                hint: 'e.g. TomatoPHP\FilamentAccounts',
            );
        }

        $generator = new Generator(
            type: $type,
            name: $name,
            module: filled($module) ? $module : null,
            path: filled($path) ? $path : null,
            namespace: $namespace,
            resource: $this->option('resource'),
            force: (bool) $this->option('force'),
            shouldOverwrite: fn (string $file): bool => $this->input->isInteractive()
                && confirm("The file [{$file}] already exists. Do you want to overwrite it?", default: false),
        );

        if (blank($generator->resource) && $this->input->isInteractive()) {
            $resources = $generator->getResources();

            if ($resources !== [] && confirm('Does this class belong to a resource?', default: false)) {
                $generator->resource = select(
                    label: 'What is the resource name?',
                    options: $resources,
                    required: true,
                );
            }
        }

        foreach ($generator->generate() as $file) {
            $this->components->info("Class [{$file}] created successfully.");
        }

        return self::SUCCESS;
    }
}
