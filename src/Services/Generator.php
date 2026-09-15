<?php

namespace TomatoPHP\FilamentHelpers\Services;

use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Generator
{
    /**
     * The helper classes the generator can create: target folder and class name suffix.
     *
     * @var array<string, array{directory: string, suffix: string}>
     */
    public const TYPES = [
        'form' => ['directory' => 'Forms', 'suffix' => 'Form'],
        'table' => ['directory' => 'Tables', 'suffix' => 'Table'],
        'actions' => ['directory' => 'Actions', 'suffix' => 'Actions'],
        'filters' => ['directory' => 'Filters', 'suffix' => 'Filters'],
    ];

    public function __construct(
        public string $type,
        public string $name,
        public ?string $module = null,
        public ?string $path = null,
        public ?string $namespace = null,
        public ?string $resource = null,
        public bool $force = false,
        public ?Closure $shouldOverwrite = null,
    ) {
        $this->name = Str::studly($name);
    }

    /**
     * Generate the requested class (or every class for the "all" type).
     *
     * @return array<int, string> The files written.
     */
    public function generate(): array
    {
        $types = $this->type === 'all' ? array_keys(static::TYPES) : [$this->type];

        return array_values(array_filter(array_map(
            fn (string $type): ?string => $this->generateType($type),
            $types,
        )));
    }

    public function form(): void
    {
        $this->generateType('form');
    }

    public function table(): void
    {
        $this->generateType('table');
    }

    public function actions(): void
    {
        $this->generateType('actions');
    }

    public function filters(): void
    {
        $this->generateType('filters');
    }

    public function all(): void
    {
        foreach (array_keys(static::TYPES) as $type) {
            $this->generateType($type);
        }
    }

    public function generateType(string $type): ?string
    {
        [$directory, $namespace] = $this->getTarget($type);

        $file = $directory . '/' . $this->name . static::TYPES[$type]['suffix'] . '.php';

        if (File::exists($file) && ! $this->force && ! ($this->shouldOverwrite && ($this->shouldOverwrite)($file))) {
            return null;
        }

        File::ensureDirectoryExists($directory);
        File::put($file, $this->render($type, [
            'name' => $this->name,
            'namespace' => $namespace,
        ]));

        return $file;
    }

    /**
     * The folder and namespace a class of the given type is generated in.
     *
     * @return array{0: string, 1: string}
     */
    public function getTarget(string $type): array
    {
        [$path, $namespace] = $this->getBase();

        if (filled($this->resource)) {
            $path .= '/Resources/' . str_replace('\\', '/', $this->resource);
            $namespace .= '\\Resources\\' . str_replace('/', '\\', $this->resource);
        }

        $directory = static::TYPES[$type]['directory'];

        return ["{$path}/{$directory}", "{$namespace}\\{$directory}"];
    }

    /**
     * The resource folders found in the target Filament folder.
     *
     * @return array<int, string>
     */
    public function getResources(): array
    {
        [$path] = $this->getBase();

        if (! File::isDirectory("{$path}/Resources")) {
            return [];
        }

        return array_map('basename', File::directories("{$path}/Resources"));
    }

    /**
     * The Filament folder of the app, of the module or of the custom path, and its namespace.
     *
     * @return array{0: string, 1: string}
     */
    protected function getBase(): array
    {
        [$path, $namespace] = match (true) {
            filled($this->module) => [
                (function_exists('module_path') ? module_path($this->module) : base_path("Modules/{$this->module}")) . '/app',
                "Modules\\{$this->module}",
            ],
            filled($this->path) => [$this->path, (string) $this->namespace],
            default => [app_path(), app()->getNamespace()],
        };

        return [
            rtrim(str_replace('\\', '/', $path), '/') . '/Filament',
            trim($namespace, '\\') . '\\Filament',
        ];
    }

    /**
     * @param  array<string, string>  $replacements
     */
    protected function render(string $type, array $replacements): string
    {
        $stubPath = rtrim(config('filament-helpers.stub_path') ?: __DIR__ . '/../../stubs', '/\\');
        $stub = File::get("{$stubPath}/{$type}.stub");

        foreach ($replacements as $key => $value) {
            $stub = str_replace(["{{ {$key} }}", "{{{$key}}}"], $value, $stub);
        }

        return $stub;
    }
}
