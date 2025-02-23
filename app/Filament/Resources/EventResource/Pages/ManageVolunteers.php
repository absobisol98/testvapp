<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use App\Models\Event;

class ManageVolunteers extends Page
{
    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.manage-volunteers';

    public Event $record;

    public function mount(Event $record): void
    {
        $this->record = $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            // Add any header actions you want
        ];
    }
}
