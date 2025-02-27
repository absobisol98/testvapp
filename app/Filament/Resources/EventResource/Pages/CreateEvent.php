<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventCreateAction;
use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function handleRecordCreation(array $data): Model
    {
      return (new EventCreateAction())->execute($data);
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.events.index');
    }
}
