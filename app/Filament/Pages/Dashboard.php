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

        // Calculate total volunteer hours using database (much faster than PHP loop)
        // TIMESTAMPDIFF(MINUTE, time_in, time_out) gets minutes, divide by 60 for hours
        $totalVolunteerHours = number_format(
            cache()->remember('total_volunteer_hours', 3600, function () {
                return EventAttendee::where('is_approve', true)
                    ->whereNotNull('time_in')
                    ->whereNotNull('time_out')
                    ->selectRaw('SUM(ROUND(TIMESTAMPDIFF(MINUTE, time_in, time_out) / 60, 2)) as total_hours')
                    ->value('total_hours') ?? 0;
            }),
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
