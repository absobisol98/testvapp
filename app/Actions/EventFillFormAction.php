<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\Program;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

final class EventFillFormAction
{
    public function execute($record,$data)
    {
        $data['date'] = $record->start_date;
        $data['start_time'] = $record->start_date;
        $data['end_time'] = $record->end_date;

        // Tags
        $data['tags'] = json_decode($data['tags']);

        // Slots
        $am_slot = $record->slots->where('slot_type_id',1)->first();
        $pm_slot = $record->slots->where('slot_type_id',2)->first();

        if($am_slot){
            $data['am_slot_number'] = $am_slot->total_slots;
            $data['am_start_time'] = $am_slot->start_time;
            $data['am_end_time'] = $am_slot->end_time;
        }
        if($pm_slot){
            $data['pm_slot_number'] = $pm_slot->total_slots;
            $data['pm_start_time'] = $pm_slot->start_time;
            $data['pm_end_time'] = $pm_slot->end_time;
        }

        // attachments
        $media = [];
        foreach ($record->getMedia('event-attachments') as $media_item) {
            $index = strlen(storage_path('app/public/'));
            $media[] = substr($media_item->getPath(), $index);
        }
        $data['media'] = $media;

        return $data;
    }
}
