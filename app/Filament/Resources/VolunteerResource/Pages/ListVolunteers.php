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
        if(auth()->user()->company?->cluster->name == "Ayala Corporation Group"){ // Ayala
            $events = Event::query()
                ->leftJoin('event_companies','event_companies.event_id','=','events.id')
                ->where('start_date', '>', now()->subDay()->endOfDay())
                ->where(function ($query) {
                    $query->where('event_type_id', 1)
                        ->orWhere('event_companies.company_id', 1);
                })->select('events.*');
        }else{
            $events = (new (static::$resource::getModel()))->where('start_date', '>', now()->subDay());

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
