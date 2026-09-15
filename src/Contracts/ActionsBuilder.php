<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

abstract class ActionsBuilder
{
    public static function make(): array
    {
        return app(static::class)->actions();
    }

    abstract public function actions(): array;
}
