![Screenshot](https://github.com/tomatophp/filament-helpers/blob/master/arts/3x1io-tomato-helpers.jpg)

# Filament Helper Classes

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-helpers/version.svg)](https://packagist.org/packages/tomatophp/filament-helpers)
[![PHP Version Require](http://poser.pugx.org/tomatophp/filament-helpers/require/php)](https://packagist.org/packages/tomatophp/filament-helpers)
[![License](https://poser.pugx.org/tomatophp/filament-helpers/license.svg)](https://packagist.org/packages/tomatophp/filament-helpers)
[![Downloads](https://poser.pugx.org/tomatophp/filament-helpers/d/total.svg)](https://packagist.org/packages/tomatophp/filament-helpers)

Helper Class Generator to manage your forms and table inside your filament app

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

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/laravel-package-generator)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
