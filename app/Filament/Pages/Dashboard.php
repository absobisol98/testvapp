<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use Filament\Pages\Dashboard as BasePage;
use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends BasePage
{
    protected static string $view = 'dashboard';

    public function getTitle(): string | Htmlable
    {
        return __('');
    }

    public function getViewData(): array
    {
        $user = auth()->user();

        $totalVolunteers = User::role('Volunteer')->count();

        $totalVolunteerHours = number_format(
            (float) EventAttendee::where('is_approve', true)
                ->get()
                ->sum(fn ($a) => $a->get_totalHrs()),
            1
        );

        $opportunities = Event::with(['slots', 'media', 'program', 'tags'])
            ->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->orderBy('start_date', 'asc')
            ->take(6)
            ->get();

        return [
            'volunteer_name'        => $user->name,
            'total_volunteers'      => number_format($totalVolunteers),
            'total_volunteer_hours' => $totalVolunteerHours,
            'opportunities'         => $opportunities,
        ];
    }
}
