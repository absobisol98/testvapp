<?php

namespace App\Services;

use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use Carbon\Carbon;

class IcsGeneratorService
{
    public function generateIcs(
        string $title,
        string $description,
        Carbon $startDate,
        Carbon $endDate,
        ?string $location = null,
        ?array $attendees = null
    ): string {
        $event = Event::create($title)
            ->description($description)
            ->startsAt($startDate)
            ->endsAt($endDate);

        if ($location) {
            $event->address($location);
        }

        if ($attendees) {
            foreach ($attendees as $attendee) {
                $event->attendee($attendee);
            }
        }

        $calendar = Calendar::create()
            ->name('Event Calendar')
            ->event($event);

        return $calendar->get();
    }
}
