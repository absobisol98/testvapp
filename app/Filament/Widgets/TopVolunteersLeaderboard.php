<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\EventAttendee;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TopVolunteersLeaderboard extends BaseWidget
{
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
    
    protected function getTableQuery(): Builder
    {
        $query = User::query()
            ->select('users.*')
            ->withCount(['eventAttendees' => function($query) {
                $query->where('encoding_type', '!=', 3);
                
                // Apply cluster filter to event attendees
                if ($this->clusterFilter) {
                    $query->whereHas('event.attendees.attendee', function($q) {
                        $q->where('cluster_id', $this->clusterFilter);
                    });
                }
            }, 'eventAttendees as total_opportunities'])
            ->addSelect([
                'computed_hours' => EventAttendee::query()
                    ->whereColumn('attendee_id', 'users.id')
                    ->where('encoding_type', '!=', 3)
                    ->whereNotNull(['time_in', 'time_out'])
                    ->when($this->clusterFilter, function ($query) {
                        // When cluster filter is applied, only count hours for events in that cluster
                        return $query->whereHas('attendee', function ($q) {
                            $q->where('cluster_id', $this->clusterFilter);
                        });
                    })
                    ->selectRaw('SUM(TIME_TO_SEC(TIMEDIFF(time_out, time_in))/3600)')
            ])
            ->having('total_opportunities', '>', 0)
            ->orderByDesc('computed_hours');
            
        // Filter users directly by cluster_id if External Partner
        if ($this->clusterFilter) {
            $query->where('cluster_id', $this->clusterFilter);
        }
        
        return $query;
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('rank')
                ->getStateUsing(static function ($rowLoop): string {
                    return match($rowLoop->iteration) {
                        1 => '🏆 1st',
                        2 => '🥈 2nd',
                        3 => '🥉 3rd',
                        default => (string) $rowLoop->iteration . 'th'
                    };
                })
                ->label('Rank')
                ->alignCenter()
                ->weight('bold')
                ->color(fn ($record, $state) => match(substr($state, -3)) {
                    '1st' => 'warning', // Gold
                    '2nd' => 'gray',    // Silver
                    '3rd' => 'orange',  // Bronze
                    default => null
                }),

            TextColumn::make('name')
                ->label('Volunteer')
                ->searchable()
                ->sortable(),

            TextColumn::make('affiliate_type_id')
                ->label('Affiliation')
                ->getStateUsing(fn ($record) => match($record->affiliate_type_id) {
                    1 => 'Ayala',
                    2 => 'Non-Ayala',
                    default => 'N/A'
                }),

            TextColumn::make('total_hours')
                ->label('Total Hours')
                ->getStateUsing(function ($record) {
                    $query = $record->eventAttendees()
                        ->where('encoding_type', '!=', 3);
                    
                    // Apply cluster filter if set
                    if ($this->clusterFilter) {
                        $query = $query->whereHas('attendee', function ($q) {
                            $q->where('cluster_id', $this->clusterFilter);
                        });
                    }
                    
                    return $query->get()
                        ->sum(fn($attendance) => $attendance->get_totalHrs());
                })
                ->numeric()
                ->alignEnd(),

            TextColumn::make('total_opportunities')
                ->label('Total Events')
                ->sortable()
                ->alignEnd(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('business_unit')
                ->relationship('eventAttendees.event.companies', 'name')
                ->multiple()
                ->preload()
                ->query(function (Builder $query) {
                    // Filter companies by cluster if External Partner
                    if ($this->clusterFilter) {
                        return $query->where('cluster_id', $this->clusterFilter);
                    }
                    return $query;
                }),

            SelectFilter::make('affiliate_type_id')
                ->label('Affiliation')
                ->options([
                    1 => 'Ayala',
                    2 => 'Non-Ayala'
                ]),

            SelectFilter::make('period')
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
        ];
    }
    
    public function getTableHeading(): string
    {
        if ($this->clusterFilter) {
            return 'Top Volunteers (Cluster Filtered)';
        }
        
        return 'Top Volunteers';
    }
}