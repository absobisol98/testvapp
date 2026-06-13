<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\MessageRoom;
use App\Models\MessageRoomParticipant;
use App\Models\Survey;
use App\Notifications\EventCancelledNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EventObserver
{
    public function created(Event $event): void
    {
        $room = MessageRoom::create([
            'event_id' => $event->id,
            'name'     => "{$event->name} Discussion",
        ]);

        MessageRoomParticipant::create([
            'message_room_id' => $room->id,
            'user_id'         => $event->created_by,
        ]);

        Survey::create([
            'event_id'    => $event->id,
            'title'       => $event->title . ' - Post Event Survey',
            'description' => 'Please help us improve by providing your feedback about this volunteer opportunity.',
            'is_anonymous' => true,
            'questions'   => [
                ['question' => 'How would you rate your overall volunteer experience?', 'type' => 'rating'],
                ['question' => 'What aspects of the volunteer opportunity did you find most meaningful?', 'type' => 'text'],
                ['question' => 'How satisfied were you with the support provided by the facilitators?', 'type' => 'rating'],
                [
                    'question' => 'Would you recommend this volunteer opportunity to others?',
                    'type'     => 'multiple_choice',
                    'options'  => [
                        ['option' => 'Yes, definitely'],
                        ['option' => 'Maybe'],
                        ['option' => 'No'],
                    ],
                ],
                ['question' => 'Do you have any suggestions for improvement?', 'type' => 'text'],
            ],
        ]);
    }

    public function updated(Event $event): void
    {
        // Detect cancellation: is_published flipped to false on a future event
        if (
            $event->wasChanged('is_published') &&
            ! $event->is_published &&
            Carbon::parse($event->start_date)->isFuture()
        ) {
            $this->notifyCancellation($event);
        }
    }

    public function deleted(Event $event): void
    {
        // Notify volunteers if a published future event is deleted
        if ($event->is_published && Carbon::parse($event->start_date)->isFuture()) {
            $this->notifyCancellation($event);
        }
    }

    public function restored(Event $event): void
    {
        //
    }

    public function forceDeleted(Event $event): void
    {
        //
    }

    private function notifyCancellation(Event $event): void
    {
        $registrations = $event->registrations()->with('volunteer')->get();

        foreach ($registrations as $registration) {
            $volunteer = $registration->volunteer;
            if (! $volunteer) {
                continue;
            }

            try {
                $volunteer->notify(new EventCancelledNotification($event));
            } catch (\Exception $e) {
                Log::error('Cancellation notification failed', [
                    'volunteer_id' => $volunteer->id,
                    'event_id'     => $event->id,
                    'error'        => $e->getMessage(),
                ]);
            }
        }
    }
}
