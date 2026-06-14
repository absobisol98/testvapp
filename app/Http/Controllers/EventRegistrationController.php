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

class EventRegistrationController extends Controller
{
    public function registerSlot(Request $request, Event $event, EventSlot $slot)
    {
        // Add validation rules
        $validationRules = [
            'media.*' => ['file', 'max:10240'], // 10MB max
            'privacy_policy' => ['required', 'accepted'], // Add privacy policy validation
        ];

        // Add required validation if needed
        $user = auth()->user();
        $isMinor = $user->birthday && Carbon::parse($user->birthday)->age < 18;

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

        // Add duplicate registration check
        $existingRegistrations = EventRegistration::where('volunteer_id', auth()->id())
            ->where('event_id', $event->id)
            ->whereIn('status_id', [1, 2]) // Pending or Approved
            ->count();

        if ($existingRegistrations > 0) {
            Notification::make()
                ->title('Registration Failed')
                ->body('You are already registered for this event.')
                ->danger()
                ->send();
            return back();
        }

        // Check if registration is still open
        if ($event->registration_end_date && Carbon::now()->isAfter($event->registration_end_date)) {
            Notification::make()
                ->title('Registration Failed')
                ->body('Registration period for this event has ended.')
                ->danger()
                ->send();
            return back();
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
            return back();
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
            return back();
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

            return back();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Registration Failed')
                ->body('An error occurred while processing your registration.')
                ->danger()
                ->send();
            return back();
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
        $isMinor = $user->birthday && Carbon::parse($user->birthday)->age < 18;

        $rules = ['file', 'max:10240']; // 10MB max file size

        if ($isMinor || $event->attachment_required) {
            $rules[] = 'required';
        }

        return $rules;
    }

    private function getMediaValidationMessage(): string
    {
        $user = auth()->user();
        $isMinor = $user->birthday && Carbon::parse($user->birthday)->age < 18;

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
}
