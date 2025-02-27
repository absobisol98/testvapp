<?php

namespace App\Filament\Widgets;

use App\Models\BusinessUnit;
use App\Models\Event;
use App\Models\User;
use App\Models\Program;
use Filament\Widgets\Widget;
use App\Models\EventAttendee;

class HeroBannerWidget extends Widget
{
    protected static string $view = 'filament.widgets.hero-banner-widget';

    protected function getViewData(): array
    {
        $volunteer = User::role('volunteer')->count();
        $businessunit = BusinessUnit::count();
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        // Calculate Ayala Member hours
        $ayalaHours = EventAttendee::whereHas('attendee', function ($query) {
                $query->where('affiliate_type_id', 1); // Ayala members
            })
            ->whereNotNull(['time_in', 'time_out'])
            ->where('is_approve', true)
            ->get()
            ->sum(function ($attendance) {
                return $attendance->get_totalHrs();
            });

        // Calculate Non-Ayala Member hours
        $nonAyalaHours = EventAttendee::whereHas('attendee', function ($query) {
                $query->where('affiliate_type_id', 2); // Non-Ayala members
            })
            ->whereNotNull(['time_in', 'time_out'])
            ->where('is_approve', true)
            ->get()
            ->sum(function ($attendance) {
                return $attendance->get_totalHrs();
            });

        // Get volunteer counts
        $ayalaVolunteers = User::where('affiliate_type_id', 1)->count();
        $nonAyalaVolunteers = User::where('affiliate_type_id', 2)->count();
        $totalVolunteers = $ayalaVolunteers + $nonAyalaVolunteers;

        $bgImg = '';

        $user_created_date = auth()->user()->created_at;
        $total_hours_since_creation = Event::whereHas('attendees', function ($query) {
            $query->where('attendee_id', auth()->id())
                ->where('is_approve', true)
                ->whereNotNull('time_in')
                ->whereNotNull('time_out');
        })
        ->where('created_at', '>=', $user_created_date)
        ->with('attendees')
        ->get()
        ->sum(function ($event) {
            return $event->attendees
                ->where('attendee_id', auth()->id())
                ->where('is_approve', true)
                ->sum(function ($attendee) {
                    return $attendee->get_totalHrs();
                });
        });

        $progNames = array();
        $count = array();
        $overall_hrs = 0;
        $programs = Program::all();
        foreach ($programs as $program){
            $tot_hrs = 0;
            if(!empty( $program->events)){
                foreach($program->events as $event){
                    if(!empty( $event->attendees)){
                        foreach ($event->attendees as $attendee){
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

        $currentDate = now();

        $upcoming = Event::query()
        ->where('start_date', '>=', $currentDate)
        ->orderBy('start_date', 'asc')
        ->get();

        $opportunities = Event::with('slots')->orderBy('created_at','desc')->get();

        $totalHours = EventAttendee::whereNotNull(['time_in', 'time_out'])
            ->get()
            ->sum(fn($attendance) => $attendance->get_totalHrs());

        // For Volunteer
        if (false) {
            $bgImg = 'img/ayala-foundation-bg.jpg';

            // $totalStat1 = 3;
            // $totalStat2 = 20;
            // $totalStat3 = 203.51;
            // $totalStat4 = 200;
            // $totalStat5 = 0;
        }
        // For AFI Admin
        elseif (false) {
            $bgImg = 'img/hero-banner-bg_2.jpg';
            // $totalStat1 = 43;
            // $totalStat2 = 240;
            // $totalStat3 = 267.51;
            // $totalStat4 = 456;
            // $totalStat5 = 42;
        }
        // For partners
        elseif (true) {
            $bgImg = 'img/hero-banner-bg_3.jpg';
            // $totalStat1 = User::role('volunteer')->count();
            // $totalStat2 = Event::get()->count();
            // $totalStat3 = 658.51;
            // $totalStat4 = 980;
            // $totalStat5 = 223;
        }

        return [
            'opportunity' => $opportunity,
            'businessunit' => $businessunit,
            'volunteer' => $volunteer,
            'bgImg' => $bgImg,
            'overall_hrs' =>  number_format($overall_hrs, 1),
            'ayala_hours' => number_format($ayalaHours, 1),
            'non_ayala_hours' => number_format($nonAyalaHours, 1),
            'total_volunteer_hours' => number_format($ayalaHours + $nonAyalaHours, 1),
            'ayala_volunteers' => $ayalaVolunteers,
            'non_ayala_volunteers' => $nonAyalaVolunteers,
            'total_volunteers' => $totalVolunteers,
            'total_hours_since_creation' => number_format($total_hours_since_creation, 1),
            'opportunities' => $opportunities,
            'upcoming' => $upcoming,
            'account_created_at' => $user_created_date->format('M d, Y'),
            'totalHours' => $totalHours,
        ];
    }
}
