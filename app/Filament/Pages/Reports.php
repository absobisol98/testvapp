<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\VolunteerSignupPerMonth;
use App\Filament\Widgets\VolunteerUsageWidgetByDepartment;
use App\Filament\Widgets\VolunteerUsageWidgetByProgram;
use App\Models\EventType;
use App\Models\Program;
use Filament\Pages\Page;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';
    protected static ?int $contentHeight = 300; //px

    
    
    protected function getHeaderWidgets(): array
    {

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
                                $tot_hrs += $attendee->time_in->diffInHours($attendee->time_out);
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
            if(!empty( $evnt->events)){
                foreach($evnt->events as $event){
                    if(!empty( $event->attendees)){
                        foreach ($event->attendees as $attendee){
                            if($attendee->time_in && $attendee->time_out){
                                $evnttot_hrs += $attendee->time_in->diffInHours($attendee->time_out);
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
        
        return [
            VolunteerUsageWidgetByProgram::make([
                'progNames' => $progNames,
                'count' =>  $count,
                'overall_hrs' =>  number_format($overall_hrs,1),
            ]),

            VolunteerUsageWidgetByDepartment::make([
                'progNames' => $evntNames,
                'count' =>  $evntcount,
                'overall_hrs' =>  number_format($evntoverall_hrs,1),
            ]),
            VolunteerSignupPerMonth::class,
        ];
    }

  



    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }

}
