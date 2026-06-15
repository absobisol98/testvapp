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

    public static function canAccess(array $parameters = []): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        // Volunteers cannot manage volunteers
        if ($user->hasActiveRole('Volunteer')) {
            return false;
        }

        // Admins (including Ayala Super Admin) have global access
        if ($user->isAdminRole() || $user->isSuperAdmin()) {
            return true;
        }

        // Facilitators can only manage events they are assigned to
        if ($user->hasActiveRole('Facilitator')) {
            $record = $parameters['record'] ?? null;
            if (! $record instanceof Event) {
                return true;
            }
            return $record->facilitators()->where('facilitator_id', $user->id)->exists();
        }

        return true;
    }

    public function mount(int|string $record): void
    {
        // Bypass global scopes so drafts and filtered events are accessible
        $this->record = Event::withoutGlobalScopes()->findOrFail($record);

        static::authorizeAccess();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
