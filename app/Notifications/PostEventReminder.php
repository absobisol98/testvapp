<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PostEventReminder extends Notification
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
            Log::info('Building post-event mail', [
                'event' => $this->event->title,
                'recipient' => $notifiable->volunteer->email
            ]);

            Mail::send('emails.post-event-reminder', [
                'event' => $this->event,
                'registration' => $notifiable->volunteer,
                'endTime' => \Carbon\Carbon::parse($this->event->end_date)->format('g:i A'),
                'endDate' => \Carbon\Carbon::parse($this->event->end_date)->format('F d, Y')
            ], function($message) use ($notifiable) {
                $message->to($notifiable->volunteer->email)
                       ->subject('Reminder: ' . $this->event->title . ' Ending Soon');
            });

            // Return empty MailMessage to satisfy notification contract
            return new MailMessage;

        } catch (\Exception $e) {
            Log::error('Post-event mail failed', [
                'error' => $e->getMessage(),
                'event' => $this->event->id,
                'recipient' => $notifiable->volunteer->email
            ]);
            throw $e;
        }
    }
}
