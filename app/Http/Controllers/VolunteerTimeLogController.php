<?php

namespace App\Http\Controllers;

use App\Models\EventAttendee;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Http\JsonResponse;

class VolunteerTimeLogController extends Controller
{
    public function store(EventAttendee $attendee): JsonResponse
    {
        if ($attendee->attendee_id !== auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $event = $attendee->event;

        if (!$event) {
            return response()->json(['status' => 'error', 'message' => 'Event not found'], 404);
        }

        $slot = $attendee->slot;
        $slotFormat = $slot?->slot_format ?? $event->event_format;
        $isVirtualOrHybrid = in_array($event->event_format, ['virtual', 'hybrid'])
            || $slotFormat === 'virtual';

        if (!$isVirtualOrHybrid) {
            return response()->json([
                'status' => 'error',
                'message' => 'Self time logging is only available for virtual and hybrid events',
            ], 403);
        }

        $now   = Carbon::now();
        $start = Carbon::parse($event->start_date);
        $end   = Carbon::parse($event->end_date);

        if ($now->lt($start) || $now->gt($end)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You can only log time during the event window ('
                    . $start->format('M d, Y h:i A') . ' – ' . $end->format('M d, Y h:i A') . ')',
            ], 422);
        }

        if (!$attendee->time_in) {
            $attendee->time_in = $now;
            $attendee->save();

            Notification::make()
                ->title("Time In: {$event->title}")
                ->body('Your time in has been recorded and is pending approval.')
                ->icon('fas-right-to-bracket')
                ->success()
                ->sendToDatabase(auth()->user());

            return response()->json(['status' => 'success', 'action' => 'time_in']);
        }

        if (!$attendee->time_out) {
            $attendee->time_out = $now;
            $attendee->save();

            Notification::make()
                ->title("Time Out: {$event->title}")
                ->body('Your time out has been recorded and is pending approval.')
                ->icon('fas-right-from-bracket')
                ->success()
                ->sendToDatabase(auth()->user());

            return response()->json(['status' => 'success', 'action' => 'time_out']);
        }

        return response()->json(['status' => 'warning', 'message' => 'Time already fully logged']);
    }
}
