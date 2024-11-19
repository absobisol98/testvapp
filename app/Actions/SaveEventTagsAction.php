<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
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

final class SaveEventTagsAction
{
    public function execute($tag_arr,$event,$data)
    {
        if($tag_arr){ // If a new tag exists, insert the new tag into the tags_event table

            $tags = TagsEvent::get()->pluck('name')->toArray();

            $new_tags = array_diff($tag_arr, $tags); // Get new tags

            foreach ($new_tags as $new_tag) { // Insert new tags in tags_event table
                TagsEvent::create([
                    'name' => ucfirst($new_tag),
                ]);
            }

            $tags = TagsEvent::whereIn('name', $tag_arr)->get();

            foreach ($tags as $tag) {
                EventTag::create([
                    'event_id' => $event->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
    }
}
