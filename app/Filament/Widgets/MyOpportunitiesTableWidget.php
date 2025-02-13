<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class MyOpportunitiesTableWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->html()
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('approval_type')
                    ->searchable()
                    ->sortable(),
                    TextColumn::make('start_date')
                    ->label('Event Date')
                    ->formatStateUsing(function ($record) {
                        return \Carbon\Carbon::parse($record->start_date)->format('M d, Y') .
                               ' - ' .
                               \Carbon\Carbon::parse($record->end_date)->format('M d, Y');
                    })
                    ->searchable()
                    ->sortable(),

            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-s-eye')
                    ->url(fn ($record) => route('filament.admin.resources.events.view', $record)),
                Action::make('edit')
                    ->icon('heroicon-s-pencil')
                    ->url(fn ($record) => route('filament.admin.resources.events.edit', $record)),
            ]);
    }
}
