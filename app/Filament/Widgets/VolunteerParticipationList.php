<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\EventAttendee;
use Illuminate\Support\Facades\Auth;
class VolunteerParticipationList extends BaseWidget
{
    protected function getTableQuery(): Builder|Relation|null
    {
        return User::query()
            ->withCount('eventAttendees as total_opportunities')
            ->having('total_opportunities', '>', 0);
    }

     // Add cluster filter parameter
     public ?int $clusterFilter = null;
    
     public function mount(?int $clusterFilter = null): void
     {
         // If no cluster filter is passed, check if current user is External Partner
         if (!$clusterFilter) {
             $user = Auth::user();
             if ($user && $user->hasRole('External Partner') && $user->cluster_id) {
                 $this->clusterFilter = $user->cluster_id;
             }
         } else {
             $this->clusterFilter = $clusterFilter;
         }
     }


    protected function getYearOptions(): array
    {
        $years = EventAttendee::selectRaw('YEAR(created_at) as year')
            ->whereNotNull('created_at')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter() // Remove null values
            ->toArray();

        return array_combine($years, array_map(function ($year) {
            return (string) $year; // Ensure values are strings
        }, $years));
    }


    protected function getTableColumns(): array
    {
        return [
           TextColumn::make('name')
            ->label('Volunteer')
            ->searchable()
            ->sortable(query: function (Builder $query, string $direction): Builder {
                return $query->orderBy('firstname', $direction)
                            ->orderBy('lastname', $direction);
            }),

            TextColumn::make('affiliate_type')
            ->label('Affiliation')
            ->getStateUsing(fn ($record) => match($record->affiliate_type_id) {
                1 => 'Ayala',
                2 => 'Non-Ayala',
                default => 'N/A'
            })
            ->sortable(query: function (Builder $query, string $direction): Builder {
                return $query->orderBy('affiliate_type_id', $direction);
            }),

            TextColumn::make('total_opportunities')
                ->label('Total Opportunities')
                ->sortable()
                ->alignEnd(),

TextColumn::make('total_hours')
    ->label('Total Hours')
    ->getStateUsing(function ($record) {
        $total = $record->eventAttendees()
            ->get()
            ->sum(fn($attendance) => $attendance->get_totalHrs());
        
        // Debug edge case
        if ($record->name === 'Marmykl Ting') {
            \Log::info('Marmykl Ting hours breakdown:', [
                'attendances' => $record->eventAttendees->map(fn($a) => [
                    'time_in' => $a->time_in,
                    'time_out' => $a->time_out,
                    'encoding_type' => $a->encoding_type,
                    'volunteer_count' => $a->volunteer_count,
                    'calculated_hours' => $a->get_totalHrs()
                ])
            ]);
        }
        
        return $total;
    })
    ->numeric()
    ->sortable(query: function (Builder $query, string $direction): Builder {
        return $query->addSelect([
            'calculated_hours' => EventAttendee::selectRaw('
                COALESCE(SUM(
                    CASE 
                        WHEN time_in IS NOT NULL AND time_out IS NOT NULL THEN
                            (TIMESTAMPDIFF(HOUR, time_in, time_out) + (DATEDIFF(time_out, time_in) * 24)) *
                            CASE WHEN encoding_type = 3 THEN volunteer_count ELSE 1 END
                        ELSE 0
                    END
                ), 0)
            ')
            ->whereColumn('event_attendees.attendee_id', 'users.id')
        ])->orderBy('calculated_hours', $direction);
    })
    ->alignEnd(),
            TextColumn::make('latest_participation')
                ->label('Latest Participation')
                ->getStateUsing(function ($record) {
                    $latestAttendance = $record->eventAttendees()
                        ->latest('created_at')
                        ->first();

                    return $latestAttendance ?
                        Carbon::parse($latestAttendance->created_at)->format('M d, Y') :
                        'N/A';
                }),

            TextColumn::make('participation_frequency')
                ->label('Frequency')
                ->getStateUsing(fn ($record) =>
                    $record->getParticipationFrequency()
                ),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('affiliate_type_id') // Changed from affiliate_type
            ->label('Affiliation')
            ->options([
                1 => 'Ayala',
                2 => 'Non-Ayala'
            ])
            ->multiple()
            ->query(function (Builder $query, array $data): Builder {
                return $query->when(
                    !empty($data['values']),
                    fn (Builder $query) => $query->whereIn('affiliate_type_id', $data['values'])
                );
            }),


            SelectFilter::make('business_unit')
                ->label('Business Unit')
                ->relationship('eventAttendees.event.companies', 'name')
                ->multiple()
                ->preload(),

            SelectFilter::make('period')
                ->label('Time Period')
                ->options([
                    'month' => 'This Month',
                    'quarter' => 'This Quarter',
                    'year' => 'This Year'
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function ($query, $value) {
                        return match($value) {
                            'month' => $query->whereHas('eventAttendees', fn($q) =>
                                $q->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)),
                            'quarter' => $query->whereHas('eventAttendees', fn($q) =>
                                $q->whereRaw('QUARTER(created_at) = ?', [now()->quarter])
                                  ->whereYear('created_at', now()->year)),
                            'year' => $query->whereHas('eventAttendees', fn($q) =>
                                $q->whereYear('created_at', now()->year)),
                            default => $query
                        };
                    });
                }),

            SelectFilter::make('year')
                ->label('Filter by Year')
                ->options($this->getYearOptions())
                ->multiple()
                ->preload()
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        !empty($data['values']),
                        fn (Builder $query) => $query->whereHas(
                            'eventAttendees',
                            fn ($query) => $query->whereIn(DB::raw('YEAR(created_at)'), $data['values'])
                        )
                    );
                }),
        ];
    }
}
