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
use Illuminate\Support\Facades\DB;
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

        // [FIX #5] Block registration if event has already started
        if (Carbon::now()->isAfter($event->start_date)) {
            Notification::make()
                ->title('Registration Closed')
                ->body('This event has already started. Registration is no longer available.')
                ->danger()
                ->send();
            return back();
        }

        // Check if registration deadline has passed
        if ($event->registration_end_date && Carbon::now()->isAfter($event->registration_end_date)) {
            Notification::make()
                ->title('Registration Failed')
                ->body('Registration period for this event has ended.')
                ->danger()
                ->send();
            return back();
        }

        try {
            // [FIX #1] Wrap in DB transaction with pessimistic lock to prevent race conditions
            $registration = DB::transaction(function () use ($event, $slot, $request) {

                // Lock the slot row so concurrent requests queue up here
                $lockedSlot = EventSlot::lockForUpdate()->find($slot->id);

                // [FIX #3] Single definitive duplicate check inside transaction
                $existing = EventRegistration::where('event_id', $event->id)
                    ->where('volunteer_id', auth()->id())
                    ->where('slot_type_id', $lockedSlot->id)
                    ->whereIn('status_id', [1, 2])
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    throw new \RuntimeException('You are already registered for this shift.');
                }

                // Re-count capacity inside transaction
                $registrationCount = EventRegistration::where('slot_type_id', $lockedSlot->id)
                    ->where('status_id', '!=', 3)
                    ->count();

                if ($registrationCount >= $lockedSlot->total_slots) {
                    throw new \RuntimeException('This shift is already full.');
                }

                // Create registration
                $reg = EventRegistration::create([
                    'event_id'    => $event->id,
                    'volunteer_id'=> auth()->id(),
                    'slot_type_id'=> $lockedSlot->id,
                    'status_id'   => $event->approval_type === 'Automatic' ? 2 : 1,
                ]);

                // Handle file attachments
                if ($request->hasFile('media')) {
                    foreach ($request->file('media') as $file) {
                        $reg->addMedia($file)
                            ->toMediaCollection('event-registration-attachments');
                    }
                }

                // [FIX #2] Automatic approval: set facilitator_id to NULL (volunteer ≠ facilitator)
                if ($event->approval_type === 'Automatic') {
                    $attendee = EventAttendee::create([
                        'event_id'      => $event->id,
                        'attendee_id'   => auth()->id(),
                        'facilitator_id'=> null,
                        'slot_type_id'  => $lockedSlot->id,
                        'is_approve'    => 1,
                    ]);
                    (new GenerateEventQRCode())->execute($attendee);
                }

                return $reg;
            });

            $body = $event->approval_type === 'Automatic'
                ? 'Your registration has been automatically approved.'
                : 'Your registration is pending approval.';

            Notification::make()
                ->title('Registration Successful')
                ->body($body)
                ->success()
                ->send();

            return back();

        } catch (\RuntimeException $e) {
            Notification::make()
                ->title('Registration Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
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

        // [FIX #7] Correct cancellation guard logic
        if ($registration->status_id == 3) {
            Notification::make()
                ->title('Cannot Cancel')
                ->body('This registration has already been rejected.')
                ->warning()
                ->send();
            return back();
        }

        // Prevent cancellation after event has started
        if ($registration->event && Carbon::now()->isAfter($registration->event->start_date)) {
            Notification::make()
                ->title('Cannot Cancel')
                ->body('You cannot cancel a registration after the event has started.')
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
