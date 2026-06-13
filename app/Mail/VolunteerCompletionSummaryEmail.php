<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Visualbuilder\EmailTemplates\Traits\BuildGenericEmail;

class VolunteerCompletionSummaryEmail extends Mailable
{
    use Queueable, SerializesModels, BuildGenericEmail;

    public string $template = 'volunteer-completion-summary';
    public string $sendTo;
    public $user;
    public $event;
    public $eventAttendee;

    public function __construct($user, $event, $eventAttendee = null)
    {
        $this->user          = $user;
        $this->event         = $event;
        $this->eventAttendee = $eventAttendee;
        $this->sendTo        = $user->email;
    }
}
