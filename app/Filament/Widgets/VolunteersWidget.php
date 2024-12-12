<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class VolunteersWidget extends Widget
{
    protected static string $view = 'filament.widgets.volunteers-widget';

    protected function getViewData(): array
    {
        $totalStat1 = 23;
        $totalStat2 = 435;
        $totalStat3 = 546;
        $totalStat4 = 67;
        $totalStat5 = 54;

        return [
            'totalStat1' => $totalStat1,
            'totalStat2' => $totalStat2,
            'totalStat3' => $totalStat3,
            'totalStat4' => $totalStat4,
            'totalStat5' => $totalStat5,
        ];
    }
}
