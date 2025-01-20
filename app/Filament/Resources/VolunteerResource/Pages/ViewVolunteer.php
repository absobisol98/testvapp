<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewVolunteer extends ViewRecord
{
    protected static string $resource = VolunteerResource::class;



    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['badges'] = $this->record->getBadges(); 
        return $data;
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

}
