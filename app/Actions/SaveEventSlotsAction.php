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
    public function execute($event,$data)
    {
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
