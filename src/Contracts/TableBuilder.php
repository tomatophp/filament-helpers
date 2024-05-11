<?php

namespace TomatoPHP\FilamentHelpers\Contracts;



use Filament\Tables\Table;

abstract class TableBuilder
{
    public static function make(Table $table): Table
    {
        return (new static())->table($table);
    }

    public abstract function table(Table $table): Table;
}
