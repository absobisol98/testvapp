<?php

namespace App\Notifications;

use App\Mail\VolunteerRegistrationConfirmedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VolunteerRegistrationConfirmed extends Notification
{
    use Queueable;

    public function __construct(
        public $event,
        public $eventRegistration = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(VolunteerRegistrationConfirmedEmail::class, [
            'user'              => $notifiable,
            'event'             => $this->event,
            'eventRegistration' => $this->eventRegistration,
        ]);
    }
}
