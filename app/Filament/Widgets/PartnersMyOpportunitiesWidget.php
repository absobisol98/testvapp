<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PartnersMyOpportunitiesWidget extends Widget
{
    protected static string $view = 'filament.widgets.partners-my-opportunities-widget';

    protected function getViewData(): array
    {
        $totalStat1 = 13;
        $totalStat2 = 876;
        $totalStat3 = 258;
        $totalStat4 = 657;
        $totalStat5 = 436;

        return [
            'totalStat1' => $totalStat1,
            'totalStat2' => $totalStat2,
            'totalStat3' => $totalStat3,
            'totalStat4' => $totalStat4,
            'totalStat5' => $totalStat5,
        ];
    }
}
