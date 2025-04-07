<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\EventAttendee;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TotalVolunteerHoursStats extends BaseWidget
{
    // Add a cluster filter parameter that can be passed from parent component
    public ?int $clusterFilter = null;
    
    public function mount(?int $clusterFilter = null): void
    {
        // If no cluster filter is passed, check if current user is External Partner
        if (!$clusterFilter) {
            $user = Auth::user();
            if ($user && $user->hasRole('External Partner') && $user->cluster_id) {
                $this->clusterFilter = $user->cluster_id;
            }
        } else {
            $this->clusterFilter = $clusterFilter;
        }
    }
    
    protected function getStats(): array
    {
        // Base queries that will be modified based on cluster filter
        $ayalaHoursQuery = EventAttendee::whereHas('attendee', function ($query) {
            $query->where('affiliate_type_id', 1);
            
            // Apply cluster filter if set
            if ($this->clusterFilter) {
                $query->where('cluster_id', $this->clusterFilter);
            }
        })
        ->whereNotNull(['time_in', 'time_out']);
        
        $nonAyalaHoursQuery = EventAttendee::whereHas('attendee', function ($query) {
            $query->where('affiliate_type_id', 2);
            
            // Apply cluster filter if set
            if ($this->clusterFilter) {
                $query->where('cluster_id', $this->clusterFilter);
            }
        })
        ->whereNotNull(['time_in', 'time_out']);
        
        // Get hours with possible cluster filtering
        $ayalaHours = $ayalaHoursQuery->get()->sum(function ($attendance) {
            return $attendance->is_approve ? $attendance->get_totalHrs() : 0;
        });

        $nonAyalaHours = $nonAyalaHoursQuery->get()->sum(function ($attendance) {
            return $attendance->is_approve ? $attendance->get_totalHrs() : 0;
        });

        // Base queries for volunteer counts with possible cluster filtering
        $ayalaVolunteersQuery = User::where('affiliate_type_id', 1)->where('volunteer', 1);
        $nonAyalaVolunteersQuery = User::where('affiliate_type_id', 2)->where('volunteer', 1);
        
        // Apply cluster filter if set
        if ($this->clusterFilter) {
            $ayalaVolunteersQuery->where('cluster_id', $this->clusterFilter);
            $nonAyalaVolunteersQuery->where('cluster_id', $this->clusterFilter);
        }
        
        // Count volunteers
        $ayalaVolunteers = $ayalaVolunteersQuery->count();
        $nonAyalaVolunteers = $nonAyalaVolunteersQuery->count();
        $totalVolunteers = $ayalaVolunteers + $nonAyalaVolunteers;

        // Calculate percentages
        $ayalaPercentage = $totalVolunteers > 0 ? ($ayalaVolunteers / $totalVolunteers) * 100 : 0;
        $nonAyalaPercentage = $totalVolunteers > 0 ? ($nonAyalaVolunteers / $totalVolunteers) * 100 : 0;
        
        // Add cluster-specific title if filtering
        $titlePrefix = $this->clusterFilter ? 'Cluster - ' : '';

        return [
            Stat::make($titlePrefix . 'Total Volunteer Hours', number_format($ayalaHours + $nonAyalaHours) . ' hours')
                ->description('Total Volunteers: ' . number_format($totalVolunteers))
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            Stat::make($titlePrefix . 'Ayala', number_format($ayalaHours) . ' hours')
                ->description(sprintf(
                    '%s volunteers (%s%%)',
                    number_format($ayalaVolunteers),
                    number_format($ayalaPercentage, 1)
                ))
                ->descriptionIcon('heroicon-m-building-office')
                ->chart([2, 4, 6, 8, 10, 12, 14])
                ->color('primary'),

            Stat::make($titlePrefix . 'Non-Ayala', number_format($nonAyalaHours) . ' hours')
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