<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use App\Models\Event;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListVolunteers extends ListRecords
{
    protected static string $resource = VolunteerResource::class;

    protected function getTableQuery(): ?Builder
    {
        if(auth()->user()->hasRole(['super_admin'])){ // Super admin
            $events = (new (static::$resource::getModel()));
        }else{
            $events = (new (static::$resource::getModel()))->where('id',auth()->id());
        }

        return $events;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Volunteers'),
        ];
    }
}
