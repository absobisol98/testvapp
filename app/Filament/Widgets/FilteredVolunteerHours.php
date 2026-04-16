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
use Illuminate\Support\Facades\Auth;
class FilteredVolunteerHours extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

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

    public function table(Table $table): Table
    {
        return $table
        ->heading('Volunteer Hours')
            ->query(
                EventAttendee::query()
                    ->with(['attendee', 'slot', 'event'])
                    ->whereNotNull(['time_in', 'time_out'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Opportunity')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('attendee.company.name')
                    ->label('Business Unit')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('attendee.name')
                    ->label('Volunteer')
                    ->searchable(['firstname', 'lastname'])
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->join('users', 'event_attendees.attendee_id', '=', 'users.id')
                            ->orderBy('users.firstname', $direction)
                            ->orderBy('users.lastname', $direction)
                            ->select('event_attendees.*');
                    })
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('slot.shift_name')
                    ->label('Shift Name')
                    ->searchable()
                    ->sortable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('total_hours')
                    ->label('Total Hours')
                    ->getStateUsing(function(EventAttendee $record) {
                        return $record->get_totalHrs();
                    })
                    ->numeric()
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw("
                            CASE 
                                WHEN time_in IS NOT NULL AND time_out IS NOT NULL THEN
                                    (TIMESTAMPDIFF(HOUR, time_in, time_out) + (DATEDIFF(time_out, time_in) * 24)) *
                                    CASE WHEN encoding_type = 3 THEN volunteer_count ELSE 1 END
                                ELSE 0
                            END {$direction}
                        ");
                    }),

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
                    ->relationship('attendee.company', 'name')
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
