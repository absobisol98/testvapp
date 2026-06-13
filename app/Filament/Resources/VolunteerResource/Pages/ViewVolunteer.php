<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use App\Models\Event;
use App\Models\EventAttendee;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Actions\EditAction;
use App\Models\User;
use App\Actions\GenerateEventQRCode;
use App\Models\BusinessUnit;

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
            Actions\EditAction::make()
            ->label('Edit Profile')
            ->icon('heroicon-o-pencil')
            ->url(fn () => VolunteerResource::getUrl('edit', ['record' => $this->record]))
            ->visible(fn () => auth()->id() == $this->record || auth()->user()->hasRole('Ayala Super Admin'))
            ->color('warning'),
        ];

    }

    protected function getViewData(): array
    {
        $user = User::where('id', $this->record)->first();

        // Calculate badge-related metrics
        $totalHours = $user->getTotalHours();
        $totalOpportunities = $user->eventAttended()->count();
        $currentStreak = $user->calculateStreak();

        // Calculate next goals
        $hourThresholds = [4, 8, 12, 16, 20];
        $nextHourGoal = collect($hourThresholds)->first(function($threshold) use ($totalHours) {
            return $threshold > $totalHours;
        }) ?? end($hourThresholds);

        $oppThresholds = [10, 20, 30];
        $nextOppGoal = collect($oppThresholds)->first(function($threshold) use ($totalOpportunities) {
            return $threshold > $totalOpportunities;
        }) ?? end($oppThresholds);

        // Get badges and progress
        $badges = $user->getBadges();

        return [
            'businessunit' => BusinessUnit::count(),
            'user' => $user,
            'volunteer' => User::role('volunteer')->count(),
            'opportunity' => Event::with('slots', 'tags', 'program')
                ->orderBy('created_at', 'desc')
                ->first(),
            'allEvents' => Event::with('slots')
                ->whereHas('attendees', function ($query) {
                    $query->where('attendee_id', $this->record);
                })
                ->get(),
            'favoriteEvents' => Event::with('slots')
                ->whereHas('attendees', function ($query) {
                    $query->where('attendee_id', $this->record);
                })
                ->get(),
            'bgImg' => 'img/ayala-foundation-bg-2.jpg',
            'badges' => $badges,
            'totalHours' => $totalHours,
            'totalOpportunities' => $totalOpportunities,
            'currentStreak' => $currentStreak,
            'nextHourGoal' => $nextHourGoal,
            'nextOppGoal' => $nextOppGoal,
        ];
    }

}
