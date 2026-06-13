<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use App\Models\User;
use Filament\Widgets\Widget;

class HeroBannerWidget extends Widget
{
    protected static string $view = 'filament.widgets.hero-banner-widget';

    protected function getViewData(): array
    {
        $user       = auth()->user();
        $activeRole = $user->activeRole();

        // Volunteer-facing stats
        $availableOpportunities = Event::where(function ($q) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', now());
        })->count();

        $myUpcomingOpportunities = EventRegistration::where('volunteer_id', $user->id)
            ->whereHas('event', fn ($q) => $q->where('start_date', '>=', now()))
            ->count();

        $myHoursRendered = EventAttendee::where('attendee_id', $user->id)
            ->where('is_approve', true)
            ->whereNotNull(['time_in', 'time_out'])
            ->get()
            ->sum(fn ($a) => $a->get_totalHrs());

        $myCertificates = Certificate::where('attendee_id', $user->id)->count();

        // Admin-facing stats
        $totalVolunteers = User::role('Volunteer')->count();

        $totalVolunteerHours = EventAttendee::where('is_approve', true)
            ->whereNotNull(['time_in', 'time_out'])
            ->get()
            ->sum(fn ($a) => $a->get_totalHrs());

        // External Partner-facing
        $bu          = $user->currentBU();
        $buCompanyId = $bu?->company_id;

        $partnerEventScope = function ($q) use ($buCompanyId) {
            $q->where(function ($inner) use ($buCompanyId) {
                if ($buCompanyId) {
                    $inner->whereHas('companies', fn ($sq) => $sq->where('companies.id', $buCompanyId));
                }
                $inner->orWhere('is_public', true);
            });
        };

        $partnerAvailableOpportunities = Event::where(function ($q) {
            $q->whereNull('end_date')->orWhere('end_date', '>=', now());
        })->where($partnerEventScope)->count();

        $partnerUpcomingOpportunities = Event::where('start_date', '>=', now())
            ->where($partnerEventScope)
            ->count();

        $partnerHours = 0;
        if ($buCompanyId) {
            $buVolunteerIds = User::where('company_id', $buCompanyId)->pluck('id');
            $partnerHours = EventAttendee::where('is_approve', true)
                ->whereNotNull(['time_in', 'time_out'])
                ->whereIn('attendee_id', $buVolunteerIds)
                ->get()
                ->sum(fn ($a) => $a->get_totalHrs());
        }

        $bgImg = match (true) {
            $activeRole === 'Volunteer'          => 'img/ayala-foundation-bg.jpg',
            $activeRole === 'External Partner'   => 'img/hero-banner-bg_3.jpg',
            default                              => 'img/hero-banner-bg_2.jpg',
        };

        return [
            'activeRole'              => $activeRole,
            'availableOpportunities'  => $availableOpportunities,
            'myUpcomingOpportunities' => $myUpcomingOpportunities,
            'myHoursRendered'         => number_format($myHoursRendered, 1),
            'myCertificates'          => $myCertificates,
            'totalVolunteers'         => $totalVolunteers,
            'totalVolunteerHours'     => number_format($totalVolunteerHours, 1),
            'partnerAvailableOpportunities'  => $partnerAvailableOpportunities,
            'partnerUpcomingOpportunities'   => $partnerUpcomingOpportunities,
            'partnerHours'                   => number_format($partnerHours, 1),
            'bgImg'                          => $bgImg,
        ];
    }
}
