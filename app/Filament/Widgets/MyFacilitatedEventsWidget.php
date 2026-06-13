<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyFacilitatedEventsWidget extends BaseWidget
{
    protected static ?string $heading = 'Events I Facilitate';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
                    ->whereHas('facilitators', fn ($q) => $q->where('users.id', auth()->id()))
                    ->orderBy('start_date', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registrations_count')
                    ->counts('registrations')
                    ->label('Registered'),
                Tables\Columns\TextColumn::make('attendees_count')
                    ->counts('attendees')
                    ->label('Attended'),
                Tables\Columns\BadgeColumn::make('event_status')
                    ->getStateUsing(fn (Event $record) => match (true) {
                        $record->end_date?->isPast()   => 'Completed',
                        $record->start_date?->isPast() => 'Ongoing',
                        default                        => 'Upcoming',
                    })
                    ->colors([
                        'success' => 'Completed',
                        'warning' => 'Ongoing',
                        'primary' => 'Upcoming',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('manage')
                    ->label('Manage Attendance')
                    ->icon('heroicon-o-user-group')
                    ->url(fn (Event $record) => route('filament.admin.resources.events.edit', ['record' => $record])),
            ])
            ->emptyStateHeading('No events assigned')
            ->emptyStateDescription('You have not been nominated to facilitate any events yet.');
    }
}
