<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventsGetTableQueryAction;
use App\Filament\Resources\EventResource;
use App\Filament\Resources\EventResource\Widgets\EventsToApprove;
use App\Models\Company;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getTableQuery(): ?Builder
    {
        $events = (new EventsGetTableQueryAction())->execute(auth()->user());

        return $events;
    }

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


    protected function getFooterWidgets(): array
    {
        return [
            EventsToApprove::class,
        ];
    }

 

}
