<?php

namespace TomatoPHP\FilamentHelpers\Contracts;



use Filament\Tables\Table;

abstract class TableBuilder
{
    public static function make(): Table
    {
        return (new static())->table();
    }

    public abstract function table(): Table;
}
