<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Actions\Action::make('Calendar')
                ->icon('heroicon-o-calendar-date-range')
                ->url(route('filament.admin.resources.events.calendar')),
            Actions\Action::make('Thumbnail')
                ->icon('heroicon-o-photo')
                ->url(route('filament.admin.resources.events.thumbnail')),
            Actions\CreateAction::make()
                ->label('Event')
                ->icon('heroicon-o-plus'),
        ];
    }
}
