<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

use Filament\Schemas\Schema;

abstract class FormBuilder
{
    public static function make(Schema $schema): Schema
    {
        return app(static::class)->form($schema);
    }

    abstract public function form(Schema $schema): Schema;
}
