<?php

namespace App\Filament\Widgets;

use App\Exports\VolunteerHoursExport;
use App\Models\EventAttendee;
use App\Models\Company;
use App\Models\Volunteer;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Get;

class FilteredVolunteerHours extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
        ->heading('Volunteer Hours')
            ->query(
                EventAttendee::query()
                    ->with(['attendee', 'event.companies', 'slot'])
                    ->whereNotNull(['time_in', 'time_out'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Opportunity')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('event.companies.name')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('attendee.name')
                    ->label('Volunteer')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('slot.shift_name')
                    ->label('Shift Name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('Total Hours')
                    ->getStateUsing(function(EventAttendee $record) {
                        return $record->get_totalHrs();
                    })
                    ->numeric(
                        decimalPlaces: 2,
                        thousandsSeparator: ',',
                    ),
                Tables\Columns\TextColumn::make('time_in')
                    ->label('Time In')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('time_out')
                    ->label('Time Out')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('N/A'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->label('Opportunity')
                    ->multiple()
                    ->preload(),


                Tables\Filters\SelectFilter::make('company')
                    ->relationship('event.companies', 'name')
                    ->multiple()
                    ->preload(),

                Tables\Filters\SelectFilter::make('affiliate_type')
                    ->label('Affiliation')
                    ->options([
                        1 => 'Ayala',
                        2 => 'Non-Ayala',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function ($query, $value) {
                            return $query->whereHas('attendee', fn ($q) =>
                                $q->where('affiliate_type_id', $value)
                            );
                        });
                    }),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        DatePicker::make('from_date'),
                        DatePicker::make('to_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('time_in', '>=', $date)
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('time_in', '<=', $date)
                            );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export')
                    ->label('Export Selected')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function ($records) {
                        return Excel::download(
                            new VolunteerHoursExport($records),
                            'volunteer-hours-' . date('Y-m-d') . '.csv'
                        );
                    })
            ]);
    }
}
