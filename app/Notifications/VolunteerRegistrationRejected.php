<?php

namespace App\Notifications;

use App\Mail\VolunteerRegistrationRejectedEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VolunteerRegistrationRejected extends Notification
{
    use Queueable;

    public function __construct(
        public $event,
        public $eventRegistration = null,
        public $message = null
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(VolunteerRegistrationRejectedEmail::class, [
            'user'              => $notifiable,
            'event'             => $this->event,
            'eventRegistration' => $this->eventRegistration,
            'message'           => $this->message,
        ]);
    }
}
