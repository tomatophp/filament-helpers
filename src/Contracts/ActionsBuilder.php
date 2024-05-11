<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

abstract class ActionsBuilder
{
    public static function make(): array
    {
        return (new static())->actions();
    }

    public abstract function actions(): array;
}
