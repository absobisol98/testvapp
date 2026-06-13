<?php

namespace App\Notifications;

use App\Mail\VolunteerCompletionSummaryEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PostEventReminder extends Notification
{
    use Queueable;

    public function __construct(
        public $event,
        public $eventAttendee = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(VolunteerCompletionSummaryEmail::class, [
            'user'         => $notifiable,
            'event'        => $this->event,
            'eventAttendee' => $this->eventAttendee,
        ]);
    }
}
