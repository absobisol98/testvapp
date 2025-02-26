<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\EventAttendee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TotalVolunteerHoursStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Calculate hours
        $ayalaHours = EventAttendee::whereHas('attendee', function ($query) {
            $query->where('affiliate_type_id', 1);
        })
        ->whereNotNull(['time_in', 'time_out'])
        ->get()
        ->sum(function ($attendance) {
            return $attendance->get_totalHrs();
        });

        $nonAyalaHours = EventAttendee::whereHas('attendee', function ($query) {
            $query->where('affiliate_type_id', 2);
        })
        ->whereNotNull(['time_in', 'time_out'])
        ->get()
        ->sum(function ($attendance) {
            return $attendance->get_totalHrs();
        });

        // Get volunteer counts
        $ayalaVolunteers = User::where('affiliate_type_id', 1)->count();
        $nonAyalaVolunteers = User::where('affiliate_type_id', 2)->count();
        $totalVolunteers = $ayalaVolunteers + $nonAyalaVolunteers;

        // Calculate percentages
        $ayalaPercentage = $totalVolunteers > 0 ? ($ayalaVolunteers / $totalVolunteers) * 100 : 0;
        $nonAyalaPercentage = $totalVolunteers > 0 ? ($nonAyalaVolunteers / $totalVolunteers) * 100 : 0;

        return [
            Stat::make('Total Volunteer Hours', number_format($ayalaHours + $nonAyalaHours) . ' hours')
                ->description('Total Volunteers: ' . number_format($totalVolunteers))
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make('Ayala', number_format($ayalaHours) . ' hours')
                ->description(sprintf(
                    '%s volunteers (%s%%)',
                    number_format($ayalaVolunteers),
                    number_format($ayalaPercentage, 1)
                ))
                ->descriptionIcon('heroicon-m-building-office')
                ->chart([2, 4, 6, 8, 10, 12, 14])
                ->color('primary'),

            Stat::make('Non-Ayala', number_format($nonAyalaHours) . ' hours')
                ->description(sprintf(
                    '%s volunteers (%s%%)',
                    number_format($nonAyalaVolunteers),
                    number_format($nonAyalaPercentage, 1)
                ))
                ->descriptionIcon('heroicon-m-users')
                ->chart([1, 3, 5, 7, 9, 11, 13])
                ->color('warning'),
        ];
    }
}
