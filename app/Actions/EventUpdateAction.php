<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\Event;
use App\Models\EventCompany;
use App\Models\EventRecurring;
use App\Models\EventSlot;
use App\Models\EventTag;
use App\Models\Program;
use App\Models\TagsEvent;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

final class EventUpdateAction
{
    public function execute($data,$record)
    {
        $data['updated_by'] = auth()->id();
        $data['updated_at'] = now();
        // start_date and end_date come directly from DateTimePicker fields
        unset($data['date'], $data['start_time'], $data['end_time']);

        $new_start_date = Carbon::parse($record->start_date)->diffInSeconds($data['start_date'], false);
        $new_end_date = Carbon::parse($record->end_date)->diffInSeconds($data['end_date'], false);

        $tag_arr = array();

        if(isset($data['tags']) && $data['tags']){

            $record->tags()->detach(); // Delete existing tags
            $tag_arr = $data['tags'];
        }
        unset($data['tags']);

        if($record->event_recurring_id){ // Recurring Events

            // Get all future events
            $records = Event::where('event_recurring_id',$record->event_recurring_id)->where('start_date', '>', Carbon::parse($record->start_date)->startOfDay())->get();

        }else { // One-time event
            $records = collect([$record]); // Model to Collection
        }

        foreach ($records as $event) {

            $data['start_date'] = Carbon::parse($event->start_date)->addSeconds($new_start_date);
            $data['end_date'] = Carbon::parse($event->end_date)->addSeconds($new_end_date);
            $event->update($data);

            // Insert Slots
            (new SaveEventSlotsAction())->execute($event,$data,true);

            // Insert Event companies
            (new SaveEventCompaniesAction())->execute($event,$data,true);

            // Insert Event Tags
            (new SaveEventTagsAction())->execute($event,$tag_arr,true);

        }

        // BANNER - Update media  - Start
        $existing_media_array = $record->getMedia('event-banner-attachments')->pluck('file_name')->toArray();
        $new_media_array = array();

        if (isset($data['media_banner']) && $data['media_banner']) {
            foreach ($data['media_banner'] as $media) {
                $new_media_array[] = $this->remove_folder_number($media);
            }
        }

        // Delete existing file
        foreach ($record->getMedia('event-banner-attachments') as $media) {
            if(!in_array($media->file_name, $new_media_array)){ // If not in the uploaded file
                $media->delete();
            }
        }

        if (isset($data['media_banner']) && $data['media_banner']) {

            foreach ($data['media_banner'] as $media) {
                $sourcePath = storage_path('app/public/' . $media);

                if(!in_array($this->remove_folder_number($media), $existing_media_array)){ // If not in the existing file
                    $record->addMedia($sourcePath)->toMediaCollection('event-banner-attachments');
                }
            }
        }
        // BANNER - Update media - End

        // ATTACHMENTS Update media - Start
        $existing_media_array = $record->getMedia('event-attachments')->pluck('file_name')->toArray();
        $new_media_array = array();

        if (isset($data['media']) && $data['media']) {
            foreach ($data['media'] as $media) {
                $new_media_array[] = $this->remove_folder_number($media);
            }
        }

        // Delete existing file
        foreach ($record->getMedia('event-attachments') as $media) {
            if(!in_array($media->file_name, $new_media_array)){ // If not in the uploaded file
                $media->delete();
            }
        }

        if (isset($data['media']) && $data['media']) {

            foreach ($data['media'] as $media) {
                $sourcePath = storage_path('app/public/' . $media);

                if(!in_array($this->remove_folder_number($media), $existing_media_array)){ // If not in the existing file
                    $record->addMedia($sourcePath)->toMediaCollection('event-attachments');
                }
            }
        }
        // ATTACHMENTS Update media - End

        // Certificate Update media - Start
        $existing_media_array = $record->getMedia('certificate_background')->pluck('file_name')->toArray();
        $new_media_array = array();

        if (isset($data['media']) && $data['media']) {
            foreach ($data['media'] as $media) {
                $new_media_array[] = $this->remove_folder_number($media);
            }
        }

        // Delete existing file
        foreach ($record->getMedia('certificate_background') as $media) {
            if(!in_array($media->file_name, $new_media_array)){ // If not in the uploaded file
                $media->delete();
            }
        }

        if (isset($data['media']) && $data['media']) {

            foreach ($data['media'] as $media) {
                $sourcePath = storage_path('app/public/' . $media);

                if(!in_array($this->remove_folder_number($media), $existing_media_array)){ // If not in the existing file
                    $record->addMedia($sourcePath)->toMediaCollection('certificate_background');
                }
            }
        }
        // Certificate Update media - End

        return $record; // TODO: Change the autogenerated stub
    }

    public function remove_folder_number($media_file)
    {
        // Find the position of the first '/'
        $pos = strpos($media_file, '/');

        // Extract the substring from the position of the first '/' to the end
        $result = substr($media_file, $pos + 1);

        return $result;
    }

}
