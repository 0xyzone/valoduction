<?php

namespace App\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        CreateAction::configureUsing(
            fn (CreateAction $action): CreateAction => $action
                ->slideOver()
                ->modalWidth(Width::SevenExtraLarge)
        );

        EditAction::configureUsing(
            fn (EditAction $action): EditAction => $action
                ->slideOver()
                ->modalWidth(Width::SevenExtraLarge)
        );

        ViewAction::configureUsing(
            fn (ViewAction $action): ViewAction => $action
                ->slideOver()
                ->modalWidth(Width::SevenExtraLarge)
        );

        ColorPicker::macro('withSwatches', function (?array $swatches = null): ColorPicker {
            /** @var ColorPicker $this */
            return $this->belowContent(
                fn (ColorPicker $component): View => view('filament.components.color-swatches', [
                    'statePath' => $component->getStatePath(),
                    'swatches' => $swatches,
                ])
            );
        });
    }
}
