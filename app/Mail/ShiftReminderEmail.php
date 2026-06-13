<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Visualbuilder\EmailTemplates\Traits\BuildGenericEmail;

class ShiftReminderEmail extends Mailable
{
    use Queueable, SerializesModels, BuildGenericEmail;

    public string $template = 'volunteer-shift-reminder';
    public string $sendTo;
    public $user;
    public $event;
    public $eventSlot;

    public function __construct($user, $event, $eventSlot = null)
    {
        $this->user      = $user;
        $this->event     = $event;
        $this->eventSlot = $eventSlot;
        $this->sendTo    = $user->email;
    }
}
