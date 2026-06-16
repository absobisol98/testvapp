<?php

namespace App\Notifications;

use App\Mail\VolunteerRegistrationApprovedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VolunteerRegistrationApproved extends Notification
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
        return app(VolunteerRegistrationApprovedEmail::class, [
            'user'              => $notifiable,
            'event'             => $this->event,
            'eventRegistration' => $this->eventRegistration,
        ]);
    }
}
