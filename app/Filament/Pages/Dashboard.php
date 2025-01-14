<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AssignedTasksToday;
use App\Filament\Widgets\CalendarWidget as CalendarWidget;
use App\Filament\Widgets\LatestInquiries as LatestInquiries;
use App\Filament\Widgets\LatestInvoices as LatestInvoices;
use App\Filament\Widgets\Welcome;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BasePage
{
    public function getTitle(): string | Htmlable
    {
        return __('');
    }
    
    protected static string $view = 'filament.pages.dashboard';
}
