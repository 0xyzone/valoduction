<?php

namespace App\Providers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Width;
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
    }
}
