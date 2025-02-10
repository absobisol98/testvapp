<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\MessageRoom;
use App\Models\MessageRoomParticipant;
use Tapp\FilamentFormBuilder\Models\FilamentForm;
use Tapp\FilamentFormBuilder\Models\FilamentFormField;

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


        $record = FilamentForm::first();

        $formCopy = FilamentForm::create([
            'name' => $event->title . ' Survey Form',
            'permit_guest_entries' => $record->permit_guest_entries,
            'redirect_url' => $record->redirect_url,
            'description' => $record->description,
        ]);

        $record->filamentFormFields->each(function ($field) use ($formCopy) {
            FilamentFormField::create([
                'filament_form_id' => $formCopy->id,
                'label' => $field->label,
                'type' => $field->type,
                'required' => $field->required,
                'order' => $field->order,
                'hint' => $field->hint,
                'options' => $field->options,
                'rules' => $field->rules,
            ]);
        });
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
