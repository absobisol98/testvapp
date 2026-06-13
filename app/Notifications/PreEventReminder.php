<?php

namespace App\Notifications;

use App\Mail\ShiftReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PreEventReminder extends Notification
{
    use Queueable;

    public function __construct(
        public $event,
        public $eventSlot = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(ShiftReminderEmail::class, [
            'user'      => $notifiable,
            'event'     => $this->event,
            'eventSlot' => $this->eventSlot,
        ]);
    }
}
