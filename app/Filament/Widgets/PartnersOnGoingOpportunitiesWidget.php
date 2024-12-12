<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;

class PartnersOnGoingOpportunitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.partners-on-going-opportunities-widget';

    protected function getViewData(): array
    {
        
        $opportunities = Event::with('slots')->get();

        return [
            'opportunities' => $opportunities,
        ];
    }
}
