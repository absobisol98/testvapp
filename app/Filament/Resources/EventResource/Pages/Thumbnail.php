<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\View;

class Thumbnail extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected static ?string $title = 'Thumbnail';

    protected static string $view = 'filament.resources.event-resource.pages.thumbnail';

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                View::make('filament.tables.columns.event-thumbnail'),
            ])
            ->filters([

            ])
            ->actions([
            ])
            ->bulkActions([
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('id', 'desc')
            ->paginated([12, 24, 48, 120, 'all']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('List')
                ->icon('heroicon-o-list-bullet')
                ->url(route('filament.admin.resources.events.index')),
            Action::make('Calendar')
                ->icon('heroicon-o-calendar-date-range')
                ->url(route('filament.admin.resources.events.calendar')),
            CreateAction::make()
                ->label('Event')
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.events.create')),
        ];
    }
}
