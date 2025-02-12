<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Notifications\PreEventReminder;
use App\Notifications\PostEventReminder;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send pre and post event reminders';

    public function handle()
    {
        $now = Carbon::now();

        // Find events starting in 15 minutes
        $preEventReminders = Event::where('start_date', $now->copy()->addMinutes(15)->toDateString())
            ->where('start_date', $now->copy()->addMinutes(15)->format('H:i:s'))
            ->get();

        foreach ($preEventReminders as $event) {
            foreach ($event->registrations as $registration) {
                $registration->notify(new PreEventReminder($event));
            }
        }

        // Find events ending in 15 minutes
        $postEventReminders = Event::where('end_date', $now->copy()->addMinutes(15)->toDateString())
            ->where('end_date', $now->copy()->addMinutes(15)->format('H:i:s'))
            ->get();

        foreach ($postEventReminders as $event) {
            foreach ($event->registrations as $registration) {
                $registration->notify(new PostEventReminder($event));
            }
        }

        $this->info('Event reminders sent successfully!');
    }
}
