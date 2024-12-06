<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class AFIAdminDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.a-f-i-admin-dashboard';

    public function getTitle(): string | Htmlable
    {
        return __('');
    }

    protected function getViewData(): array
    {
        // Stat Overview(Start)
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        $totalVolunteers = 56;
        $totalOpportunities = 65;
        $totalOnGoing = 674.51;
        $totalHrsRendered = 324;
        $totalPartners = 23;
        // Stat Overview(End)


        // Opportunities(Start)
        $opportunities = Event::with('slots')->get();
        // Opportunities(End)

        return [
            // Stat Overview(Start)
            'opportunity' => $opportunity,
            'totalVolunteers' => $totalVolunteers,
            'totalOpportunities' => $totalOpportunities,
            'totalOnGoing' => $totalOnGoing,
            'totalHrsRendered' => $totalHrsRendered,
            'totalPartners' => $totalPartners,
            // Stat Overview(End)


            // Opportunities(Start)
            'opportunities' => $opportunities,
            // Opportunities(End)
        ];
    }
}
