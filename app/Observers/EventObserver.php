<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\MessageRoom;
use App\Models\MessageRoomParticipant;


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
