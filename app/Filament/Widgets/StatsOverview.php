<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Volunteers', '192.1k'),
            Stat::make('Opportunities', '21%'),
            Stat::make('Total Hours', '3:12'),
        ];
    }
}
