<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Event;
use App\Models\AffiliateType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;

class VolunteerExportController extends Controller
{
    public function export(Request $request)
    {
        // Validate the request
        $request->validate([
            'companies' => 'nullable|array',
            'affiliations' => 'nullable|array',
            'events' => 'nullable|array',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        // Start with all volunteer users
        $volunteers = User::query()
            ->where('volunteer', 1)
            ->with(['company', 'affiliate', 'eventAttendees.event']);

        // Apply filters
        if ($request->has('companies') && !empty($request->companies)) {
            $volunteers->whereIn('company_id', $request->companies);
        }

        if ($request->has('affiliations') && !empty($request->affiliations)) {
            $volunteers->whereIn('affiliate_type_id', $request->affiliations);
        }

        if ($request->has('events') && !empty($request->events)) {
            $volunteers->whereHas('eventAttendees', function($query) use ($request) {
                $query->whereIn('event_id', $request->events);
            });
        }

        if ($request->has('date_from') && $request->date_from) {
            $volunteers->whereHas('eventAttendees', function($query) use ($request) {
                $query->whereDate('time_in', '>=', $request->date_from);
            });
        }

        if ($request->has('date_to') && $request->date_to) {
            $volunteers->whereHas('eventAttendees', function($query) use ($request) {
                $query->whereDate('time_in', '<=', $request->date_to);
            });
        }

        // Get the volunteers
        $volunteers = $volunteers->get();

        // Create CSV writer
        $csv = Writer::createFromString('');

        // Add header row
        $csv->insertOne([
            'ID',
            'First Name',
            'Last Name',
            'Email',
            'Phone',
            'Business Unit',
            'Affiliation',
            'Position',
            'Birthdate',
            'Joined Date',
            'Total Hours',
            'Opportunities Participated'
        ]);

        // Add data rows
        foreach ($volunteers as $volunteer) {
            // Calculate total hours
            $totalHours = $volunteer->eventAttendees->sum(function ($attendee) {
                return $attendee->get_totalHrs();
            });

            // Get events participated
            $eventsParticipated = $volunteer->eventAttendees->pluck('event.name')->unique()->implode(', ');

            $csv->insertOne([
                $volunteer->id,
                $volunteer->firstname,
                $volunteer->lastname,
                $volunteer->email,
                $volunteer->phone ?? 'N/A',
                $volunteer->company ? $volunteer->company->name : 'N/A',
                $volunteer->affiliate ? $volunteer->affiliate->name : 'N/A',
                $volunteer->position ?? 'N/A',
                $volunteer->birthday ? Carbon::parse($volunteer->birthday)->format('Y-m-d') : 'N/A',
                $volunteer->created_at->format('Y-m-d'),
                number_format($totalHours, 1),
                $eventsParticipated
            ]);
        }

        // Generate file name
        $fileName = 'volunteers-export-' . now()->format('Y-m-d') . '.csv';

        // Create response
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        return Response::make($csv->getContent(), 200, $headers);
    }
}
