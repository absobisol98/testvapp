<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use App\Models\EventRecurring;
use App\Models\EventSlot;
use App\Models\EventTag;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['created_by'] = auth()->id();
        $data['created_at'] = now();
        $data['start_date'] = $data['date'].' '.$data['start_time'].':00';
        $data['end_date'] = $data['date'].' '.$data['end_time'].':00';


        if(isset($data['tags']) && $data['tags']){ // If a new tag exists, insert the new tag into the event_tags table

            $tags = EventTag::get()->pluck('name')->toArray();

            $new_tags = array_diff($data['tags'], $tags);

            foreach ($new_tags as $new_tag) {
                EventTag::create([
                    'name' => ucfirst($new_tag),
                ]);
            }

            $data['tags'] = json_encode($data['tags']);
        }else{
            $data['tags'] = null;
        }


        if($data['recurrence_type_id'] == 2){ // Recurring

            // Recurring Function
            $recurrences = [
                'daily'     => [
                    'function'  => 'addDay'
                ],
                'weekly'    => [
                    'function'  => 'addWeek'
                ],
                'monthly'    => [
                    'function'  => 'addMonth'
                ],
                'yearly'    => [
                    'function'  => 'addYear'
                ]
            ];

            $data['repeat_until'] = Carbon::parse($data['repeat_until'])->endOfDay();

            $start = Carbon::parse($data['start_date']); // Start date
            $end = Carbon::parse($data['end_date']); // End date
            $repeat_until = $data['repeat_until'];

            $recurrence = $recurrences[$data['frequency']] ?? null;

            if($recurrence){

                $event_recurring = EventRecurring::create($data);

                $data['event_recurring_id'] = $event_recurring->id;

                if($start->format('Y-m-d H:i:s') > Carbon::now()->format('Y-m-d H:i:s')){
                    $data['start_date'] = $start->format('Y-m-d H:i:s');
                    $data['end_date'] = $end->format('Y-m-d H:i:s');

                    $event = Event::create($data);

                    if ($data['media']) {
                        foreach ($data['media'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-attachments'
                            );
                        }
                    }
                    // Insert Slots
                    if($data['am_slot_number']){
                        EventSlot::create([
                            'slot_type_id' => 1, // AM
                            'event_id' => $event->id,
                            'total_slots' => $data['am_slot_number'],
                            'start_time' => $data['am_start_time'],
                            'end_time' => $data['am_end_time'],
                        ]);
                    }
                    if($data['pm_slot_number']){
                        EventSlot::create([
                            'slot_type_id' => 2, // PM
                            'event_id' => $event->id,
                            'total_slots' => $data['pm_slot_number'],
                            'start_time' => $data['pm_start_time'],
                            'end_time' => $data['pm_end_time'],
                        ]);
                    }
                }
                while ($start->format('Y-m-d') < $repeat_until->format('Y-m-d'))
                {
                    $data['start_date'] = $start->{$recurrence['function']}()->format('Y-m-d H:i:s');
                    $data['end_date'] = $end->{$recurrence['function']}()->format('Y-m-d H:i:s');

                    $event = Event::create($data);

                    if ($data['media']) {
                        foreach ($data['media'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-attachments'
                            );
                        }
                    }

                    // Insert Slots
                    if($data['am_slot_number']){
                        EventSlot::create([
                            'slot_type_id' => 1, // AM
                            'event_id' => $event->id,
                            'total_slots' => $data['am_slot_number'],
                            'start_time' => $data['am_start_time'],
                            'end_time' => $data['am_end_time'],
                        ]);
                    }
                    if($data['pm_slot_number']){
                        EventSlot::create([
                            'slot_type_id' => 2, // PM
                            'event_id' => $event->id,
                            'total_slots' => $data['pm_slot_number'],
                            'start_time' => $data['pm_start_time'],
                            'end_time' => $data['pm_end_time'],
                        ]);
                    }
                }
            }
        }else{ // One time event
            $event = Event::create($data);

            if ($data['media']) {
                foreach ($data['media'] as $media) {
                    $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                        'event-attachments'
                    );
                }
            }
        }

        Notification::make()
            ->title('Saved Successfully.')
            ->success()
            ->send();

        return $event; // TODO: Change the autogenerated stub
    }

    protected function getRedirectUrl(): string
    {
        return route('filament.admin.resources.events.calendar');
    }
}
