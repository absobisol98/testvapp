<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Visualbuilder\EmailTemplates\Traits\BuildGenericEmail;

class EventCancelledEmail extends Mailable
{
    use Queueable, SerializesModels, BuildGenericEmail;

    public string $template = 'event-cancelled';
    public string $sendTo;
    public $user;
    public $event;

    public function __construct($user, $event)
    {
        $this->user   = $user;
        $this->event  = $event;
        $this->sendTo = $user->email;
    }
}
