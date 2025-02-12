<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PreEventReminder extends Notification
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        try {
            Log::info('Building mail message', [
                'event' => $this->event->title,
                'recipient' => $notifiable->volunteer->email
            ]);

            Mail::send('emails.pre-event-reminder', [
                'event' => $this->event,
                'registration' => $notifiable->volunteer,
                'startTime' => \Carbon\Carbon::parse($this->event->start_date)->format('g:i A'),
                'startDate' => \Carbon\Carbon::parse($this->event->start_date)->format('F d, Y')
            ], function($message) use ($notifiable) {
                $message->to($notifiable->volunteer->email)
                       ->subject('Reminder: ' . $this->event->title . ' Starting Soon');
            });

            // Return empty MailMessage to satisfy notification contract
            return new MailMessage;

        } catch (\Exception $e) {
            Log::error('Mail send failed', [
                'error' => $e->getMessage(),
                'event' => $this->event->id,
                'recipient' => $notifiable->volunteer->email
            ]);
            throw $e;
        }
    }
}

