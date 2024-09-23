![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-helpers/master/arts/3x1io-tomato-helpers.jpg)

# Filament Helper Classes

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-helpers/version.svg)](https://packagist.org/packages/tomatophp/filament-helpers)
[![License](https://poser.pugx.org/tomatophp/filament-helpers/license.svg)](https://packagist.org/packages/tomatophp/filament-helpers)
[![Downloads](https://poser.pugx.org/tomatophp/filament-helpers/d/total.svg)](https://packagist.org/packages/tomatophp/filament-helpers)

Helper Class Generator to manage your forms and table inside your filament app

## Screenshots

![Command](https://raw.githubusercontent.com/tomatophp/filament-helpers/master/arts/command.png)

## Installation

```bash
composer require tomatophp/filament-helpers --dev
```

## Using

to generate a new helper class you can use this command

```bash
php artisan filament:helpers
```

and select the type and name, and you can generate the class inside module or on selected path inside your resource.

## Using Generated Class

and you can use the generated class like this

```php
use App\Filament\Resources\AccountResource\Forms\UserForm;

public function form(Form $form): Form
{
    return UserForm::make($form);
}
```

```php
use App\Filament\Resources\AccountResource\Tables\UserTable;

public function form(Table $table): Table
{
    return UserTable::make($table);
}
```

```php
use App\Filament\Resources\AccountResource\Actions\UserActions;

public function table(Table $table): Table
{
    return $table->actions(UserActions::make());
}
```

```php
use App\Filament\Resources\AccountResource\Actions\UserFilters;

public function table(Table $table): Table
{
    return $table->filters(UserFilters::make());
}
```

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
