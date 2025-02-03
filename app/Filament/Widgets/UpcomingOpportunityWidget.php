<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;

class UpcomingOpportunityWidget extends Widget
{
    protected static string $view = 'filament.widgets.upcoming-opportunity-widget';

    protected function getViewData(): array
    {
        $opportunities = Event::with('slots')->orderBy('created_at','desc')->get();

        // dd($opportunities);

        return [
            'opportunities' => $opportunities,
        ];
    }
}
