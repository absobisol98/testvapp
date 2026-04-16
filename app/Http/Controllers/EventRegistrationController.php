<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventAttendee;
use App\Models\EventSlot;
use App\Actions\GenerateEventQRCode;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;
use App\Filament\Resources\EventResource;

class EventRegistrationController extends Controller
{
    public function registerSlot(Request $request, Event $event, EventSlot $slot)
    {
        // Add validation rules
        $validationRules = [
            'media.*' => ['file', 'max:10240'], // 10MB max
        ];

        // Add required validation if needed
        $user = auth()->user();
        $isMinor = $user->age_range === '10-17';

        if ($isMinor || $event->attachment_required) {
            $validationRules['media'] = ['required', 'array'];
        }

        // Add file type validation
        if ($request->hasFile('media')) {
            $validationRules['media.*'] = [
                'file',
                'max:10240',
                'mimes:pdf,jpg,jpeg,png,doc,docx', // Allow only specific file types
            ];
        }

        // Custom validation messages
        $messages = [
            'media.required' => $isMinor
                ? 'Parental consent document is required for minors.'
                : 'Required documents must be uploaded for this event.',
            'media.*.max' => 'Files must not exceed 10MB in size.',
            'media.*.mimes' => 'Only PDF, JPG, PNG, DOC, and DOCX files are allowed.',
            'privacy_policy.required' => 'You must accept the Data Privacy Policy to continue.',
            'privacy_policy.accepted' => 'You must accept the Data Privacy Policy to continue.',
        ];

        // Validate the request
        $request->validate($validationRules, $messages);

        // Check if registration is still open
        if ($event->registration_end_date && Carbon::now()->isAfter($event->registration_end_date)) {
            Notification::make()
                ->title('Registration Failed')
                ->body('Registration period for this event has ended.')
                ->danger()
                ->send();
            return redirect(EventResource::getUrl('view', ['record' => $event]));
        }

        // Check if slot is available
        $registrationCount = $event->registrations()
            ->where('slot_type_id', $slot->id)
            ->where('status_id', '!=', 3)
            ->count();

        if ($registrationCount >= $slot->total_slots) {
            Notification::make()
                ->title('Registration Failed')
                ->body('This shift is already full.')
                ->danger()
                ->send();
            return redirect(EventResource::getUrl('view', ['record' => $event]));
        }

        // Check for existing registration
        $existingRegistration = EventRegistration::where([
            'event_id' => $event->id,
            'volunteer_id' => auth()->id(),
            'slot_type_id' => $slot->id,
        ])->first();

        if ($existingRegistration) {
            Notification::make()
                ->title('Registration Failed')
                ->body('You are already registered for this shift.')
                ->danger()
                ->send();
            return redirect(EventResource::getUrl('view', ['record' => $event]));
        }

        try {
            // Create registration
            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'volunteer_id' => auth()->id(),
                'slot_type_id' => $slot->id,
                'status_id' => $event->approval_type == "Automatic" ? 2 : 1,
            ]);

            // Handle file attachments
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $registration->addMedia($file)
                        ->toMediaCollection('event-registration-attachments');
                }
            }

            // Handle automatic approval
            if ($event->approval_type == "Automatic") {
                $attendee = EventAttendee::create([
                    'event_id' => $event->id,
                    'attendee_id' => auth()->id(),
                    'facilitator_id' => auth()->id(),
                    'slot_type_id' => $slot->id,
                    'is_approve' => 1,
                ]);

                (new GenerateEventQRCode())->execute($attendee);

                Notification::make()
                    ->title('Registration Successful')
                    ->body('Your registration has been automatically approved.')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Registration Successful')
                    ->body('Your registration is pending approval.')
                    ->success()
                    ->send();
            }

            return redirect(EventResource::getUrl('view', ['record' => $event]));

        } catch (\Exception $e) {
            Notification::make()
                ->title('Registration Failed')
                ->body('An error occurred while processing your registration.')
                ->danger()
                ->send();
            return redirect(EventResource::getUrl('view', ['record' => $event]));
        }
    }

    public function cancelRegistration(EventRegistration $registration)
    {
        // Security checks
        if ($registration->volunteer_id !== auth()->id()) {
            Notification::make()
                ->title('Unauthorized')
                ->body('You are not authorized to cancel this registration.')
                ->danger()
                ->send();
            return back();
        }

        if ($registration->status_id == 3) {
            Notification::make()
                ->title('Cannot Cancel')
                ->body('Cannot cancel an approved or rejected registration.')
                ->warning()
                ->send();
            return back();
        }

        try {
            // Delete attendee record if exists
            EventAttendee::where([
                'event_id' => $registration->event_id,
                'attendee_id' => $registration->volunteer_id,
                'slot_type_id' => $registration->slot_type_id,
            ])->delete();

            // Delete media files
            $registration->clearMediaCollection('event-registration-attachments');

            // Delete registration
            $registration->delete();

            Notification::make()
                ->title('Registration Cancelled')
                ->success()
                ->send();

            return back();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Cancellation Failed')
                ->body('An error occurred while cancelling your registration.')
                ->danger()
                ->send();
            return back();
        }
    }

    private function getMediaValidationRules(Event $event): array
    {
        $user = auth()->user();
        $isMinor = $user->age_range === '10-17';

        $rules = ['file', 'max:10240']; // 10MB max file size

        if ($isMinor || $event->attachment_required) {
            $rules[] = 'required';
        }

        return $rules;
    }

    private function getMediaValidationMessage(): string
    {
        $user = auth()->user();
        $isMinor = $user->age_range === '10-17';

        if ($isMinor) {
            return 'Parental consent document is required for minors.';
        }

        return 'Required documents must be uploaded for this event.';
    }

    /**
     * Validate file attachments
     */
    private function validateAttachments($files): bool
    {
        foreach ($files as $file) {
            // Check file size
            if ($file->getSize() > 10240 * 1024) { // 10MB in bytes
                return false;
            }

            // Check file type
            $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
            if (!in_array($file->getClientOriginalExtension(), $allowedTypes)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Export event registrants to CSV
     */
    public function exportRegistrants(Event $event)
    {
        // Check if user is authorized (Super Admin, Admin, Creator, or Facilitator)
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('super_admin');
        $isAdmin = $user->hasRole('Ayala Super Admin');
        $isCreator = $event->created_by == $user->id;
        $isFacilitator = $event->facilitators->contains($user->id);

        if (!$isSuperAdmin && !$isAdmin && !$isCreator && !$isFacilitator) {
            return back()->with('error', 'You are not authorized to export volunteer registrations for this event.');
        }

        // Get all registrants with related data
        $registrations = EventRegistration::where('event_id', $event->id)
            ->with(['volunteer', 'event_slot', 'status'])
            ->get();

        // Create CSV writer
        $csv = Writer::createFromString('');

        // Add header row
        $csv->insertOne([
            'Registration ID',
            'Volunteer Name',
            'Email',
            'Phone',
            'Company',
            'Position',
            'Shift Name',
            'Shift Time',
            'Status',
            'Registration Date'
        ]);

        // Add data rows
        foreach ($registrations as $registration) {
            $volunteer = $registration->volunteer;
            $company = $volunteer->company ? $volunteer->company->name : 'N/A';
            $position = $volunteer->position ?? 'N/A';

            $shiftTime = 'N/A';
            if ($registration->event_slot) {
                $startTime = \Carbon\Carbon::parse($registration->event_slot->start_time)->format('g:i A');
                $endTime = \Carbon\Carbon::parse($registration->event_slot->end_time)->format('g:i A');
                $shiftTime = "$startTime - $endTime";
            }

            $csv->insertOne([
                $registration->id,
                $volunteer->firstname . ' ' . $volunteer->lastname,
                $volunteer->email,
                $volunteer->phone ?? 'N/A',
                $company,
                $position,
                $registration->event_slot ? $registration->event_slot->shift_name : 'N/A',
                $shiftTime,
                $registration->status ? $registration->status->name : 'N/A',
                $registration->created_at->format('Y-m-d H:i:s')
            ]);
        }

        // Generate file name
        $fileName = 'opportunity-' . $event->title . '-registrants-' . now()->format('Y-m-d') . '.csv';

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
