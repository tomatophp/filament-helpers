<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('registers the generator command and the config', function () {
    expect(Artisan::all())->toHaveKey('filament:helpers')
        ->and(config('filament-helpers'))->toHaveKey('stub_path');
});

it('publishes the stubs', function () {
    $this->artisan('vendor:publish', ['--tag' => 'filament-helpers-stubs'])->assertSuccessful();

    expect(File::exists(base_path('stubs/filament-helpers/form.stub')))->toBeTrue();

    File::deleteDirectory(base_path('stubs/filament-helpers'));
});
