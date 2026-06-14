<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Widgets\Widget;

class RecentOpportunitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-opportunities-widget';

    protected function getViewData(): array
    {

        $currentDate = now();

        // [FIX] Show UPCOMING events (start_date in the future), not past ones
        $opportunities = Event::with('slots', 'tags', 'program')
            ->where('start_date', '>=', $currentDate)
            ->where('is_published', true)
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        $tagList = collect();

        foreach ($opportunities as $opportunity) {
            foreach ($opportunity->tags as $tag) {
                if (!$tagList->contains('name', $tag->name)) {
                    $tagList->push((object) ['name' => $tag->name]);
                }
            }
        }

        $tagList = $tagList->sortBy('name');

        // dd($opportunities);

        return [
            'opportunities' => $opportunities,
            'tags' => $tagList,
        ];
    }
}
