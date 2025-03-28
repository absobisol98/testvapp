<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpLoginNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Ayala Workflow Login OTP')
            ->line('You are receiving this email because we received a login request for your account.')
            ->line('Your One-Time Password (OTP) is:')
            ->line($this->otp)
            ->line('This OTP will expire in 10 minutes.')
            ->line('If you did not request a login, no further action is required.');
    }
}
