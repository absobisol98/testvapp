<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventCreateAction;
use Filament\Actions\Action;
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

    protected function getCreateAnotherFormAction(): Action
    {
       return parent::getCreateAnotherFormAction()
            ->label('Save & Create Another');
    }
}
