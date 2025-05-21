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
            ->visible(fn () => auth()->id() == $this->record || auth()->user()->hasRole(['super_admin','admin', 'Ayala Super Admin', 'External Partner']))
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

        // Get badges and progress
        $badges = $user->getBadges();

        // Check if user has milestones based on badges
        // This assumes your badge system stores information about completed milestones
        $has20HourBadge = false;

        // Loop through badges to check for 20-hour milestone
        // Adjust this logic based on your badge data structure
        if (isset($badges['badges']) && is_array($badges['badges'])) {
            foreach ($badges['badges'] as $badge) {
                if (isset($badge['name']) && strpos(strtolower($badge['name']), '20 hour') !== false) {
                    $has20HourBadge = true;
                    break;
                }
            }
        }

        // Define milestones that are completed
        $completedHourMilestones = [];

        // Add 20-hour milestone if badge exists or actual hours >= 20
        if ($has20HourBadge || $totalHours >= 20) {
            $completedHourMilestones[] = 20;
        }

        // Add other milestones based on actual hours
        foreach ([100, 250, 500, 1000] as $milestone) {
            if ($totalHours >= $milestone) {
                $completedHourMilestones[] = $milestone;
            }
        }

        // Define completed opportunity milestones
        $completedOpportunityMilestones = [];
        foreach ([5, 10, 25, 50, 100] as $milestone) {
            if ($totalOpportunities >= $milestone) {
                $completedOpportunityMilestones[] = $milestone;
            }
        }

        // Calculate next goals (using actual hours)
        $hourThresholds = [4, 8, 12, 16, 20];
        $nextHourGoal = collect($hourThresholds)->first(function($threshold) use ($totalHours) {
            return $threshold > $totalHours;
        }) ?? end($hourThresholds);

        $oppThresholds = [10, 20, 30];
        $nextOppGoal = collect($oppThresholds)->first(function($threshold) use ($totalOpportunities) {
            return $threshold > $totalOpportunities;
        }) ?? end($oppThresholds);

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
            'completedHourMilestones' => $completedHourMilestones,
            'completedOpportunityMilestones' => $completedOpportunityMilestones,
        ];
    }

}
