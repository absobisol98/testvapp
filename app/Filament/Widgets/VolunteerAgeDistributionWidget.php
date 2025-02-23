<?php

namespace App\Filament\Widgets;

use App\Models\Volunteer;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VolunteerAgeDistributionWidget extends ChartWidget
{
    protected static ?string $heading = 'Volunteer Age Distribution';

    protected function getData(): array
    {
        $ageRanges = [
            '18-24' => [18, 24],
            '25-34' => [25, 34],
            '35-44' => [35, 44],
            '45-54' => [45, 54],
            '55+' => [55, 150],
        ];

        $distribution = [];
        foreach ($ageRanges as $label => [$min, $max]) {
            $count = Volunteer::whereRaw('TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN ? AND ?', [$min, $max])
                ->count();
            $distribution[$label] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Volunteers',
                    'data' => array_values($distribution),
                ],
            ],
            'labels' => array_keys($distribution),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
