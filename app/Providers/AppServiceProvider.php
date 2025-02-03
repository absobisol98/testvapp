<?php

namespace App\Providers;

use App\Settings\GeneralSettings;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;

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
        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });

        // # \Opcodes\LogViewer
        LogViewer::auth(function ($request) {
            $role = auth()?->user()?->roles?->first()->name;
            return $role == config('filament-shield.super_admin.name');
        });

        // # Hooks
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn (): View => view('filament.components.panel-footer'),
        );
        
        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::USER_MENU_BEFORE,
        //     fn (): View => view('filament.components.button-website'),
        // );

        FilamentColor::register(function (GeneralSettings $settings) {
            return $settings->site_theme;
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_START,
            fn (): View => view('filament.components.sidebar.user-details'),
        );

        // FilamentView::registerRenderHook(
        //     PanelsRenderHook::SIDEBAR_NAV_START,
        //     fn (): View => view('filament.components.sidebar.sidebar-items'),
        // );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_NAV_END,
            fn (): View => view('filament.components.sidebar.ad-content'),
        );

        FilamentAsset::register([
            Css::make('example-external-stylesheet', 'https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css'),
            Js::make('example-external-script', 'https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js'),
        ]);
        
    }
}
