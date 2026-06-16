<?php

namespace App\Notifications;

use App\Mail\VolunteerRegistrationCancelledEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VolunteerRegistrationCancelled extends Notification
{
    use Queueable;

    public function __construct(
        public $event
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(VolunteerRegistrationCancelledEmail::class, [
            'user'  => $notifiable,
            'event' => $this->event,
        ]);
    }
}
