<?php

namespace App\Observers;

use App\Models\Certificate;
use App\Models\EventAttendee;
use Carbon\Carbon;

class EventAttendeeObserver
{
    public function updated(EventAttendee $attendee): void
    {
        if (
            $attendee->wasChanged('time_out') &&
            $attendee->time_out !== null &&
            $attendee->time_in !== null &&
            $attendee->is_approve
        ) {
            $this->issueCertificate($attendee);
        }

        // Also handle when is_approve is set after time_out already recorded
        if (
            $attendee->wasChanged('is_approve') &&
            $attendee->is_approve &&
            $attendee->time_in !== null &&
            $attendee->time_out !== null
        ) {
            $this->issueCertificate($attendee);
        }
    }

    private function issueCertificate(EventAttendee $attendee): void
    {
        // ✅ FIX: prevent null slot_type_id from breaking DB constraint
        if ($attendee->slot_type_id === null) {
            return;
        }

        $hoursServed = $attendee->get_totalHrs();

        Certificate::firstOrCreate(
            [
                'event_id'     => $attendee->event_id,
                'attendee_id'  => $attendee->attendee_id,
                'slot_type_id' => $attendee->slot_type_id,
            ],
            [
                'certificate_number' => $this->generateNumber($attendee),
                'hours_served'       => $hoursServed,
                'issued_at'          => now(),
            ]
        );
    }

    private function generateNumber(EventAttendee $attendee): string
    {
        $year   = Carbon::now()->format('Y');
        $suffix = strtoupper(substr(md5(uniqid()), 0, 3));

        return sprintf(
            'AF-%s-%s-%s-%s-%s',
            $year,
            str_pad($attendee->event_id, 4, '0', STR_PAD_LEFT),
            str_pad($attendee->attendee_id, 4, '0', STR_PAD_LEFT),
            str_pad($attendee->slot_type_id ?? 0, 2, '0', STR_PAD_LEFT),
            $suffix
        );
    }
}