<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class VolunteersByOpportunity extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'volunteersByOpportunity';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Volunteers By Opportunity';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */


    protected function getFormSchema(): array
    {
        return [
            
            Select::make('business_unit')
                ->label('Business Unit'),
                // ->options( function(EventType $eventtype){
                //     $options = array();
                //     foreach($eventtype->orderBy('name')->get() as $type){
                //         $name = ucfirst($type->name);
                //         $options[$type->id] = $name;
                //     }
                //     return $options;
                // })

            DatePicker::make('date_start')
                ->label('From')
                ->live(),

            DatePicker::make('date_end')
                ->label('To')
                ->disabled(fn (Get $get) => $get('date_start') ? false : true)
                ->required(fn (Get $get) => $get('date_start') ? true : false)
                ->minDate( fn (Get $get) => $get('date_start')),
    
        ];
    }


    protected function getOptions(): array
    {
       

        $type = $this->filterFormData['business_unit'];
        $dateStart = $this->filterFormData['date_start'];
        $dateEnd = $this->filterFormData['date_end'];

        // if has filters the graphs must display the count per event selected the event type

        $volunteer_hrs = array();
        $signed_up = array();
        $total_attendee = 0;
        $total_hrs = 0;
    
        $names = array();

        if(!$type){
            $events = Event::orderBy('title','asc')->get();
            $evnttot_hrs = 0;
            $attendee_count = 0;
            if($dateStart && $dateEnd){
                $events =  $events->whereBetween('start_date', [$dateStart, $dateEnd])->get();
            }
            foreach($events as $event){
                $attendee_count =  $event->attendees->count();
                if(!empty( $event->attendees)){
                    foreach ($event->attendees as $attendee){
                        if($attendee->is_approve){
                            $total_attendee++;
                            $evnttot_hrs += $attendee->get_totalHrs();
                        }
                    }
                }
                $evnt_single_count = (double)number_format($evnttot_hrs,1);
                array_push($names, $event->title);
                array_push($volunteer_hrs, $evnt_single_count);
                array_push($signed_up,  $attendee_count);
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
