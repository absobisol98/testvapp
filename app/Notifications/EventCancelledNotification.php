<?php

namespace App\Notifications;

use App\Mail\EventCancelledEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(public $event) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return app(EventCancelledEmail::class, [
            'user'  => $notifiable,
            'event' => $this->event,
        ]);
    }
}
