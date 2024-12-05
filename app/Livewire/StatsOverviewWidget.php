<?php

namespace App\Livewire;

use App\Models\Event;
use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    protected static string $view = 'filament.widgets.stats-overview-widget';

    protected function getViewData(): array
    {
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        $totalUpcoming = 3;
        $totalCertificates = 20;
        $totalApprovedHrs = 203.51;
        $totalRemainingHrs = 200;
        $totalCancelled = 0;

        return [
            'opportunity' => $opportunity,
            'totalUpcoming' => $totalUpcoming,
            'totalCertificates' => $totalCertificates,
            'totalApprovedHrs' => $totalApprovedHrs,
            'totalRemainingHrs' => $totalRemainingHrs,
            'totalCancelled' => $totalCancelled,
        ];
    }
}
