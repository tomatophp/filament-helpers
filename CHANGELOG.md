# Changelog

## v5.0.0

- Filament 5, Laravel 12 and 13, PHP 8.2+.
- Stubs generate Filament 5 code: `Schema` forms, `recordActions()` / `toolbarActions()` with `Filament\Actions`.
- Fixed module resource classes being written to `/Filament/Resources/...` instead of the module folder.
- The command exits with success, reads the module and path arguments instead of asking again,
  and fails with a clear message when modules are requested without nwidart/laravel-modules.
- New `--namespace`, `--resource` and `--force` options; `filament-helpers-stubs` publish tag.
- Dropped the `tomatophp/console-helpers` dependency.
