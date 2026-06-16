<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Visualbuilder\EmailTemplates\Traits\BuildGenericEmail;

class VolunteerRegistrationRejectedEmail extends Mailable
{
    use Queueable, SerializesModels, BuildGenericEmail;

    public string $template = 'volunteer-registration-rejected';
    public string $sendTo;
    public $user;
    public $event;
    public $eventRegistration;
    public $message;

    public function __construct($user, $event, $eventRegistration = null, $message = null)
    {
        $this->user              = $user;
        $this->event              = $event;
        $this->eventRegistration = $eventRegistration;
        $this->message            = $message;
        $this->sendTo            = $user->email;
    }
}
