<?php

// app/Mail/PostEventSurvey.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Survey;
use App\Models\EventRegistration;

class PostEventSurvey extends Mailable
{
    use Queueable, SerializesModels;

    public $survey;
    public $registration;
    public $uniqueToken;

    public function __construct(Survey $survey, EventRegistration $registration)
    {
        $this->survey = $survey;
        $this->registration = $registration;
        $this->uniqueToken = encrypt($registration->id);
    }

    public function build()
    {
        return $this->markdown('emails.post-event-survey')
                    ->subject('Event Feedback Survey - ' . $this->survey->event->title);
    }
}
