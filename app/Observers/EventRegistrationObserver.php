<?php

namespace App\Observers;

use App\Models\EventRegistration;
use App\Models\MessageRoom;
use App\Models\MessageRoomParticipant;


class EventRegistrationObserver
{
    /**
     * Handle the EventRegistration "created" event.
     */
    public function created(EventRegistration $eventRegistration): void
    {
        //


        // $room = MessageRoom::where('event_id',$eventRegistration->event_id)->first();

        // // Add event owner as participant
        // MessageRoomParticipant::create([
        //     'message_room_id' => $room->id,
        //     'user_id' => $eventRegistration->volunteer_id,
        // ]);

    }

    /**
     * Handle the EventRegistration "updated" event.
     */
    public function updated(EventRegistration $eventRegistration): void
    {
        //
    }

    /**
     * Handle the EventRegistration "deleted" event.
     */
    public function deleted(EventRegistration $eventRegistration): void
    {
        //
    }

    /**
     * Handle the EventRegistration "restored" event.
     */
    public function restored(EventRegistration $eventRegistration): void
    {
        //
    }

    /**
     * Handle the EventRegistration "force deleted" event.
     */
    public function forceDeleted(EventRegistration $eventRegistration): void
    {
        //
    }
}
