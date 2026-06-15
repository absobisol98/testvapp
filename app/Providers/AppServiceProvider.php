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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use App\Services\IcsGeneratorService;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(IcsGeneratorService::class, function ($app) {
            return new IcsGeneratorService();
        });

        // Always redirect to /admin after login — bypass redirect()->intended()
        // which can send users to stale "intended" URLs (volunteer-registration, etc.)
        $this->app->bind(
            \Filament\Http\Responses\Auth\Contracts\LoginResponse::class,
            \App\Http\Responses\Auth\LoginResponse::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(\TomatoPHP\FilamentMediaManager\Models\Folder::class, \App\Policies\MediaPolicy::class);
        Gate::policy(\Visualbuilder\EmailTemplates\Models\EmailTemplate::class, \App\Policies\EmailTemplatePolicy::class);
        Gate::policy(\Visualbuilder\EmailTemplates\Models\EmailTemplateTheme::class, \App\Policies\EmailTemplateThemePolicy::class);
        Gate::policy(\Tapp\FilamentMailLog\Models\MailLog::class, \App\Policies\MailLogPolicy::class);
        Gate::policy(\Datlechin\FilamentMenuBuilder\Models\Menu::class, \App\Policies\MenuPolicy::class);

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
            Js::make('example-external-script', 'https://platform-api.sharethis.com/js/sharethis.js#property=67acd1165a9d7b0012f84b26&product=inline-share-buttons&source=platform'),
        ]);

    }
}
