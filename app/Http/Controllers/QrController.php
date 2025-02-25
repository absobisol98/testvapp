<?php

namespace App\Http\Controllers;

use App;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\QrCode;
use App\Models\QrCodeClaimedLog;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;

class QrController extends Controller
{
    public function scan_qr($event_id, $attendee_id)
    {
        // First check if user is authenticated
        if (!auth()->check()) {
            Notification::make()
                ->title('Unauthorized Access')
                ->danger()
                ->send();

            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 401);
        }

        // Check if event exists
        $event = Event::find($event_id);
        if (!$event) {
            Notification::make()
                ->title('Event Not Found')
                ->danger()
                ->send();

            return response()->json([
                'status' => 'error',
                'message' => 'Event not found'
            ], 404);
        }

        $facilitator_ids = $event->facilitators()->pluck('id')->toArray();

        // Check if user is authorized (Super Admin or Facilitator)
        if (!auth()->user()->hasRole('super_admin') && !in_array(auth()->id(), $facilitator_ids)) {
            Notification::make()
                ->title('Unauthorized Facilitator')
                ->warning()
                ->send();

            return response()->json([
                'status' => 'error',
                'message' => 'unauthorized facilitator'
            ], 403);
        }

        // Get all slots for this attendee
        $attendees = EventAttendee::where('event_id', $event_id)
            ->where('attendee_id', $attendee_id)
            ->with('slot')
            ->get();

        if ($attendees->isEmpty()) {
            Notification::make()
                ->title('Invalid QR')
                ->danger()
                ->send();

            return response()->json([
                'status' => 'error',
                'message' => 'invalid QR'
            ], 404);
        }

        $currentTime = Carbon::now();
        $hasTimeIn = false;
        $hasTimeOut = false;

        foreach ($attendees as $attendee) {
            $slot = $attendee->slot;

            // Handle time in
            if (!$attendee->time_in) {
                $attendee->time_in = $currentTime;
                $attendee->save();

                Notification::make()
                    ->icon('fas-right-to-bracket')
                    ->title('Time In')
                    ->body("Slot: {$slot->name}")
                    ->success()
                    ->send();

                // Notify attendee
                Notification::make()
                    ->title("Time In: {$event->title}")
                    ->body("Slot: {$slot->name}")
                    ->icon('fas-right-to-bracket')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->url(route('filament.admin.resources.events.view', ['record' => $event_id]), shouldOpenInNewTab: true),
                    ])
                    ->sendToDatabase($attendee->attendee);

                $hasTimeIn = true;
                continue;
            }

            // Handle time out
            $time_in = Carbon::parse($attendee->time_in);
            if (!$attendee->time_out && $time_in->diffInHours($currentTime) > 1) {
                $attendee->time_out = $currentTime;
                $attendee->save();

                Notification::make()
                    ->icon('fas-right-from-bracket')
                    ->title('Time Out')
                    ->body("Slot: {$slot->name}")
                    ->success()
                    ->send();

                // Notify attendee
                Notification::make()
                    ->title("Time Out: {$event->title}")
                    ->body("Slot: {$slot->name}")
                    ->icon('fas-right-from-bracket')
                    ->actions([
                        \Filament\Notifications\Actions\Action::make('view')
                            ->button()
                            ->url(route('filament.admin.resources.events.view', ['record' => $event_id]), shouldOpenInNewTab: true),
                    ])
                    ->sendToDatabase($attendee->attendee);

                $hasTimeOut = true;
            }
        }

        // Return appropriate response with notification
        if ($hasTimeIn) {
            return response()->json([
                'status' => 'success',
                'message' => 'time in recorded'
            ]);
        }

        if ($hasTimeOut) {
            return response()->json([
                'status' => 'success',
                'message' => 'time out recorded'
            ]);
        }

        Notification::make()
            ->title('Recent Login Detected')
            ->warning()
            ->send();

        return response()->json([
            'status' => 'warning',
            'message' => 'You have just logged in'
        ]);
    }
}
