<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\EventAttendee;
use App\Models\Certificate;
use Carbon\Carbon;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    public function generateCertificate($event_id, $attendee_id)
    {
        $event = Event::find($event_id);
        $attendee = User::find($attendee_id);
        $certificates = [];

        $attendeeRecords = EventAttendee::where('event_id', $event_id)
            ->where('attendee_id', $attendee_id)
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->where('is_approve', true)
            ->with(['slot', 'event'])
            ->get();

        if ($attendeeRecords->isEmpty()) {
            abort(404, 'No completed attendance records found');
        }

        foreach ($attendeeRecords as $record) {
            $hoursServed = $record->get_totalHrs();

            $certificateNumber = $this->generateCertificateNumber(
                $event_id,
                $attendee_id,
                $record->slot_type_id
            );

            $existingCertificate = Certificate::firstOrCreate(
                [
                    'event_id' => $event_id,
                    'attendee_id' => $attendee_id,
                    'slot_type_id' => $record->slot_type_id,
                ],
                [
                    'certificate_number' => $certificateNumber,
                    'hours_served' => $hoursServed,
                    'issued_at' => now(),
                ]
            );

            $certificates[] = [
                'record' => $record,
                'certificateNumber' => $existingCertificate->certificate_number,
                'hoursServed' => $hoursServed
            ];
        }

        return PDF::loadView('pdf.certificate', compact(
            'event',
            'attendee',
            'certificates'
        ))
        ->setPaper('Letter')
        ->setOption('margin-bottom', 0)
        ->setOrientation('landscape')
        ->inline('certificates.pdf');

    }

    private function generateCertificateNumber($event_id, $attendee_id, $slot_id)
    {
        // Format: AF-YYYY-NNNN-VVVV-SS-XXX
        // AF: Ayala Foundation prefix
        // YYYY: Year
        // NNNN: Event ID padded
        // VVVV: Volunteer ID padded
        // SS: Slot ID padded
        // XXX: Random suffix for additional security

        $year = Carbon::now()->format('Y');
        $randomSuffix = strtoupper(substr(md5(uniqid()), 0, 3));

        return sprintf(
            "AF-%s-%s-%s-%s-%s",
            $year,
            str_pad($event_id, 4, '0', STR_PAD_LEFT),
            str_pad($attendee_id, 4, '0', STR_PAD_LEFT),
            str_pad($slot_id, 2, '0', STR_PAD_LEFT),
            $randomSuffix
        );
    }
}
