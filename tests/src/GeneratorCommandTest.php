<?php

use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use TomatoPHP\FilamentHelpers\Contracts\ActionsBuilder;
use TomatoPHP\FilamentHelpers\Contracts\FiltersBuilder;
use TomatoPHP\FilamentHelpers\Contracts\FormBuilder;
use TomatoPHP\FilamentHelpers\Contracts\TableBuilder;
use TomatoPHP\FilamentHelpers\Services\Generator;
use TomatoPHP\FilamentHelpers\Tests\Fixtures\TableComponent;

if (! function_exists('module_path')) {
    function module_path(string $name, string $path = ''): string
    {
        return base_path('Modules/' . $name . ($path !== '' ? '/' . $path : ''));
    }
}

beforeEach(function () {
    $this->directory = str_replace('\\', '/', sys_get_temp_dir()) . '/filament-helpers-' . Str::random(8);
    $this->namespace = 'Generated' . Str::random(8);
});

afterEach(function () {
    File::deleteDirectory($this->directory);
});

it('generates every helper class into a custom path and exits successfully', function () {
    $this->artisan('filament:helpers', [
        'name' => 'user',
        'type' => 'all',
        'path' => $this->directory,
        '--namespace' => $this->namespace,
        '--no-interaction' => true,
    ])->assertSuccessful();

    foreach (['Forms/UserForm.php', 'Tables/UserTable.php', 'Actions/UserActions.php', 'Filters/UserFilters.php'] as $file) {
        expect(File::exists("{$this->directory}/Filament/{$file}"))->toBeTrue();
    }
});

it('generates classes that work with Filament 5', function () {
    $this->artisan('filament:helpers', [
        'name' => 'user',
        'type' => 'all',
        'path' => $this->directory,
        '--namespace' => $this->namespace,
        '--no-interaction' => true,
    ])->assertSuccessful();

    foreach (File::allFiles($this->directory) as $file) {
        require_once $file->getPathname();
    }

    $base = "{$this->namespace}\\Filament";
    $form = "{$base}\\Forms\\UserForm";
    $table = "{$base}\\Tables\\UserTable";
    $actions = "{$base}\\Actions\\UserActions";
    $filters = "{$base}\\Filters\\UserFilters";

    expect(is_subclass_of($form, FormBuilder::class))->toBeTrue()
        ->and(is_subclass_of($table, TableBuilder::class))->toBeTrue()
        ->and(is_subclass_of($actions, ActionsBuilder::class))->toBeTrue()
        ->and(is_subclass_of($filters, FiltersBuilder::class))->toBeTrue()
        ->and($form::make(Schema::make()))->toBeInstanceOf(Schema::class)
        ->and($actions::make())->toBe([])
        ->and($filters::make())->toBe([]);

    $configured = $table::make(Table::make(new TableComponent));

    expect(collect($configured->getRecordActions())->first())->toBeInstanceOf(EditAction::class)
        ->and(collect($configured->getToolbarActions())->first())->toBeInstanceOf(BulkActionGroup::class);
});

it('generates into a resource folder of a custom path', function () {
    $this->artisan('filament:helpers', [
        'name' => 'post',
        'type' => 'form',
        'path' => $this->directory,
        '--namespace' => $this->namespace,
        '--resource' => 'Posts',
        '--no-interaction' => true,
    ])->assertSuccessful();

    $file = "{$this->directory}/Filament/Resources/Posts/Forms/PostForm.php";

    expect(File::get($file))->toContain("namespace {$this->namespace}\\Filament\\Resources\\Posts\\Forms;");
});

it('places module resource classes inside the module', function () {
    $generator = new Generator(type: 'form', name: 'post', module: 'Blog', resource: 'Posts');

    [$directory, $namespace] = $generator->getTarget('form');

    expect($directory)->toBe(str_replace('\\', '/', module_path('Blog')) . '/app/Filament/Resources/Posts/Forms')
        ->and($namespace)->toBe('Modules\\Blog\\Filament\\Resources\\Posts\\Forms');
});

it('generates into the app folder by default', function () {
    expect(File::isDirectory(app_path('Filament')))->toBeFalse();

    $this->artisan('filament:helpers', [
        'name' => 'invoice',
        'type' => 'actions',
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(File::get(app_path('Filament/Actions/InvoiceActions.php')))->toContain('namespace App\Filament\Actions;');

    File::deleteDirectory(app_path('Filament'));
});

it('keeps an existing class unless forced', function () {
    $arguments = [
        'name' => 'user',
        'type' => 'form',
        'path' => $this->directory,
        '--namespace' => $this->namespace,
        '--no-interaction' => true,
    ];
    $file = "{$this->directory}/Filament/Forms/UserForm.php";

    $this->artisan('filament:helpers', $arguments)->assertSuccessful();
    File::put($file, 'custom');

    $this->artisan('filament:helpers', $arguments)->assertSuccessful();
    expect(File::get($file))->toBe('custom');

    $this->artisan('filament:helpers', [...$arguments, '--force' => true])->assertSuccessful();
    expect(File::get($file))->toContain('class UserForm extends FormBuilder');
});

it('uses the stubs from the configured stub path', function () {
    File::ensureDirectoryExists("{$this->directory}/stubs");
    File::put("{$this->directory}/stubs/form.stub", 'custom {{ name }} in {{namespace}}');
    config()->set('filament-helpers.stub_path', "{$this->directory}/stubs");

    $this->artisan('filament:helpers', [
        'name' => 'user',
        'type' => 'form',
        'path' => "{$this->directory}/out",
        '--namespace' => $this->namespace,
        '--no-interaction' => true,
    ])->assertSuccessful();

    expect(File::get("{$this->directory}/out/Filament/Forms/UserForm.php"))
        ->toBe("custom User in {$this->namespace}\\Filament\\Forms");
});

it('fails clearly when a module is requested without nwidart/laravel-modules', function () {
    $this->artisan('filament:helpers', [
        'name' => 'post',
        'type' => 'form',
        'module' => 'Blog',
        '--no-interaction' => true,
    ])
        ->expectsOutputToContain('nwidart/laravel-modules')
        ->assertFailed();
});

it('fails for an unknown type', function () {
    $this->artisan('filament:helpers', [
        'name' => 'post',
        'type' => 'widget',
        '--no-interaction' => true,
    ])->assertFailed();
});
