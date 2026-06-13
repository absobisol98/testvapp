<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EmailVerification;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\MenuResource;
use App\Filament\Widgets\Welcome;
use App\Livewire\MyProfileExtended;
use App\Settings\GeneralSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Visualbuilder\EmailTemplates\EmailTemplatesPlugin;
use Tapp\FilamentMailLog\FilamentMailLogPlugin;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->userMenuItems([
                'profile' => MenuItem::make()->icon('heroicon-s-pencil-square')->label('Edit profile')->url(fn (): string => route('filament.admin.resources.users.edit',['record' => auth()->user()->id])),
                // ...
            ])
            ->login(Login::class)
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification(EmailVerification::class)
            ->favicon(fn (GeneralSettings $settings) => Storage::url($settings->site_favicon))
            ->brandName(fn (GeneralSettings $settings) => $settings->brand_name)
            ->brandLogo(fn() => $this->getLogo())
            ->darkMode(false)
            ->sidebarCollapsibleOnDesktop()
            ->brandLogoHeight(fn (GeneralSettings $settings) => $settings->brand_logoHeight)
            ->colors(fn (GeneralSettings $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->navigationGroups([
                Navigation\NavigationGroup::make()
                    ->label('Content') // !! To-Do: lang
                    ->collapsible(false),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.access'))
                    ->collapsible(false),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.settings'))
                    ->collapsed(),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.activities'))
                    ->collapsed(),
            ])

            ->navigationItems([
                Navigation\NavigationItem::make('Log Viewer') // !! To-Do: lang
                    ->visible(fn(): bool => auth()->user()->can('access_log_viewer'))
                    ->url(config('app.url').'/'.config('log-viewer.route_path'), shouldOpenInNewTab: true)
                    ->icon('fluentui-document-bullet-list-multiple-20-o')
                    ->group(__('menu.nav_group.activities'))
                    ->sort(99),

            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\SetActiveRole::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): \Illuminate\Contracts\View\View => view('filament.partials.preloader'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): \Illuminate\Contracts\View\View => view('filament.partials.guided-tour'),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): \Illuminate\Contracts\View\View => view('filament.partials.role-switcher-mount'),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_END,
                fn (): \Illuminate\Contracts\View\View => view('filament.partials.help-button'),
            )
            ->plugins([
                AuthUIEnhancerPlugin::make()
                ->mobileFormPanelPosition('bottom')
                ->showEmptyPanelOnMobile(true)
                ->emptyPanelBackgroundImageOpacity('100%')
                ->emptyPanelBackgroundImageUrl(asset('img/ayala-login-bg-new.png')),
                FilamentMailLogPlugin::make(),
                \TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin::make(),
                FilamentFullCalendarPlugin::make()
                    ->schedulerLicenseKey('')
                    ->selectable(true)
                    ->editable()
                    ->timezone(config('app.timezone'))
                    ->locale(config('app.locale'))
                    ->plugins(['dayGrid','timeGrid'])
                    ->config([]),
               EmailTemplatesPlugin::make(),
//                \TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin::make()
//                    ->allowSubFolders(),
                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: false,
                        shouldRegisterNavigation: false,
                        navigationGroup: 'Settings',
                        hasAvatars: true,
                        slug: 'my-profile'
                    )
                    ->myProfileComponents([
                        'personal_info' => MyProfileExtended::class,
                    ]),
                \Datlechin\FilamentMenuBuilder\FilamentMenuBuilderPlugin::make()
                    ->usingResource(MenuResource::class)
                    ->addMenuPanels([
                        \Datlechin\FilamentMenuBuilder\MenuPanel\StaticMenuPanel::make()
                            ->addMany([
                                'Home' => url('/'),
                                'Blog' => url('/blog'),
                            ])
                            ->description('Default menus')
                            ->collapsed(true)
                            ->collapsible(true)
                            ->paginate(perPage: 5, condition: true)
                            ]),
                FilamentApexChartsPlugin::make()

            ]);
    }


    public function getLogo(): ?string
    {
        if(request()->routeIs('filament.admin.auth.login')) {
            return asset('img/logo-colored.png');
        }else{
             return Storage::url(app(GeneralSettings::class)->brand_logo) ?? null;;
        }

    }
}
