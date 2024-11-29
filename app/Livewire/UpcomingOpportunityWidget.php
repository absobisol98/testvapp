<?php

namespace App\Livewire;

use App\Models\Event;
use Filament\Widgets\Widget;

class UpcomingOpportunityWidget extends Widget
{
    protected static string $view = 'livewire.upcoming-opportunity-widget';

    protected function getViewData(): array
    {
        $opportunities = Event::with('slots')->get();

        // dd($opportunities);

        return [
            'opportunities' => $opportunities,
        ];
    }
}
