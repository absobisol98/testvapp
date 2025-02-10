<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use PDF;
class PDFController extends Controller
{
    //

    public function generateCertificate($event_id, $attendee_id)
    {
        $event = Event::find($event_id);
        $attendee = User::find($attendee_id);

        return PDF::loadView('pdf.certificate', compact('event', 'attendee'))
        ->setPaper('Letter')
        ->setOption('margin-bottom', 0)
        ->setOrientation('landscape')
        ->inline('certificate.pdf')
        ;

    }

}
