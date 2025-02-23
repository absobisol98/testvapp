<?php

namespace App\Filament\Widgets;

use App\Models\EventAttendee;
use App\Models\User;
use App\Models\BusinessUnit;
use App\Models\ExternalPartner;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class LeaderboardWidget extends ChartWidget
{
    protected static ?string $heading = 'Volunteer Leaderboard';
    protected static ?string $pollingInterval = null;
    public ?string $filter = 'monthly';

    protected function getFilters(): ?array
    {
        return [
            'monthly' => 'This Month',
            'quarterly' => 'This Quarter',
            'annually' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $dateFilter = $this->getDateFilter();

        $topVolunteers = EventAttendee::query()
            ->with('volunteer')
            ->whereBetween('created_at', $dateFilter)
            ->where('is_approve', true)
            ->select('attendee_id')
            ->selectRaw('COUNT(*) as participation_count')
            ->groupBy('attendee_id')
            ->orderByDesc('participation_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Participation Count',
                    'data' => $topVolunteers->pluck('participation_count')->toArray(),
                ],
            ],
            'labels' => $topVolunteers->map(function ($attendee) {
                return $attendee->volunteer->firstname . ' ' . $attendee->volunteer->lastname;
            })->toArray(),
        ];
    }

    private function getDateFilter(): array
    {
        $now = Carbon::now();

        return match ($this->filter) {
            'quarterly' => [
                $now->startOfQuarter(),
                $now->endOfQuarter(),
            ],
            'annually' => [
                $now->startOfYear(),
                $now->endOfYear(),
            ],
            default => [
                $now->startOfMonth(),
                $now->endOfMonth(),
            ],
        };
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
