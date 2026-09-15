![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-helpers/master/arts/3x1io-tomato-helpers.jpg)

# Filament Helper Classes

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-helpers/version.svg)](https://packagist.org/packages/tomatophp/filament-helpers)
[![License](https://poser.pugx.org/tomatophp/filament-helpers/license.svg)](https://packagist.org/packages/tomatophp/filament-helpers)
[![Downloads](https://poser.pugx.org/tomatophp/filament-helpers/d/total.svg)](https://packagist.org/packages/tomatophp/filament-helpers)

Helper class generator to keep the forms, tables, actions and filters of your Filament app in their own classes.

## Version Compatibility

| Plugin | Filament | Laravel | PHP |
|--------|----------|---------|-----|
| 1.x    | 3.x      | 10.x / 11.x | 8.1+ |
| 5.x    | 5.x      | 12.x / 13.x | 8.2+ |

## Installation

```bash
composer require tomatophp/filament-helpers
```

## Usage

Run the command and pick the type and name; you can generate the class in `app/`, inside a
[laravel-modules](https://github.com/nWidart/laravel-modules) module, or in any source folder, optionally inside a resource folder.

```bash
php artisan filament:helpers
```

Or pass everything on the command line:

```bash
# form, table, actions, filters or all
php artisan filament:helpers User all
php artisan filament:helpers User form --resource=Users
php artisan filament:helpers User table Blog                       # inside the Blog module
php artisan filament:helpers User all "" packages/accounts/src --namespace="TomatoPHP\FilamentAccounts"
```

Existing classes are kept unless you confirm or pass `--force`.

## Using Generated Classes

```php
use App\Filament\Resources\Users\Forms\UserForm;
use Filament\Schemas\Schema;

public static function form(Schema $schema): Schema
{
    return UserForm::make($schema);
}
```

```php
use App\Filament\Resources\Users\Tables\UserTable;
use Filament\Tables\Table;

public static function table(Table $table): Table
{
    return UserTable::make($table);
}
```

```php
use App\Filament\Resources\Users\Actions\UserActions;
use App\Filament\Resources\Users\Filters\UserFilters;

public static function table(Table $table): Table
{
    return $table
        ->recordActions(UserActions::make())
        ->filters(UserFilters::make());
}
```

## Custom Stubs

```bash
php artisan vendor:publish --tag="filament-helpers-stubs"
```

Then set `stub_path` in `config/filament-helpers.php` (publish it with `--tag="filament-helpers-config"`) to `base_path('stubs/filament-helpers')`.

## Testing

```bash
composer test
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
