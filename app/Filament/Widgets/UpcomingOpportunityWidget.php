<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;

class UpcomingOpportunityWidget extends Widget
{
    protected static string $view = 'filament.widgets.upcoming-opportunity-widget';

    protected function getViewData(): array
    {
        $currentDate = now();

        $opportunities = Event::query()
        ->where('start_date', '>=', $currentDate)
        ->orderBy('start_date', 'asc')
        ->get();

        $Recentopportunities = Event::with('slots')->orderBy('created_at','desc')->get();

        // dd($opportunities);

        return [
            'opportunities' => $opportunities,
            'Recentopportunities' => $Recentopportunities
        ];
    }
}
