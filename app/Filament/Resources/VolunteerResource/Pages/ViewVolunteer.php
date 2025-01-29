<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use App\Models\Event;
use Filament\Resources\Pages\Page;
use Filament\Actions\EditAction;
use App\Models\User;

class ViewVolunteer extends Page
{
    protected static string $resource = VolunteerResource::class;

    protected static string $view = 'filament.resources.volunteer-resource.pages.view-volunteer';
    

    public $record;
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['user_id'] = $this->record;
        $data['badges'] = $this->record->getBadges(); 
        return $data;
    }


    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];

    }

    protected function getViewData(): array
    {
    
        
        $user = User::where('id', $this->record)->first();
        $opportunity = Event::with('slots', 'tags', 'program')
            ->orderBy('created_at', 'desc')
            ->first();

        $allEvents = Event::with('slots')
            ->whereHas('attendees', function ($query) {
                $query->where('attendee_id', $this->record);
            })
            ->get();
        $favoriteEvents = Event::with('slots')
            ->whereHas('attendees', function ($query) {
                $query->where('attendee_id', $this->record);
            })
            ->get();
        
        $bgImg = 'img/ayala-foundation-bg-2.jpg';

        return [
            'user' => $user,
            'opportunity' => $opportunity,
            'allEvents' => $allEvents,
            'favoriteEvents' => $favoriteEvents,
            'bgImg' => $bgImg,
            'badges' => $user->getBadges()
        ];
    }

}
