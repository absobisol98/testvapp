<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\EventAttendee;
use Filament\Widgets\Widget;

class AFIAdminOpportunitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.a-f-i-admin-opportunities-widget';

    protected function getViewData(): array
    {

        $opportunities = Event::with(['slots', 'media'])
            ->where('is_published', true)
            ->whereDate('end_date', '>=', now())
            ->orderBy('created_at', 'Asc')
            ->take(10)
            ->get();
        $volunteer = EventAttendee::where('event_id')->count();

        return [
            'opportunities' => $opportunities,
            'volunteer' => $volunteer,
        ];
    }
}
