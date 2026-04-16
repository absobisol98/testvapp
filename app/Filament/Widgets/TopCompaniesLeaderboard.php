<?php
namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\EventAttendee;
use App\Models\User;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class TopCompaniesLeaderboard extends BaseWidget
{
    protected static ?string $heading = 'Top Business Units';

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
        // First query: Get companies
        $companies = Company::query();

        // Get all companies with their IDs
        $companyIds = $companies->pluck('id')->toArray();

        // Calculate total hours for each company
        $companyHours = [];

        foreach ($companyIds as $companyId) {
            // Get all event attendees for this company
            $attendees = EventAttendee::query()
                ->join('users', 'event_attendees.attendee_id', '=', 'users.id')
                ->where('users.company_id', $companyId)
                ->where('encoding_type', '!=', 3)
                ->where('is_approve', true)
                ->whereNotNull(['time_in', 'time_out'])
                ->get();

            // Calculate total hours using the get_totalHrs() method
            $totalHours = $attendees->sum(function ($attendee) {
                return $attendee->get_totalHrs();
            });

            $companyHours[$companyId] = $totalHours;
        }

        // Apply the calculated hours to the query results
        return $companies
            ->get()
            ->each(function ($company) use ($companyHours) {
                $company->total_hours = $companyHours[$company->id] ?? 0;
            })
            ->filter(function ($company) {
                // Filter out companies with no volunteers
                $volunteerCount = User::where('company_id', $company->id)
                    ->where('volunteer', 1)
                    ->count();
                return $volunteerCount > 0;
            })
            ->sortByDesc('total_hours')
            // Convert to query builder for compatibility with Filament
            ->pipe(function ($collection) {
                if ($collection->isEmpty()) {
                    return Company::query()->where('id', 0); // Return empty query if no companies
                }

                return Company::query()->whereIn('id', $collection->pluck('id')->toArray())
                    ->orderByRaw(
                        'FIELD(id,' . implode(',', $collection->pluck('id')->toArray()) . ')'
                    );
            });
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
                ->weight('bold'),

            TextColumn::make('name')
                ->label('Business Unit')
                ->searchable(),

            TextColumn::make('volunteer_count')
                ->label('Total Volunteers')
                ->getStateUsing(function (Company $company) {
                    return User::where('company_id', $company->id)
                        ->where('volunteer', 1)
                        ->count();
                })
                ->numeric()
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->withCount(['users as volunteer_count' => function ($q) {
                        $q->where('volunteer', 1);
                    }])->orderBy('volunteer_count', $direction);
                })
                ->alignEnd(),

            TextColumn::make('total_hours')
                ->label('Total Hours')
                ->getStateUsing(function (Company $company) {
                    // Get all event attendees for this company
                    $attendees = EventAttendee::query()
                        ->join('users', 'event_attendees.attendee_id', '=', 'users.id')
                        ->where('users.company_id', $company->id)
                        ->where('encoding_type', '!=', 3)
                        ->where('is_approve', true)
                        ->whereNotNull(['time_in', 'time_out'])
                        ->get();

                    // Calculate total hours using the get_totalHrs() method
                    return $attendees->sum(function ($attendee) {
                        return $attendee->get_totalHrs();
                    });
                })
                ->sortable(query: function (Builder $query, string $direction): Builder {
                    return $query->addSelect([
                        'computed_hours' => EventAttendee::selectRaw('
                            COALESCE(SUM(
                                CASE 
                                    WHEN event_attendees.time_in IS NOT NULL AND event_attendees.time_out IS NOT NULL THEN
                                        (TIMESTAMPDIFF(HOUR, event_attendees.time_in, event_attendees.time_out) + 
                                        (DATEDIFF(event_attendees.time_out, event_attendees.time_in) * 24)) *
                                        CASE WHEN event_attendees.encoding_type = 3 THEN event_attendees.volunteer_count ELSE 1 END
                                    ELSE 0
                                END
                            ), 0)
                        ')
                        ->join('users', 'event_attendees.attendee_id', '=', 'users.id')
                        ->whereColumn('users.company_id', 'companies.id')
                        ->where('event_attendees.encoding_type', '!=', 3)
                        ->where('event_attendees.is_approve', true)
                        ->whereNotNull(['event_attendees.time_in', 'event_attendees.time_out'])
                    ])->orderBy('computed_hours', $direction);
                })
                ->alignEnd(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('period')
                ->options([
                    'month' => 'This Month',
                    'quarter' => 'This Quarter',
                    'year' => 'This Year'
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when($data['value'], function ($query, $value) {
                        return match($value) {
                            'month' => $query->whereHas('users', function($userQuery) {
                                $userQuery->where('volunteer', 1)
                                    ->whereHas('eventAttendees', function($q) {
                                        $q->whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year);
                                    });
                            }),
                            'quarter' => $query->whereHas('users', function($userQuery) {
                                $userQuery->where('volunteer', 1)
                                    ->whereHas('eventAttendees', function($q) {
                                        $q->whereRaw('QUARTER(created_at) = ?', [now()->quarter])
                                          ->whereYear('created_at', now()->year);
                                    });
                            }),
                            'year' => $query->whereHas('users', function($userQuery) {
                                $userQuery->where('volunteer', 1)
                                    ->whereHas('eventAttendees', function($q) {
                                        $q->whereYear('created_at', now()->year);
                                    });
                            }),
                            default => $query
                        };
                    });
                }),
        ];
    }
}
