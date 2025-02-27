<?php
namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\EventAttendee;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Builder;

class EventOpportunitySummary extends Widget implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string $view = 'filament.widgets.event-opportunity-summary';

    public ?array $data = [];
    public $selectedEventId = null;
    public $summary = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedEventId')
                    ->label('Select Opportunity')
                    ->options(Event::pluck('title', 'id'))
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        $this->selectedEventId = $state;
                        if ($state) {
                            $this->calculateSummary();
                        }
                    })
            ])
            ->statePath('data');
    }

    public function getFormModel(): Model|string|null
    {
        return null;
    }

    public function calculateSummary(): void
    {
        if (!$this->selectedEventId) {
            return;
        }

        $event = Event::with(['attendees.attendee'])->findOrFail($this->selectedEventId);

        // Get approved attendees with time records
        $approvedAttendees = $event->attendees()
            ->whereNotNull(['time_in', 'time_out'])
            ->where('is_approve', true)
            ->with(['attendee.company'])
            ->get();

        // Calculate total hours and volunteers
        $totalVolunteers = $approvedAttendees->count();
        $totalHours = $approvedAttendees->sum(function ($attendee) {
            return $attendee->get_totalHrs();
        });

        // Calculate Ayala vs Non-Ayala breakdown
        $ayalaVolunteers = $approvedAttendees->filter(fn($a) => $a->attendee->affiliate_type_id === 1)->count();
        $nonAyalaVolunteers = $totalVolunteers - $ayalaVolunteers;

        // Calculate company breakdown
        $companyBreakdown = $approvedAttendees
            ->where('attendee.affiliate_type_id', 1) // Only Ayala employees
            ->groupBy('attendee.company_id')
            ->map(function ($group) use ($totalVolunteers) {
                $company = $group->first()->attendee->company;
                return [
                    'name' => $company->name,
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / $totalVolunteers) * 100, 1)
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->toArray();

        $this->summary = [
            'total_hours' => number_format($totalHours, 1),
            'total_volunteers' => $totalVolunteers,
            'ayala_volunteers' => [
                'count' => $ayalaVolunteers,
                'percentage' => $totalVolunteers > 0 ? round(($ayalaVolunteers / $totalVolunteers) * 100, 1) : 0
            ],
            'non_ayala_volunteers' => [
                'count' => $nonAyalaVolunteers,
                'percentage' => $totalVolunteers > 0 ? round(($nonAyalaVolunteers / $totalVolunteers) * 100, 1) : 0
            ],
            'company_breakdown' => $companyBreakdown
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                EventAttendee::query()
                    ->with(['attendee', 'attendee.company', 'slot'])
                    ->when($this->selectedEventId, function (Builder $query) {
                        $query->where('event_id', $this->selectedEventId);
                    })
                    ->whereNotNull(['time_in', 'time_out'])
                    ->where('is_approve', true)
            )
            ->columns([
                Tables\Columns\TextColumn::make('attendee.firstname')
                    ->label('Volunteer Name')
                    ->formatStateUsing(fn ($record) => "{$record->attendee->firstname} {$record->attendee->lastname}")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('attendee', function ($query) use ($search) {
                                $query->where('firstname', 'like', "%{$search}%")
                                    ->orWhere('lastname', 'like', "%{$search}%");
                            });
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('slot.shift_name')
                    ->label('Shift')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendee.company.name')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendee.affiliate_type_id')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => $state === 1 ? 'Ayala Employee' : 'Non-Ayala')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_in')
                    ->label('Time In')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_out')
                    ->label('Time Out')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('Total Hours')
                    ->getStateUsing(function(EventAttendee $record) {
                        return $record->get_totalHrs();
                    }),
            ])
            ->defaultSort('attendee.firstname', 'asc')

            ->paginated([10, 25, 50, 100]);
    }
}
