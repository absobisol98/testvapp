<?php

namespace App\Filament\Widgets;

use App\Models\AffiliateType;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\User;
use Filament\Forms\Components\Select;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class VolunteersByCategory extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'volunteersByCategory';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Volunteers By Category';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */


     protected function getFormSchema(): array
    {
        return [
            
            Select::make('type')
                ->label('Category')
                ->options( fn () => AffiliateType::get()->pluck('name','id'))
        ];
    }
    
    protected function getOptions(): array
    {

        $type = $this->filterFormData['type'];

        // if has filters the graphs must display the count per event selected the event type

        $volunteer_hrs = array();
        $signed_up = array();
        $total_hrs = 0;

        
        $names = array();

        if(!$type){
            $evnts = AffiliateType::all();
            foreach ($evnts as $evnt){
                $evnttot_hrs = 0;
                $attendee_count = 0;
                $volunteers = User::role('volunteer')->where('affiliate_type_id',$evnt->id)->get()->pluck('id');
                $eventAttendee = EventAttendee::whereIn('attendee_id',$volunteers)->get();
                if(!empty( $eventAttendee)){
                    $attendee_count = $eventAttendee->count();
                    foreach ($eventAttendee as $attendee){
                        if($attendee->is_approve){
                            $evnttot_hrs += $attendee->get_totalHrs();
                        }
                    }
                    if($evnttot_hrs != 0){
                        $evnt_single_count = (double)number_format($evnttot_hrs,1);
                        array_push($names, $evnt->name);
                        array_push($volunteer_hrs, $evnt_single_count);
                        array_push($signed_up,  $attendee_count);
                    }
                    
                }
                $total_hrs += $evnttot_hrs;
            }
        }
        else{
            $evnt = AffiliateType::find($type);
            $volunteers = User::role('volunteer')->where('affiliate_type_id',$evnt->id)->get();
            if(!empty( $volunteers)){
                foreach ($volunteers as $volunteer){
                    $evnttot_hrs = 0;
                    $attendee_count = 0;

                    if(!empty( $volunteer->eventAttended)){
                        $attendee_count =  $volunteer->eventAttended->count();
                        foreach ($volunteer->eventAttended as $attendee){
                            if($attendee->is_approve){
                                $evnttot_hrs += $attendee->get_totalHrs();
                            }
                        }
                        
                    }
                    $evnt_single_count = (double)number_format($evnttot_hrs,1);
                    array_push($names, $volunteer->firstname.' '.$volunteer->lastname);
                    array_push($volunteer_hrs, $evnt_single_count);
                    array_push($signed_up,  $attendee_count);
                    
                }
            }
        }
        

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
            ],
            'series' => [
                [
                    'name' => 'Volunteer Hours',
                    'data' => $volunteer_hrs,
                ],
                [
                    'name' => 'Volunteer Sign Up',
                    'data' => $signed_up,
                ]
            ],
            'xaxis' => [
                'categories' => $names,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            // 'colors' => ['#f59e0b'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 3,
                ],
            ],
        ];
    }
}
