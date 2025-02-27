<?php

namespace App\Filament\Widgets;

use App\Models\BusinessUnit;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class PartnersTableWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
        ->query(
            BusinessUnit::query()
        )
        ->columns([
            TextColumn::make('name')
                ->label('Partner')
                ->searchable()->sortable(),
            TextColumn::make('nickname')
                ->label('Abbreviation')
                ->searchable()->sortable(),
            TextColumn::make('created_by')
                ->label('Created By')
                ->searchable()->sortable(),
            TextColumn::make('created_at')
                ->label('Created At')
                ->searchable()->sortable(),
        ])
        ->actions([
            Action::make('edit')
                ->label('Edit')
                ->icon('heroicon-s-pencil')
                ->url(fn ($record) => route('filament.admin.resources.business-units.edit', ['record' => $record])),
        ]);
    }
}
