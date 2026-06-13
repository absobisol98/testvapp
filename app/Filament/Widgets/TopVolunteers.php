<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Company;
use App\Models\EventRegistration;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class TopVolunteers extends BaseWidget
{
    protected static ?int $sort = 3;

    protected function getTableQuery(): Builder
    {
        $query = User::query()
            ->withCount('eventRegistrations')
            ->withSum('eventRegistrations', 'hours_rendered')
            ->with('companies');

        // If user is not super admin, only show volunteers from their companies
        if (!auth()->user()->hasActiveRole('Ayala Super Admin')) {
            $adminCompanyIds = auth()->user()->adminCompanies()->pluck('companies.id');
            $query->whereHas('companies', function ($query) use ($adminCompanyIds) {
                $query->whereIn('companies.id', $adminCompanyIds);
            });
        }

        return $query->orderByDesc('event_registrations_sum_hours_rendered')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            TextColumn::make('companies.name')
                ->label('Business Units')
                ->formatStateUsing(fn ($record) => $record->companies->pluck('name')->join(', '))
                ->searchable()
                ->sortable(),
            TextColumn::make('eventRegistrations_count')
                ->label('Total Events')
                ->sortable(),
            TextColumn::make('event_registrations_sum_hours_rendered')
                ->label('Total Hours')
                ->numeric()
                ->sortable(),
        ];
    }

    protected function getTableFilters(): array
    {
        $companyOptions = auth()->user()->hasActiveRole('Ayala Super Admin')
            ? Company::pluck('name', 'id')
            : auth()->user()->adminCompanies()->pluck('name', 'id');

        return [
            SelectFilter::make('companies')
                ->label('Business Unit')
                ->multiple()
                ->preload()
                ->options($companyOptions)
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['values'],
                        fn (Builder $q) => $q->whereHas('companies',
                            fn ($q) => $q->whereIn('companies.id', $data['values'])
                        )
                    );
                }),

            SelectFilter::make('affiliate_type')
                ->options([
                    1 => 'Ayala',
                    2 => 'Non-Ayala',
                ])
                ->label('Affiliate Type'),

            // ... existing date range filter ...
        ];
    }
}
