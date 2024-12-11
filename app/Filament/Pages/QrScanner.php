<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class QrScanner extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $title = 'QR Scanner';

    protected static string $view = 'filament.pages.qr-scanner';
}
