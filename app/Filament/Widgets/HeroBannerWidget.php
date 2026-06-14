<?php

namespace App\Filament\Widgets;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

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

        // Exclude Rejected registrations so the count reflects real upcoming commitments
        $myUpcomingOpportunities = EventRegistration::where('volunteer_id', $user->id)
            ->whereHas('event', fn ($q) => $q->where('start_date', '>=', now()))
            ->whereHas('status', fn ($q) => $q->where('name', '!=', 'Rejected'))
            ->count();

        $myHoursRendered = self::sumApprovedHours(
            EventAttendee::where('attendee_id', $user->id)->where('is_approve', true)
        );

        $myHoursThisMonth = self::sumApprovedHours(
            EventAttendee::where('attendee_id', $user->id)
                ->where('is_approve', true)
                ->whereMonth('time_in', now()->month)
                ->whereYear('time_in', now()->year)
        );

        $myEventsAttended = EventAttendee::where('attendee_id', $user->id)
            ->where('is_approve', true)
            ->count();

        $myCertificates = Certificate::where('attendee_id', $user->id)->count();

        $badgeInfo = ($activeRole === 'Volunteer') ? $user->getBadges() : null;

        // Admin-facing stats
        $totalVolunteers = User::role('Volunteer')->count();

        $totalVolunteerHours = self::sumApprovedHours(
            EventAttendee::where('is_approve', true)
        );

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
            $partnerHours = self::sumApprovedHours(
                EventAttendee::where('is_approve', true)->whereIn('attendee_id', $buVolunteerIds)
            );
        }

        $bgImg = match (true) {
            $activeRole === 'Volunteer'        => 'img/ayala-foundation-bg.jpg',
            $activeRole === 'External Partner' => 'img/hero-banner-bg_3.jpg',
            default                            => 'img/hero-banner-bg_2.jpg',
        };

        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

        return [
            'activeRole'                     => $activeRole,
            'greeting'                       => $greeting,
            'availableOpportunities'         => $availableOpportunities,
            'myUpcomingOpportunities'        => $myUpcomingOpportunities,
            'myHoursRendered'                => number_format($myHoursRendered, 1),
            'myHoursThisMonth'               => number_format($myHoursThisMonth, 1),
            'myEventsAttended'               => $myEventsAttended,
            'myCertificates'                 => $myCertificates,
            'badgeInfo'                      => $badgeInfo,
            'totalVolunteers'                => $totalVolunteers,
            'totalVolunteerHours'            => number_format($totalVolunteerHours, 1),
            'partnerAvailableOpportunities'  => $partnerAvailableOpportunities,
            'partnerUpcomingOpportunities'   => $partnerUpcomingOpportunities,
            'partnerHours'                   => number_format($partnerHours, 1),
            'bgImg'                          => $bgImg,
        ];
    }

    /**
     * Sum volunteer hours efficiently.
     * On MySQL uses TIMESTAMPDIFF to avoid loading all rows; bulk-encoded
     * records (encoding_type = 3) are handled separately in PHP since they
     * multiply hours by volunteer_count.
     */
    private static function sumApprovedHours(\Illuminate\Database\Eloquent\Builder $base): float
    {
        $base = $base->whereNotNull(['time_in', 'time_out']);

        if (DB::getDriverName() === 'sqlite') {
            return (float) $base->get()->sum(fn ($a) => $a->get_totalHrs());
        }

        // Non-bulk: calculate entirely in DB
        $regularHours = (clone $base)
            ->where(fn ($q) => $q->whereNull('encoding_type')->orWhere('encoding_type', '!=', 3))
            ->selectRaw('COALESCE(SUM(TIMESTAMPDIFF(HOUR, time_in, time_out)), 0) as total')
            ->value('total') ?? 0;

        // Bulk rows: need PHP because hours *= volunteer_count
        $bulkHours = (clone $base)
            ->where('encoding_type', 3)
            ->get(['time_in', 'time_out', 'encoding_type', 'volunteer_count'])
            ->sum(fn ($a) => $a->get_totalHrs());

        return (float) ($regularHours + $bulkHours);
    }
}
