<?php
namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\EventAttendee;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopCompaniesLeaderboard extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Company::query()
            ->withCount(['eventAttendees as volunteer_count' => function($query) {
                $query->where('encoding_type', '!=', 3)
                      ->where('is_approve', true)
                      ->whereNotNull(['time_in', 'time_out']);
            }])
            ->addSelect([
                'companies.*',
                'total_hours' => EventAttendee::query()
                    ->whereColumn('company_id', 'companies.id')
                    ->where('encoding_type', '!=', 3)
                    ->where('is_approve', true)
                    ->whereNotNull(['time_in', 'time_out'])
                    ->selectRaw('COALESCE(SUM(TIMESTAMPDIFF(HOUR, time_in, time_out)), 0)')
            ])
            ->having('volunteer_count', '>', 0)
            ->orderByDesc('total_hours');
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
                ->searchable()
                ->sortable(),

            TextColumn::make('volunteer_count')
                ->label('Total Volunteers')
                ->sortable()
                ->alignEnd(),

            TextColumn::make('total_hours')
                ->label('Total Hours')
                ->numeric(
                    decimalPlaces: 1,
                    thousandsSeparator: ',',
                )
                ->sortable()
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
                            'month' => $query->whereHas('users.eventAttendees', fn($q) =>
                                $q->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)),
                            'quarter' => $query->whereHas('users.eventAttendees', fn($q) =>
                                $q->whereBetween('created_at', [now()->startOfQuarter(), now()->endOfQuarter()])),
                            'year' => $query->whereHas('users.eventAttendees', fn($q) =>
                                $q->whereYear('created_at', now()->year)),
                            default => $query
                        };
                    });
                }),
        ];
    }
}
