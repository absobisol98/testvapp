
@component('mail::message')
# Event Feedback Survey

Dear {{ $registration->name }},

Thank you for attending {{ $survey->event->name }}. We would greatly appreciate your feedback to help us improve future events.

@component('mail::button', ['url' => route('survey.respond', ['survey' => $survey->id, 'token' => $uniqueToken])])
Take Survey
@endcomponent

Your feedback is {{ $survey->is_anonymous ? 'anonymous' : 'confidential' }} and will help us enhance our future events.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
