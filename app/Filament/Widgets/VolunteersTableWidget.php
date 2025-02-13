<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class VolunteersTableWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::role('volunteer')
            )
            ->columns([
                TextColumn::make('firstname')
                    ->label('First Name')
                    ->searchable()->sortable(),
                TextColumn::make('lastname')
                    ->label('Last Name')
                    ->searchable()->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()->sortable(),
                TextColumn::make('birthday')
                    ->label('Birthday')
                    ->searchable()->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->label('Created At')
                    ->searchable()->sortable(),

            ])
            ->actions([
                Action::make('view')
                ->label('View')
                ->icon('heroicon-s-pencil')
                ->url(fn ($record) => route('filament.admin.resources.volunteers.view', $record)),
                // Action::make('edit')
                //     ->icon('heroicon-s-pencil')
                //     ->action(function (User $record) {}),
            ]);
    }
}
