<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\User;
use Filament\Widgets\Widget;

class HeroBannerWidget extends Widget
{
    protected static string $view = 'filament.widgets.hero-banner-widget';

    protected function getViewData(): array
    {
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        $totalStat1 = 0;
        $totalStat2 = 0;
        $totalStat3 = 0;
        $totalStat4 = 0;
        $totalStat5 = 0;

        $bgImg = '';

        // For Volunteer
        if (false) {
            $bgImg = 'img/ayala-foundation-bg.jpg';

            $totalStat1 = 3;
            $totalStat2 = 20;
            $totalStat3 = 203.51;
            $totalStat4 = 200;
            $totalStat5 = 0;
        }
        // For AFI Admin
        elseif (false) {
            $bgImg = 'img/hero-banner-bg_2.jpg';
            $totalStat1 = 43;
            $totalStat2 = 240;
            $totalStat3 = 267.51;
            $totalStat4 = 456;
            $totalStat5 = 42;
        }
        // For partners
        elseif (true) {
            $bgImg = 'img/hero-banner-bg_3.jpg';
            $totalStat1 = User::role('volunteer')->count();
            $totalStat2 = Event::get()->count();
            $totalStat3 = 658.51;
            $totalStat4 = 980;
            $totalStat5 = 223;
        }

        return [
            'opportunity' => $opportunity,
            'totalStat1' => $totalStat1,
            'totalStat2' => $totalStat2,
            'totalStat3' => $totalStat3,
            'totalStat4' => $totalStat4,
            'totalStat5' => $totalStat5,
            'bgImg' => $bgImg,
        ];
    }
}
