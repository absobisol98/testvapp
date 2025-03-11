<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\EventCompany;
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
    public function execute($event, $tag_arr, $edit)
    {
        // If there are no new tags to add, and we're not editing, just return
        if (!$tag_arr && !$edit) {
            return;
        }

        // Initialize arrays for comparison
        $tag_arr = $tag_arr ?: []; // Convert null to empty array

        if ($edit) {
            // Get current event tags
            $currentEventTagIds = EventTag::where('event_id', $event->id)
                ->join('tags_events', 'event_tags.tag_id', '=', 'tags_events.id')
                ->pluck('tags_events.name')
                ->toArray();

            // Only proceed if there's a difference between current and new tags
            if (count(array_diff($currentEventTagIds, $tag_arr)) > 0 ||
                count(array_diff($tag_arr, $currentEventTagIds)) > 0) {

                // There's a difference, so delete old tags and create new ones
                EventTag::where('event_id', $event->id)->delete();

                // Continue with adding the new tags
            } else {
                // Tags are the same, no need to update
                return;
            }
        }

        if ($tag_arr) { // If tags exist, process them
            // Get existing tags
            $existingTags = TagsEvent::get()->pluck('name')->toArray();

            // Find new tags that don't exist yet
            $new_tags = array_diff($tag_arr, $existingTags);

            // Create any new tags that don't exist in the system yet
            foreach ($new_tags as $new_tag) {
                TagsEvent::create([
                    'name' => ucfirst($new_tag),
                ]);
            }

            // Get all tag IDs for the event
            $tags = TagsEvent::whereIn('name', $tag_arr)->get();

            // Create event tag associations
            foreach ($tags as $tag) {
                EventTag::create([
                    'event_id' => $event->id,
                    'tag_id' => $tag->id,
                ]);
            }
        }
    }
}
