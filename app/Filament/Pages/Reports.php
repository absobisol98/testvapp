<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use App\Models\Program;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class Reports extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string  $view           = 'filament.pages.reports';

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    // Summary stats shown at the top of the page
    public array $summary = [];

    // Program-level hours breakdown (used in Volunteers tab)
    public array $programBreakdown = [];

    // Opportunities tab aggregate stats
    public array $opportunityStats = [];

    public function mount(): void
    {
        $this->computeSummary();
        $this->computeProgramBreakdown();
        $this->computeOpportunityStats();
    }

    private function computeSummary(): void
    {
        $totalVolunteers = User::whereHas('roles', fn ($q) => $q->where('name', 'Volunteer'))->count();

        $allAttendees = EventAttendee::whereNotNull('time_in')->whereNotNull('time_out')->get();
        $totalHours   = $allAttendees->sum(fn ($a) => $a->get_totalHrs());

        $totalEvents        = Event::where('is_published', true)->count();
        $totalRegistrations = EventRegistration::count();
        $totalAttended      = EventAttendee::whereNotNull('time_in')->count();
        $completionRate     = $totalRegistrations > 0
            ? round(($totalAttended / $totalRegistrations) * 100, 1)
            : 0;

        $avgHours = $totalVolunteers > 0 ? round($totalHours / $totalVolunteers, 1) : 0;

        $this->summary = [
            'total_volunteers'    => number_format($totalVolunteers),
            'total_hours'         => number_format($totalHours, 1),
            'total_events'        => number_format($totalEvents),
            'total_registrations' => number_format($totalRegistrations),
            'completion_rate'     => $completionRate,
            'avg_hours'           => $avgHours,
        ];
    }

    private function computeProgramBreakdown(): void
    {
        $rows = [];
        foreach (Program::with('events.attendees')->get() as $program) {
            $hours = 0;
            foreach ($program->events as $event) {
                foreach ($event->attendees as $attendee) {
                    if ($attendee->time_in && $attendee->time_out) {
                        $hours += $attendee->get_totalHrs();
                    }
                }
            }
            if ($hours > 0) {
                $rows[] = [
                    'name'  => $program->name,
                    'hours' => round($hours, 1),
                    'count' => $program->events->count(),
                ];
            }
        }

        usort($rows, fn ($a, $b) => $b['hours'] <=> $a['hours']);
        $this->programBreakdown = $rows;
    }

    private function computeOpportunityStats(): void
    {
        $year = now()->year;

        $eventsThisYear  = Event::whereYear('start_date', $year)->where('is_published', true)->count();
        $completedEvents = Event::whereYear('end_date', $year)
            ->where('end_date', '<', now())
            ->where('is_published', true)
            ->count();

        $slotsTotal  = DB::table('event_slots')
            ->whereExists(fn ($q) => $q->from('events')->whereColumn('events.id', 'event_slots.event_id')->whereYear('events.start_date', $year))
            ->sum('total_slots');

        $slotsFilled = EventRegistration::whereYear('created_at', $year)
            ->whereNotIn('status_id', [3])
            ->count();

        $fillRate = $slotsTotal > 0 ? round(($slotsFilled / $slotsTotal) * 100, 1) : 0;

        $topEvent = Event::withCount(['registrations' => fn ($q) => $q->whereNotIn('status_id', [3])])
            ->whereYear('start_date', $year)
            ->orderByDesc('registrations_count')
            ->first();

        $this->opportunityStats = [
            'year'             => $year,
            'events_this_year' => $eventsThisYear,
            'completed_events' => $completedEvents,
            'slots_total'      => number_format($slotsTotal),
            'slots_filled'     => number_format($slotsFilled),
            'fill_rate'        => $fillRate,
            'top_event'        => $topEvent?->title ?? '—',
            'top_event_regs'   => $topEvent?->registrations_count ?? 0,
        ];
    }
}
