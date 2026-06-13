<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class QrScanner extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $title = 'QR Scanner';

    public static function shouldRegisterNavigation(): bool
    {
        return ! (bool) auth()->user()?->hasActiveRole('Volunteer');
    }

    protected static string $view = 'filament.pages.qr-scanner';
}
