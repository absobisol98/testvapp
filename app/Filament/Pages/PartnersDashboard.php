<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class PartnersDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.partners-dashboard';

    public function getTitle(): string | Htmlable
    {
        return __('');
    }

    protected function getViewData(): array
    {
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        $totalVolunteers = 453;
        $totalOpportunities = 56;
        $totalOnGoing = 568.51;
        $totalHrsRendered = 345;
        $totalFacilitators = 6;

        return [
            'opportunity' => $opportunity,
            'totalVolunteers' => $totalVolunteers,
            'totalOpportunities' => $totalOpportunities,
            'totalOnGoing' => $totalOnGoing,
            'totalHrsRendered' => $totalHrsRendered,
            'totalFacilitators' => $totalFacilitators,
        ];
    }
}
