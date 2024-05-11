<?php

namespace TomatoPHP\FilamentHelpers\Contracts;

use Filament\Forms\Form;

abstract class FormBuilder
{
    public static function make(Form $form): Form
    {
        return (new static())->form($form);
    }

    public abstract function form(Form $form): Form;
}
