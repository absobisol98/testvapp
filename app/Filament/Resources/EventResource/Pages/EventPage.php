<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use App\Models\Event;

class EventPage extends Page
{
    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.event';

    public function mount($record): void
    {
        $this->record = Event::findOrFail($record);
        // dd($this->record->point_of_contact);
        // dd($this->record->event_recurring);
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
        ];
    }
}
