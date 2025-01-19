<?php

namespace App\Filament\Widgets;

use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OpportunityPerVolunteer extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'opportunityPerVolunteer';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Volunteers Signed Up';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

     protected function getFormSchema(): array
    {
        return [
            Select::make('volunteer')
                ->label('Select Volunteer')
                ->searchable()
                ->options(function (){
                    $option = array();
                    $users = User::role('volunteer')->orderBy('firstname')->get();
                    foreach ($users as $user){
                        $option[$user->id] = $user->firstname.' '.$user->lastname;
                    }
                    return $option;
                }),
            Select::make('year')
                ->label('Select Year')
                ->options(function (Get $get) {
                    $years = [];
                    for($x=0; $x<=2; $x++){
                        $years[now()->subYear($x)->format('Y')] = now()->subYear($x)->format('Y'); 
                    }
                    if($get('volunteer')){
                        $reg = EventRegistration::where('volunteer_id',$get('volunteer'))
                            ->select(DB::raw('YEAR(created_at) year'))
                            ->groupBy('year')
                            ->get('year')->pluck('year','year');
                        if(count($reg)> 0){
                            return $reg;
                        }
                    }
                    return $years;
                  
                }),
        ];
    }

    protected function getOptions(): array
    {

        $start = now()->startOfYear();
        $end = now()->endOfYear();

        $year = $this->filterFormData['year'];
        if($year){
            $date = $year.'-01-01';
            $start = Carbon::parse($date)->startOfYear();
            $end = Carbon::parse($date)->endOfYear();
        }

        $volunteer = $this->filterFormData['volunteer'];
        $data = Trend::model(EventRegistration::class)
            ->between(
                start:  $start,
                end: $end,
            )
            ->perMonth()
            ->count();

        if($volunteer){
            $data = Trend::query(EventRegistration::where('volunteer_id',$volunteer))
            ->between(
                start:  $start,
                end: $end,
            )
            ->perMonth()
            ->count();
        }
      

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 400,
            ],
            'series' => [
                [
                    'name' => 'Join Opportunities',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ]
            ],
            'xaxis' => [
                'categories' => $data->map(fn (TrendValue $value) => $value->date),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'tickAmount' => 1,
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
