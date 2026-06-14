<?php

namespace App\Observers;

use App\Models\EventRegistration;
use App\Notifications\VolunteerRegistrationConfirmed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EventRegistrationObserver
{
    public function created(EventRegistration $eventRegistration): void
    {
        $cacheKey = 'reg_email_sent_' . $eventRegistration->id;
        if (Cache::has($cacheKey)) {
            return;
        }
        Cache::put($cacheKey, true, now()->addMinutes(5));

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
