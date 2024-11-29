<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class VolunteerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.volunteer-dashboard';

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

        $totalUpcoming = 3;
        $totalCertificates = 20;
        $totalApprovedHrs = 203.51;
        $totalRemainingHrs = 200;
        $totalCancelled = 0;
        // Stat Overview(End)


        // Upcoming Opportunity(Start)
        $upcomingOpportunities = Event::with('slots')->get();
        // Upcoming Opportunity(End)


        // Recent Opportunity(Start)
        $recentOpportunities = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $tagList = collect();

        foreach ($recentOpportunities as $opportunity) {
            foreach ($opportunity->tags as $tag) {
                if (!$tagList->contains('name', $tag->name)) {
                    $tagList->push((object) ['name' => $tag->name]);
                }
            }
        }

        $tagList = $tagList->sortBy('name');
        // Recent Opportunity(End)


        return [
            // Stat Overview(Start)
            'opportunity' => $opportunity,
            'totalUpcoming' => $totalUpcoming,
            'totalCertificates' => $totalCertificates,
            'totalApprovedHrs' => $totalApprovedHrs,
            'totalRemainingHrs' => $totalRemainingHrs,
            'totalCancelled' => $totalCancelled,
            // Stat Overview(End)


            // Upcoming Opportunity(Start)
            'upcomingOpportunities' => $upcomingOpportunities,
            // Upcoming Opportunity(End)


            // Recent Opportunity(Start)
            'recentOpportunities' => $recentOpportunities,
            'tags' => $tagList,
            // Recent Opportunity(End)
        ];
    }
}
