<?php

namespace App\Filament\Widgets;

use App\Models\Program;
use Filament\Widgets\ChartWidget;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class VolunteerUsageWidgetByProgram extends ApexChartWidget
{

    protected static ?string $chartId = 'volunteerHoursByProgram';

    public array $progNames;
    public array $count;
    public string $overall_hrs;
    public  $head = 0;

    protected static ?int $contentHeight = 300; //px

    protected function getOptions(): array
    {
        $this->head = $this->overall_hrs;
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
                'rgb(255, 0, 0)',
                'rgb(255, 205, 86)'
            ],

        ];
    }

    public function getHeading() : ?string
    {
        return 'Volunteer Hours By Program (Total Hours: '.$this->head.')';
    }
}
