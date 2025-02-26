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

        // Get all completed slots for this attendee
        $attendeeRecords = EventAttendee::where('event_id', $event_id)
            ->where('attendee_id', $attendee_id)
            ->whereNotNull('time_in')
            ->whereNotNull('time_out')
            ->with(['slot', 'event'])
            ->get();


        if ($attendeeRecords->isEmpty()) {
            abort(404, 'No completed attendance records found');
        }

        // If only one slot, generate single PDF
        if ($attendeeRecords->count() ) {
            $record = $attendeeRecords->first();
            $timeIn = Carbon::parse($record->time_in);
            $timeOut = Carbon::parse($record->time_out);
            $hoursServed = $timeOut->diffInHours($timeIn);

            $certificateNumber = $this->generateCertificateNumber(
                $event_id,
                $attendee_id,
                $record->slot_type_id
            );

            $existingCertificate = Certificate::where([
                'event_id' => $event_id,
                'attendee_id' => $attendee_id,
                'slot_type_id' => $record->slot_type_id,
            ])->first();

            if (!$existingCertificate) {
                Certificate::create([
                    'certificate_number' => $certificateNumber,
                    'event_id' => $event_id,
                    'attendee_id' => $attendee_id,
                    'slot_type_id' => $record->slot_type_id,
                    'hours_served' => $hoursServed,
                    'issued_at' => now(),
                ]);
            } else {
                // Use existing certificate number if entry exists
                $certificateNumber = $existingCertificate->certificate_number;
                $hoursServed = $existingCertificate->hours_served;
            }


            return PDF::loadView('pdf.certificate', compact(
                'event',
                'attendee',
                'certificateNumber',
                'hoursServed',
                'record'
            ))
            ->setPaper('Letter')
            ->setOption('margin-bottom', 0)
            ->setOrientation('landscape')
            ->inline('certificate.pdf');
        }


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
