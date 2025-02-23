<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\MessageRoom;
use App\Models\MessageRoomParticipant;
use App\Models\Survey;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        //
        $room = MessageRoom::create([
            'event_id' => $event->id,
            'name' => "{$event->name} Discussion",
        ]);

        // Add event owner as participant
        MessageRoomParticipant::create([
            'message_room_id' => $room->id,
            'user_id' => $event->created_by,
        ]);

        // Create default survey for the event
        Survey::create([
            'event_id' => $event->id,
            'title' => $event->title . ' - Post Event Survey',
            'description' => 'Please help us improve by providing your feedback about this volunteer opportunity.',
            'is_anonymous' => true,
            'questions' => [
                [
                    'question' => 'How would you rate your overall volunteer experience?',
                    'type' => 'rating'
                ],
                [
                    'question' => 'What aspects of the volunteer opportunity did you find most meaningful?',
                    'type' => 'text'
                ],
                [
                    'question' => 'How satisfied were you with the support provided by the facilitators?',
                    'type' => 'rating'
                ],
                [
                    'question' => 'Would you recommend this volunteer opportunity to others?',
                    'type' => 'multiple_choice',
                    'options' => [
                        ['option' => 'Yes, definitely'],
                        ['option' => 'Maybe'],
                        ['option' => 'No']
                    ]
                ],
                [
                    'question' => 'Do you have any suggestions for improvement?',
                    'type' => 'text'
                ]
            ]
        ]);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        //
    }
}
