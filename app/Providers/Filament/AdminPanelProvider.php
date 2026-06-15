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
use Illuminate\Support\HtmlString;
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
            ->homeUrl(fn (): string => url('/admin'))
            ->userMenuItems([
                'profile' => MenuItem::make()->icon('heroicon-s-pencil-square')->label('Edit profile')->url(fn (): string => route('filament.admin.resources.users.edit',['record' => auth()->user()->id])),
                MenuItem::make()
                    ->label('Help & Tour')
                    ->icon('heroicon-o-question-mark-circle')
                    ->url('?replay_tour=1'),
            ])
            ->font('Inter', provider: \Filament\FontProviders\GoogleFontProvider::class)
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
                Navigation\NavigationItem::make('Dashboard')
                    ->icon('heroicon-o-home')
                    ->url(url('/admin'))
                    ->isActiveWhen(fn (): bool => request()->path() === 'admin')
                    ->sort(-2),

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
                PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString(
                    '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">'
                    . '<style>*,body,.fi-body,.fi-sidebar-nav,.fi-topbar,.fi-main{font-family:\'Inter\',system-ui,sans-serif!important;}</style>'
                ),
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => $this->roleNavFilterCss(),
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_NAV_START,
                fn (): \Illuminate\Support\HtmlString => $this->dashboardNavLink(),
            )
            ->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): \Illuminate\Contracts\View\View => view('filament.partials.preloader'),
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
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
            return asset('img/logo-vapp.svg');
        }else{
             return Storage::url(app(GeneralSettings::class)->brand_logo) ?? asset('img/logo-vapp.svg');
        }

    }

    private function dashboardNavLink(): HtmlString
    {
        $user = auth()->user();

        if (!$user || $user->isAdminRole()) {
            return new HtmlString('');
        }

        $url      = url('/admin');
        $isActive = request()->is('admin');
        $linkClass = 'fi-sidebar-item-button group flex w-full items-center gap-x-3 rounded-lg px-2 py-2 text-sm font-medium outline-none transition duration-75 '
            . ($isActive
                ? 'fi-active bg-gray-100 dark:bg-white/5 text-primary-600 dark:text-primary-400'
                : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/5');
        $iconClass = 'fi-sidebar-item-icon h-5 w-5 shrink-0 '
            . ($isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-500 dark:group-hover:text-gray-400');

        return new HtmlString('
<ul class="fi-sidebar-group-items flex flex-col gap-y-1 px-2 pb-1">
  <li class="fi-sidebar-item" data-nav-home="1">
    <a href="' . $url . '" class="' . $linkClass . '">
      <svg class="' . $iconClass . '" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12
                 M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875
                 c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125
                 V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
      </svg>
      <span class="fi-sidebar-item-label flex-1 truncate">Dashboard</span>
    </a>
  </li>
</ul>
');
    }

    private function roleNavFilterCss(): HtmlString
    {
        $user = auth()->user();

        if (!$user || $user->isAdminRole()) {
            return new HtmlString('');
        }

        // Build allowed href patterns per role
        $allowed = match ($user->activeRole()) {
            'Facilitator'     => ['/admin/events', '/admin/volunteers', '/admin/certificates', '/admin/qr-scanner'],
            'External Partner'=> ['/admin/events', '/admin/volunteers', '/admin/certificates', '/admin/business-units', '/admin/qr-scanner'],
            default           => ['/admin/events', '/admin/volunteers', '/admin/certificates'], // Volunteer
        };

        // Show only groups that contain at least one allowed link
        $showGroups = implode(",\n", array_map(
            fn ($p) => ".fi-sidebar-group:has(a[href*=\"{$p}\"])",
            $allowed
        ));

        // Within visible groups, hide individual items not in the allowed list
        $notHas = implode('', array_map(
            fn ($p) => ":not(:has(a[href*=\"{$p}\"]))",
            $allowed
        ));

        $css = <<<CSS
<style>
/* Role-based navigation filter ({$user->activeRole()}) */
.fi-sidebar-group { display: none !important; }
{$showGroups} { display: flex !important; }
.fi-sidebar-item:not([data-nav-home]){$notHas} { display: none !important; }
</style>
CSS;

        return new HtmlString($css);
    }
}
