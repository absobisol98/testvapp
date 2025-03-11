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
    public function execute($event, $data, $edit)
    {
        if ($edit) {
            // Get existing slots for this event
            $existingSlots = EventSlot::where('event_id', $event->id)->get()->keyBy('id');
            $processedSlotIds = [];

            foreach ($data['slots'] as $slotData) {
                if (isset($slotData['id']) && isset($existingSlots[$slotData['id']])) {
                    // Update existing slot
                    $slot = $existingSlots[$slotData['id']];
                    $slot->update([
                        'shift_name' => $slotData['shift_name'],
                        'slot_type_id' => $slotData['slot_type_id'],
                        'total_slots' => $slotData['total_slots'],
                        'start_time' => $slotData['start_time'],
                        'end_time' => $slotData['end_time'],
                        'responsibilities' => $slotData['responsibilities'],
                    ]);
                    $processedSlotIds[] = $slot->id;
                } else {
                    // Create new slot
                    $slot = EventSlot::create([
                        'shift_name' => $slotData['shift_name'],
                        'slot_type_id' => $slotData['slot_type_id'],
                        'event_id' => $event->id,
                        'total_slots' => $slotData['total_slots'],
                        'start_time' => $slotData['start_time'],
                        'end_time' => $slotData['end_time'],
                        'responsibilities' => $slotData['responsibilities'],
                    ]);
                    $processedSlotIds[] = $slot->id;
                }
            }

            // Only delete slots that weren't included in the updated data
            // This way we maintain relationships with existing slots
            if (!empty($processedSlotIds)) {
                EventSlot::where('event_id', $event->id)
                        ->whereNotIn('id', $processedSlotIds)
                        ->delete();
            }
        } else {
            // Create new slots for new events
            foreach ($data['slots'] as $slot) {
                EventSlot::create([
                    'shift_name' => $slot['shift_name'],
                    'slot_type_id' => $slot['slot_type_id'],
                    'event_id' => $event->id,
                    'total_slots' => $slot['total_slots'],
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'responsibilities' => $slot['responsibilities'],
                ]);
            }
        }
    }
}
