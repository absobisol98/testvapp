<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;

class AFIAdminOpportunitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.a-f-i-admin-opportunities-widget';

    protected function getViewData(): array
    {
        
        $opportunities = Event::with('slots')->get();

        return [
            'opportunities' => $opportunities,
        ];
    }
}
