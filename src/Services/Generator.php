<?php

namespace TomatoPHP\FilamentHelpers\Services;
use Illuminate\Support\Str;
use TomatoPHP\ConsoleHelpers\Traits\HandleStub;

class Generator
{
    use HandleStub;
    use Traits\GenerateActions;
    use Traits\GenerateFilters;
    use Traits\GenerateForm;
    use Traits\GenerateTable;

    private string $stubPath = __DIR__ . '/../../stubs/';

    public function __construct(
        public string $type,
        public string $name,
        public ?string $module=null,
        public ?string $path=null,
        public ?string $namespace=null,
        public ?string $resource=null,
    )
    {
        if(config('filament-helpers.stub_path')){
            $this->stubPath = config('filament-helpers.stub_path');
        }

        $this->name = Str::studly($this->name);
    }

    /**
     * @return void
     */
    public function generate(): void
    {
        match($this->type){
            'form' => $this->form(),
            'table' => $this->table(),
            'actions' => $this->actions(),
            'filters' => $this->filters(),
            'all' => $this->all()
        };
    }

    public function all():void
    {
        $this->form();
        $this->table();
        $this->actions();
        $this->filters();
    }
}
