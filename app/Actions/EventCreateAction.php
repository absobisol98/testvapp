<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\EventRecurring;

use Carbon\Carbon;
use Filament\Notifications\Notification;

final class EventCreateAction
{
    public function execute($data)
    {

        $tag_arr = array();

        if(isset($data['tags']) && $data['tags']){
            $tag_arr = $data['tags'];
            unset($data['tags']);
        }

        $data['created_by'] = auth()->id();
        $data['created_at'] = now();
        if(auth()->user()->hasRole('External Partner')){
            $data['is_published'] = false;
        }
        // start_date and end_date come directly from DateTimePicker fields
        unset($data['date'], $data['start_time'], $data['end_time']);

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

                    if ($data['media_banner']) {
                        foreach ($data['media_banner'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-banner-attachments'
                            );
                        }
                    }

                    if ($data['certificate_background']) {
                        foreach ($data['certificate_background'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'certificate_background'
                            );
                        }
                    }

                    if ($data['media']) {
                        foreach ($data['media'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-attachments'
                            );
                        }
                    }

                    // Insert Slots
                    (new SaveEventSlotsAction())->execute($event,$data,false);

                    // Insert Event companies
                    (new SaveEventCompaniesAction())->execute($event,$data,false);

                    // Insert Event Tags
                    (new SaveEventTagsAction())->execute($event,$tag_arr,false);

                }
                while ($start->format('Y-m-d') < $repeat_until->format('Y-m-d'))
                {
                    $data['start_date'] = $start->{$recurrence['function']}()->format('Y-m-d H:i:s');
                    $data['end_date'] = $end->{$recurrence['function']}()->format('Y-m-d H:i:s');

                    $event = Event::create($data);

                    if ($data['media_banner']) {
                        foreach ($data['media_banner'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-banner-attachments'
                            );
                        }
                    }

                    if ($data['certificate_background']) {
                        foreach ($data['certificate_background'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'certificate_background'
                            );
                        }
                    }

                    if ($data['media']) {
                        foreach ($data['media'] as $media) {
                            $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-attachments'
                            );
                        }
                    }

                    // Insert Slots
                    (new SaveEventSlotsAction())->execute($event,$data,false);

                    // Insert Event companies
                    (new SaveEventCompaniesAction())->execute($event,$data,false);

                    // Insert Event Tags
                    (new SaveEventTagsAction())->execute($event,$tag_arr,false);
                }
            }
        }else{ // One time event
            $event = Event::create($data);

            if ($data['media_banner']) {
                foreach ($data['media_banner'] as $media) {
                    $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                        'event-banner-attachments'
                    );
                }
            }

            if ($data['certificate_background']) {
                foreach ($data['certificate_background'] as $media) {
                    $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                        'certificate_background'
                    );
                }
            }

            if ($data['media']) {
                foreach ($data['media'] as $media) {
                    $event->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                        'event-attachments'
                    );
                }
            }

            // Insert Slots
            (new SaveEventSlotsAction())->execute($event,$data,false);

            // Insert Event companies
            (new SaveEventCompaniesAction())->execute($event,$data,false);

            // Insert Event Tags
            (new SaveEventTagsAction())->execute($event,$tag_arr,false);
        }

        Notification::make()
            ->title('Saved Successfully.')
            ->success()
            ->send();

        return $event; // TODO: Change the autogenerated stub
    }
}
