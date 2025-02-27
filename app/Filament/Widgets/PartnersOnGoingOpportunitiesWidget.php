<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;

class PartnersOnGoingOpportunitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.partners-on-going-opportunities-widget';

    protected function getViewData(): array
    {

        $currentDate = now();

        $opportunities = Event::query()
        ->where('start_date', '>=', $currentDate)
        ->orderBy('start_date', 'asc')
        ->take(10)
        ->get();


        return [
            'opportunities' => $opportunities,
        ];
    }
}
