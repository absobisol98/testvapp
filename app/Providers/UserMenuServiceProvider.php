<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\ServiceProvider;

class UserMenuServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Filament::serving(function () {
            // Only show role toggle to users who can switch roles
            if (auth()->user() && auth()->user()->canSwitchRoles()) {
                Filament::registerUserMenuItems([
                    UserMenuItem::make()
                        ->label(fn () => auth()->user()->isInVolunteerMode()
                            ? 'Switch to Admin View'
                            : 'Switch to Volunteer View')
                        ->url(fn () => auth()->user()->isInVolunteerMode()
                            ? '/switch-role/admin'
                            : '/switch-role/volunteer')
                        ->icon('heroicon-o-arrows-right-left')
                        ->sort(5)
                ]);
            }
        });
    }

    public function register(): void
    {
        //
    }
}
