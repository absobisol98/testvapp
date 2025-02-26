<?php

namespace App\Filament\Widgets;

use App\Models\Company;
use App\Models\EventAttendee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;

class BusinessUnitParticipationList extends BaseWidget
{
    protected function getTableQuery(): Builder|Relation|null
    {
        return Company::query()
            ->withCount(['events as total_opportunities'])
            ->withCount(['eventAttendees as total_volunteers' => function($query) {
                $query->select(DB::raw('COUNT(DISTINCT attendee_id)'));
            }])
            ->addSelect([
                'total_hours' => EventAttendee::query()
                    ->join('events', 'events.id', '=', 'event_attendees.event_id')
                    ->whereColumn('events.company_id', 'companies.id')
                    ->whereNotNull(['time_in', 'time_out'])
                    ->selectRaw('SUM(
                        CASE
                            WHEN encoding_type = 3
                            THEN TIME_TO_SEC(TIMEDIFF(time_out, time_in))/3600 * volunteer_count
                            ELSE TIME_TO_SEC(TIMEDIFF(time_out, time_in))/3600
                        END
                    )')
            ])
            ->having('total_opportunities', '>', 0)
            ->orderByDesc('total_hours');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Business Unit')
                ->sortable()
                ->searchable(),

            TextColumn::make('total_opportunities')
                ->label('Total Events')
                ->sortable()
                ->alignEnd(),

            TextColumn::make('total_volunteers')
                ->label('Total Volunteers')
                ->sortable()
                ->alignEnd(),

            TextColumn::make('total_hours')
                ->label('Total Hours')
                ->numeric(2)
                ->sortable()
                ->alignEnd(),
        ];
    }
}
