<?php

namespace App\Filament\Widgets;

use App\Models\EventType;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class VolunteerUsageWidgetByDepartment extends ApexChartWidget
{
    protected static ?string $chartId = 'volunteerHoursByDepartment';

    public array $progNames;
    public array $count;
    public string $overall_hrs;
    protected static ?int $contentHeight = 300; //px


    protected function getOptions(): array
    {

        return[
            'series' => $this->count,
            'chart' => [
                'type' => 'pie',
                'height' => 300,
            ],
        

            'labels' => $this->progNames,
            'colors' => [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],

        ];
    }
      

    public function getHeading() : ?string
    {
        return 'Volunteer Hours By Program (Total Hours: '.$this->overall_hrs.')';
    }
}
