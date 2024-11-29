<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\EventCompany;
use App\Models\EventSlot;
use App\Models\EventTag;
use App\Models\Program;
use App\Models\TagsEvent;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

final class SaveEventSlotsAction
{
    public function execute($event,$data,$edit)
    {
        if($edit){
            EventSlot::where('event_id',$event->id)->delete();
        }

        foreach ($data['slots'] as $slot)

            EventSlot::create([
                'shift_name' => $slot['shift_name'],
                'slot_type_id' => $slot['slot_type_id'], // AM
                'event_id' => $event->id,
                'total_slots' => $slot['total_slots'],
                'start_time' => $slot['start_time'],
                'end_time' => $slot['end_time'],
                'responsibilities' => $slot['responsibilities'],
            ]);

    }
}
