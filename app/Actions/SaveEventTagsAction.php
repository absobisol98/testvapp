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
            try {
                // Get current event tags - using the correct table name (singular)
                $currentEventTagIds = EventTag::where('event_id', $event->id)
                    ->join('tags_event', 'event_tags.tag_id', '=', 'tags_event.id')
                    ->pluck('tags_event.name')
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
            } catch (\Exception $e) {
                // If there's an error, just delete existing tags and continue
                EventTag::where('event_id', $event->id)->delete();
            }
        }

        if ($tag_arr) { // If tags exist, process them
            try {
                // Get existing tags - using the correct table name
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
            } catch (\Exception $e) {
                // Log the error but don't abort the process
                \Log::error('Error processing tags: ' . $e->getMessage());
            }
        }
    }
}
