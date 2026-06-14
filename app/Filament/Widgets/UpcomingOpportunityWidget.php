<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Event;
use Filament\Widgets\Widget;

class UpcomingOpportunityWidget extends Widget
{
    protected static string $view = 'filament.widgets.upcoming-opportunity-widget';

    protected function getViewData(): array
    {
        $currentDate = now();
        $user        = auth()->user();

        $opportunities = Event::query()
            ->where('start_date', '>=', $currentDate)
            ->where('is_published', true)
            ->orderBy('start_date', 'asc')
            ->take(9)
            ->get();

        $recentCertificates = Certificate::with('event')
            ->where('attendee_id', $user->id)
            ->latest('issued_at')
            ->take(3)
            ->get();

        $badgeInfo = $user->getBadges();

        return [
            'opportunities'      => $opportunities,
            'recentCertificates' => $recentCertificates,
            'badgeInfo'          => $badgeInfo,
        ];
    }
}
