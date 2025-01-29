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
                User::query()
            )
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('firstname')
                    ->label('Partner')
                    ->searchable()->sortable(),
                TextColumn::make('email')
                    ->searchable()->sortable(),
                // ToggleColumn::make('is_active')
                //     ->onColor('success')
                //     ->offColor('danger')
            ])
            ->actions([
                Action::make('view')
                    ->icon('heroicon-s-eye')
                    ->action(function (User $record) {}),
                Action::make('edit')
                    ->icon('heroicon-s-pencil')
                    ->action(function (User $record) {}),
            ]);
    }
}
