<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use App\Models\Event;
use Filament\Resources\Pages\Page;

class ViewVolunteer extends Page
{
    protected static string $resource = VolunteerResource::class;

    protected static string $view = 'filament.resources.volunteer-resource.pages.view-volunteer';

    protected function getViewData(): array
    {
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        $allEvents = Event::with('slots')
            ->whereHas('attendees', function ($query) {
                $query->where('attendee_id', auth()->user()->id);
            })
            ->get();
        $favoriteEvents = Event::with('slots')
            ->whereHas('attendees', function ($query) {
                $query->where('attendee_id', auth()->user()->id);
            })
            ->get();

        $bgImg = 'img/ayala-foundation-bg-2.jpg';

        // dd($favoriteEvents);

        return [
            'opportunity' => $opportunity,
            'allEvents' => $allEvents,
            'favoriteEvents' => $favoriteEvents,
            'bgImg' => $bgImg,
        ];
    }
}
