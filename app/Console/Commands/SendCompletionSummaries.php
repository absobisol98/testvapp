<?php

namespace App\Console\Commands;

use App\Models\EventAttendee;
use App\Models\EventSlot;
use App\Notifications\PostEventReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendCompletionSummaries extends Command
{
    protected $signature   = 'events:send-completion-summaries';
    protected $description = 'Send completion summary emails to volunteers ~1 hour after their shift ends';

    public function handle(): int
    {
        $now        = Carbon::now();
        $today      = $now->toDateString();
        $sentCount  = 0;
        $errorCount = 0;

        // Find slots that ended within the last 90 minutes (generous window for cron jitter)
        $cutoffFrom = $now->copy()->subMinutes(90)->format('H:i:s');
        $cutoffTo   = $now->copy()->subMinutes(30)->format('H:i:s');

        // Slots with explicit shift_end_date = today and end_time in the window
        $slots = EventSlot::with(['event'])
            ->where(function ($q) use ($today, $cutoffFrom, $cutoffTo) {
                $q->where('shift_end_date', $today)
                  ->whereBetween('end_time', [$cutoffFrom, $cutoffTo]);
            })
            ->orWhere(function ($q) use ($today, $cutoffFrom, $cutoffTo) {
                // Slots without shift_end_date — fall back to shift_date = today
                $q->whereNull('shift_end_date')
                  ->where('shift_date', $today)
                  ->whereBetween('end_time', [$cutoffFrom, $cutoffTo]);
            })
            ->get();

        foreach ($slots as $slot) {
            $event = $slot->event;

            // Get attendees who have time_in recorded (they actually showed up)
            $attendees = EventAttendee::with('attendee')
                ->where('event_id', $event->id)
                ->where('slot_type_id', $slot->id)
                ->whereNotNull('time_in')
                ->get();

            foreach ($attendees as $attendee) {
                $volunteer = $attendee->attendee;
                if (! $volunteer) {
                    continue;
                }

                try {
                    $volunteer->notify(new PostEventReminder($event, $attendee));
                    $sentCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    Log::error('Completion summary failed', [
                        'volunteer_id' => $volunteer->id,
                        'event_id'     => $event->id,
                        'slot_id'      => $slot->id,
                        'error'        => $e->getMessage(),
                    ]);
                }
            }
        }

        $this->info("Completion summaries sent: {$sentCount}, errors: {$errorCount}");

        return self::SUCCESS;
    }
}
