<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventSlot;
use App\Notifications\PreEventReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendShiftReminders extends Command
{
    protected $signature   = 'events:send-shift-reminders';
    protected $description = 'Send shift reminders to volunteers 24 hours before their shift';

    public function handle(): int
    {
        $tomorrow   = Carbon::tomorrow()->toDateString();
        $sentCount  = 0;
        $errorCount = 0;

        // Slots with explicit shift_date set to tomorrow
        $slots = EventSlot::with(['event', 'registrations.volunteer'])
            ->where('shift_date', $tomorrow)
            ->get();

        // Events without slot-level dates whose start_date is tomorrow
        if ($slots->isEmpty()) {
            $events = Event::with(['slots.registrations.volunteer'])
                ->whereDate('start_date', $tomorrow)
                ->whereDoesntHave('slots', fn ($q) => $q->whereNotNull('shift_date'))
                ->get();

            foreach ($events as $event) {
                foreach ($event->slots as $slot) {
                    $slots->push($slot->setRelation('event', $event));
                }
            }
        }

        foreach ($slots as $slot) {
            $event = $slot->event;

            foreach ($slot->registrations as $registration) {
                $volunteer = $registration->volunteer;
                if (! $volunteer) {
                    continue;
                }

                try {
                    $volunteer->notify(new PreEventReminder($event, $slot));
                    $sentCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error('Shift reminder failed', [
                        'volunteer_id' => $volunteer->id,
                        'event_id'     => $event->id,
                        'slot_id'      => $slot->id,
                        'error'        => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Shift reminders sent: {$sentCount}, errors: {$errorCount}");

        return self::SUCCESS;
    }
}
