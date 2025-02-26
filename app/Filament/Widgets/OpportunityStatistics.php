<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OpportunityStatistics extends BaseWidget
{
    protected function getStats(): array
    {
        // Get total volunteers
        $totalVolunteers = EventAttendee::distinct('attendee_id')->count('attendee_id');

        // Get Ayala vs Non-Ayala breakdown
        $volunteersByType = EventAttendee::query()
            ->join('users', 'users.id', '=', 'event_attendees.attendee_id')
            ->select('users.affiliate_type_id', DB::raw('COUNT(DISTINCT event_attendees.attendee_id) as count'))
            ->groupBy('users.affiliate_type_id')
            ->get()
            ->pluck('count', 'affiliate_type_id')
            ->toArray();

        $ayalaCount = $volunteersByType[1] ?? 0;
        $nonAyalaCount = $volunteersByType[2] ?? 0;

        // Updated Business Unit breakdown to use only event_companies
        $businessUnitBreakdown = EventAttendee::query()
            ->join('users', 'users.id', '=', 'event_attendees.attendee_id')
            ->join('events', 'events.id', '=', 'event_attendees.event_id')
            ->join('event_companies', 'events.id', '=', 'event_companies.event_id')
            ->join('companies', 'companies.id', '=', 'event_companies.company_id')
            ->where('users.affiliate_type_id', 1)
            ->select(
                'companies.name',
                DB::raw('COUNT(DISTINCT event_attendees.attendee_id) as count')
            )
            ->groupBy('companies.id', 'companies.name')
            ->orderByDesc('count')
            ->get();

        // Calculate total volunteer hours
        $totalHours = EventAttendee::whereNotNull(['time_in', 'time_out'])
            ->get()
            ->sum(fn($attendance) => $attendance->get_totalHrs());

        return [
            Stat::make('Total Volunteers', number_format($totalVolunteers))
                ->description(sprintf(
                    'Ayala: %s (%s%%) | Non-Ayala: %s (%s%%)',
                    number_format($ayalaCount),
                    number_format(($ayalaCount / ($totalVolunteers ?: 1)) * 100, 1),
                    number_format($nonAyalaCount),
                    number_format(($nonAyalaCount / ($totalVolunteers ?: 1)) * 100, 1)
                ))
                ->descriptionIcon('heroicon-m-users')
                ->chart([$ayalaCount, $nonAyalaCount])
                ->color('success'),

            Stat::make('Ayala Business Units', $businessUnitBreakdown->take(3)->pluck('name')->join(', '))
                ->description(sprintf(
                    'Top 3 of %d Business Units',
                    $businessUnitBreakdown->count()
                ))
                ->descriptionIcon('heroicon-m-building-office')
                ->chart($businessUnitBreakdown->take(5)->pluck('count')->toArray())
                ->color('primary'),

            Stat::make('Total Volunteer Hours', number_format($totalHours, 1) . ' hours')
                ->description(sprintf(
                    'Average: %s hrs/volunteer',
                    number_format($totalHours / ($totalVolunteers ?: 1), 1)
                ))
                ->descriptionIcon('heroicon-m-clock')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),
        ];
    }
}
