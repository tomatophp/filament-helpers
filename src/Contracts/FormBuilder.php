<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

use Filament\Forms\Form;

abstract class FormBuilder
{
    public static function make(): Form
    {
        return (new static())->form();
    }

    public abstract function form(): Form;
}
