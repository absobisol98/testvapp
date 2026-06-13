<?php

namespace App\Observers;

use App\Models\EventRegistration;
use App\Notifications\VolunteerRegistrationConfirmed;
use Illuminate\Support\Facades\Log;

class EventRegistrationObserver
{
    public function created(EventRegistration $eventRegistration): void
    {
        try {
            $volunteer = $eventRegistration->volunteer;
            $event     = $eventRegistration->event;

            if ($volunteer && $event) {
                $volunteer->notify(new VolunteerRegistrationConfirmed($event, $eventRegistration));
            }
        } catch (\Exception $e) {
            Log::error('Registration confirmation email failed', [
                'registration_id' => $eventRegistration->id,
                'error'           => $e->getMessage(),
            ]);
        }
    }

    public function updated(EventRegistration $eventRegistration): void
    {
        //
    }

    public function deleted(EventRegistration $eventRegistration): void
    {
        //
    }

    public function restored(EventRegistration $eventRegistration): void
    {
        //
    }

    public function forceDeleted(EventRegistration $eventRegistration): void
    {
        //
    }
}
