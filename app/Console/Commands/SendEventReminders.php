<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Notifications\PreEventReminder;
use App\Notifications\PostEventReminder;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders {--dry-run : Preview notifications without sending them}';
    protected $description = 'Send pre and post event reminders';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info('DRY RUN MODE: No notifications will be sent');
        }

        $this->sendDayBeforeReminders($isDryRun);
        $this->sendPostEventReminders($isDryRun);

        $this->info('Event reminders ' . ($isDryRun ? 'dry run completed' : 'sent successfully') . '!');
    }

    /**
     * Send reminders 1 day before events
     */
    private function sendDayBeforeReminders($isDryRun = false)
    {
        // Tomorrow's date
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Find events starting tomorrow
        $upcomingEvents = Event::whereDate('start_date', $tomorrow)->get();

        $this->info("Found " . $upcomingEvents->count() . " events starting tomorrow");

        foreach ($upcomingEvents as $event) {
            $this->info("Processing event: {$event->title}");

            foreach ($event->registrations as $registration) {
                // Check if user relationship exists before accessing properties
                if ($registration->volunteer) {
                    if (!$isDryRun) {
                        $registration->notify(new PreEventReminder($event));
                    }
                    $this->line(($isDryRun ? "[DRY RUN] Would send" : "Sent") . " pre-event reminder to {$registration->volunteer->name} for event: {$event->title}");
                } else {
                    $this->warn("Skipping notification for registration ID {$registration->id} - volunteer not found");
                }
            }
        }
    }

    /**
     * Send post-event reminders for events that ended yesterday
     */
    private function sendPostEventReminders($isDryRun = false)
    {
        // Yesterday's date (for post-event reminders)
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        // Find events that ended yesterday
        $endedEvents = Event::whereDate('end_date', $yesterday)->get();

        $this->info("Found " . $endedEvents->count() . " events that ended yesterday");

        foreach ($endedEvents as $event) {
            $this->info("Processing event: {$event->title}");

            foreach ($event->registrations as $registration) {
                // Check if user relationship exists before accessing properties
                if ($registration->volunteer) {
                    if (!$isDryRun) {
                        $registration->notify(new PostEventReminder($event));
                    }
                    $this->line(($isDryRun ? "[DRY RUN] Would send" : "Sent") . " post-event reminder to {$registration->volunteer->name} for event: {$event->title}");
                } else {
                    $this->warn("Skipping notification for registration ID {$registration->id} - volunteer not found");
                }
            }
        }
    }
}
