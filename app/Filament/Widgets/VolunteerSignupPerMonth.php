<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;


class VolunteerSignupPerMonth extends ChartWidget
{
    protected static ?string $heading = 'Volunteer Sign Up Per Month';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {

        $data = Trend::query(User::role('volunteer'))
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        return [

            'datasets' => [
                [
                    'label' => 'Volunteers',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'backgroundColor' => '#0000FF',
            'borderColor' => '#0000FF',
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];

    }
    protected function getType(): string
    {
        return 'bar';
    }
}
