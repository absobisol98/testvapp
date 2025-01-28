<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\Program;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $volunteers = User::role('volunteer')->count();
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
            $overall_hrs += $tot_hrs;
        }
        $overall_hrs = (double)number_format($overall_hrs,1);
        $event = Event::get()->count();
        return [
            Stat::make('Volunteers',  $volunteers),
            Stat::make('Opportunities', $event),
            Stat::make('Total Hours',  $overall_hrs.' hrs'),
        ];
    }
}
