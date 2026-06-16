<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Visualbuilder\EmailTemplates\Traits\BuildGenericEmail;

class VolunteerRegistrationApprovedEmail extends Mailable
{
    use Queueable, SerializesModels, BuildGenericEmail;

    public string $template = 'volunteer-registration-approved';
    public string $sendTo;
    public $user;
    public $event;
    public $eventRegistration;

    public function __construct($user, $event, $eventRegistration = null)
    {
        $this->user              = $user;
        $this->event              = $event;
        $this->eventRegistration = $eventRegistration;
        $this->sendTo            = $user->email;
    }
}
