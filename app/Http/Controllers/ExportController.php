<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Volunteer;
use App\Models\EventRegistration;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VolunteerExport;

class ExportController extends Controller
{
    //

    public function exportVolunteer($event_id)
    {
        abort_unless(
            auth()->check() && auth()->user()->can('manage_registrations_event'),
            403
        );

        return Excel::download(new VolunteerExport($event_id), 'summary.xlsx');
    }
}
