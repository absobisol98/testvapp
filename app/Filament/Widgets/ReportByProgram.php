<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Illuminate\Contracts\View\View;


class ReportByProgram extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'reportByProgram';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Volunteer Usage Report By Department';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
 
    public $total_attendee = 0;
    public $total_hours = 0;

    protected function getFormSchema(): array
    {
        return [
            
            Select::make('type')
                ->label('Event type')
                ->options( function(Program $eventtype){
                    $options = array();
                    foreach($eventtype->orderBy('name')->get() as $type){
                        $name = ucfirst($type->name);
                        $options[$type->id] = $name;
                    }
                    return $options;
                }),

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

    protected function getFooter(): string|View
    {
        // return '';
        $type = $this->filterFormData['type'];
        $dateStart = $this->filterFormData['date_start'];
        $dateEnd = $this->filterFormData['date_end'];

        if(!$type){
            $type = null;
        }
        else{
            $type = Program::find($type);
            $type = $type->name;
            $type = 'Department: <span class="text-primary-500"> '.$type.'</span>';
        }
        if(!$dateStart || !$dateEnd){
            $dateRange = null;
        }
        else{
            $dateStart = Carbon::parse( $dateStart)->isoFormat('MMMM DD YYYY');
            $dateEnd = Carbon::parse( $dateEnd)->isoFormat('MMMM DD YYYY');

            $dateRange = '<br> Date Filters: <span class="text-primary-500"> '.$dateStart.'</span> - <span class="text-primary-500"> '.$dateEnd.'</span> ';
        }


        return new HtmlString('<p class="text-gray">'.$type.''.$dateRange.'</p>');
    }


    protected function getOptions(): array
    {

        $type = $this->filterFormData['type'];
        $dateStart = $this->filterFormData['date_start'];
        $dateEnd = $this->filterFormData['date_end'];

        // if has filters the graphs must display the count per event selected the event type

        $volunteer_hrs = array();
        $signed_up = array();
        $total_attendee = 0;
        $total_hrs = 0;

        
        $names = array();

        if(!$type){
            $evnts = Program::all();
            foreach ($evnts as $evnt){
                $evnttot_hrs = 0;
                $attendee_count = 0;
                if(!empty( $evnt->events)){
                    $events =  $evnt->events;
                    if($dateStart && $dateEnd){
                        $events =  $evnt->events->whereBetween('start_date', [$dateStart, $dateEnd]);
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
                    }
                    $evnt_single_count = (double)number_format($evnttot_hrs,1);
                    array_push($names, $evnt->name);
                    array_push($volunteer_hrs, $evnt_single_count);
                    array_push($signed_up,  $attendee_count);
                }
                $total_hrs += $evnttot_hrs;
            }
            $this->total_hours = $total_hrs;
        }
        else{
            $evnt = Program::find($type);
                $evnttot_hrs = 0;
                $attendee_count = 0;
                if(!empty( $evnt->events)){
                    $events =  $evnt->events;
                    if($dateStart && $dateEnd){
                        $events =  $evnt->events->whereBetween('start_date', [$dateStart, $dateEnd]);
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
                $total_hrs += $evnttot_hrs;
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
