<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

use Filament\Tables\Table;

abstract class TableBuilder
{
    public static function make(Table $table): Table
    {
        return app(static::class)->table($table);
    }

    abstract public function table(Table $table): Table;
}
