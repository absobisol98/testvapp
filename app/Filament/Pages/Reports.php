<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\VolunteerSignupPerMonth;
use App\Filament\Widgets\VolunteerUsageWidgetByDepartment;
use App\Filament\Widgets\VolunteerUsageWidgetByProgram;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventType;
use App\Models\Program;
use App\Models\User;
use App\Models\Company;
use App\Models\AffiliateType;
use BezhanSalleh\FilamentShield\Support\Utils;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Reports extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';
    public $widgetData = array();
    protected static ?int $contentHeight = 300; //px

    public function mount() :void {
        // Check if user is an External Partner
        $user = Auth::user();
        $isExternalPartner = $user->hasRole('External Partner');
        $isAyalaSuperAdmin = $user->hasRole('Ayala Super Admin');
        $clusterFilter = $isExternalPartner && $user->cluster_id ? $user->cluster_id : null;

        // Filter the companies and events for external partners
        if ($isExternalPartner && $clusterFilter) {
            // Filter companies for export dropdown
            view()->share('filteredCompanies', Company::where('cluster_id', $clusterFilter)->orderBy('name')->get());
            
            // Filter events for export dropdown
            view()->share('filteredEvents', Event::whereHas('created_by_user', function($query) use ($clusterFilter) {
                $query->where('cluster_id', $clusterFilter);
            })->orWhere('event_type_id', 4)
              ->orWhere('event_type_id', 1)
              ->orderBy('title')->get());
        } else {
            view()->share('filteredCompanies', Company::orderBy('name')->get());
            view()->share('filteredEvents', Event::orderBy('title')->get());
        }
        
        view()->share('filteredAffiliations', AffiliateType::orderBy('name')->get());
        
        $progNames = array();
        $count = array();
        $overall_hrs = 0;
        $programs = Program::all();
        
        foreach ($programs as $program){
            $tot_hrs = 0;
            if(!empty($program->events)){
                foreach($program->events as $event){
                    if(!empty($event->attendees)){
                        foreach ($event->attendees as $attendee){
                            // Skip attendees not in external partner's cluster
                            if ($isExternalPartner && $clusterFilter) {
                                $volunteer = $attendee->attendee;
                                if (!$volunteer || $volunteer->cluster_id != $clusterFilter) {
                                    continue; // Skip this attendee
                                }
                            }
                            
                            if($attendee->time_in && $attendee->time_out){
                                if($attendee->is_approve){
                                    $tot_hrs += $attendee->get_totalHrs();
                                }
                            }
                        }
                    }
                }
            }
            if($tot_hrs > 0){
                $single_count = (double)number_format($tot_hrs,1);
                array_push($progNames, $program->name .': '. $single_count .' Hrs');
                array_push($count,$single_count);
            }
            $overall_hrs += $tot_hrs;
        }

        $evntNames = array();
        $evntcount = array();
        $evntoverall_hrs = 0;
        $evnts = EventType::all();
        
        foreach ($evnts as $evnt){
            $evnttot_hrs = 0;
            if(!empty($evnt->events)){
                foreach($evnt->events as $event){
                    if(!empty($event->attendees)){
                        foreach ($event->attendees as $attendee){
                            // Skip attendees not in external partner's cluster
                            if ($isExternalPartner && $clusterFilter) {
                                $volunteer = $attendee->attendee;
                                if (!$volunteer || $volunteer->cluster_id != $clusterFilter) {
                                    continue; // Skip this attendee
                                }
                            }
                            
                            if($attendee->is_approve){
                                $evnttot_hrs += $attendee->get_totalHrs();
                            }
                        }
                    }
                }
            }
            if($evnttot_hrs > 0){
                $evnt_single_count = (double)number_format($evnttot_hrs,1);
                array_push($evntNames, $evnt->name .': '. $evnt_single_count .' Hrs');
                array_push($evntcount,$evnt_single_count);
            }
            $evntoverall_hrs += $evnttot_hrs;
        }

        // Get age distribution data
        $ageDistribution = $this->getAgeDistribution($clusterFilter);

        $this->widgetData = [
            'widgetByProgram' => [
                'progNames' => $progNames,
                'count' => $count,
                'overall_hrs' => number_format($overall_hrs,1),
            ],

            'widgetByDepartment' => [
                'progNames' => $evntNames,
                'count' => $evntcount,
                'overall_hrs' => number_format($evntoverall_hrs,1),
            ],
            
            'clusterFilter' => $clusterFilter,
            'isExternalPartner' => $isExternalPartner,
            'ageDistribution' => $ageDistribution,
        ];
        
        // Share data with Livewire components
        view()->share('clusterFilter', $clusterFilter);
        view()->share('isExternalPartner', $isExternalPartner);
    }

    /**
     * Get age distribution of volunteers
     * 
     * @param int|null $clusterFilter
     * @return array
     */
    private function getAgeDistribution(?int $clusterFilter = null): array
    {
        $query = User::whereNotNull('age_range')
            ->where('volunteer', 1);
            
        // Apply cluster filter for external partners
        if ($clusterFilter) {
            $query->where('cluster_id', $clusterFilter);
        }
        
        $ageRanges = ['10-17', '18-24', '25-34', '35-44', '45-54', '55-64', '65+'];
        $distribution = [];
        
        // Count volunteers in each age range
        foreach ($ageRanges as $range) {
            $count = clone $query;
            $count = $count->where('age_range', $range)->count();
            $distribution[$range] = $count;
        }
        
        return [
            'labels' => array_map(function($range) {
                return match($range) {
                    '10-17' => '10-17 years',
                    '18-24' => '18-24 years',
                    '25-34' => '25-34 years',
                    '35-44' => '35-44 years',
                    '45-54' => '45-54 years',
                    '55-64' => '55-64 years',
                    '65+' => '65+ years',
                    default => $range
                };
            }, $ageRanges),
            'data' => array_values($distribution),
        ];
    }


    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }
}