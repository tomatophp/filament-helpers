<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

abstract class FiltersBuilder
{
    public static function make(): array
    {
        return app(static::class)->filters();
    }

    abstract public function filters(): array;
}
