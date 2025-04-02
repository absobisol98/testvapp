<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use DateTime;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event as IcsEvent;
use Spatie\IcalendarGenerator\ValueObjects\RRule;
use Spatie\IcalendarGenerator\Enums\RecurrenceFrequency;
use Filament\Forms\Components\CheckboxList;

class EventRegistrationTableAction
{

    public function execute()
{
    $user = auth()->user();
    $isExternalPartner = $user && $user->hasRole('External Partner');

    $actions = [
        \Filament\Tables\Actions\ViewAction::make()
            ->mountUsing(function (Event $record, ComponentContainer $form){
                $media = [];
                foreach ($record->getMedia('event-attachments') as $media_item) {
                    $index = strlen(storage_path('app/public/'));
                    $media[] = substr($media_item->getPath(), $index);
                }
                $data['media'] = $media;
                $form->fill($data);
            }),
    ];

    // For type 1 events (view-only) for External Partners, only show view action
    if (!$isExternalPartner || !isset($GLOBALS['record']) || $GLOBALS['record']->event_type_id != 1) {
        // Add all remaining actions for non-type-1 events or non-external partners
        $actions = array_merge($actions, [
            \Filament\Tables\Actions\Action::make('Make it as featured')
                ->color('success')
                ->button()
                ->requiresConfirmation()
                ->action(function (Event $record){
                    $record->is_featured = true;
                    $record->update();

                    Notification::make()
                        ->title('Event has been mark as featured.')
                        ->success()
                        ->send();
                })
                ->visible(function (Event $record){
                    return auth()->user()->can('set_featured_event') &&
                    $record->is_featured == false &&
                    $record->end_date >= now();
                }),

            \Filament\Tables\Actions\Action::make('Remove as is featured')
                ->color('danger')
                ->button()
                ->requiresConfirmation()
                ->action(function (Event $record){
                    $record->is_featured = false;
                    $record->update();

                    Notification::make()
                        ->title('Event has been remove to as featured.')
                        ->success()
                        ->send();
                })
                ->visible(function (Event $record){
                    return auth()->user()->can('set_featured_event') &&
                    $record->is_featured == true;
                }),

            \Filament\Tables\Actions\Action::make('downloadIcs')
                ->label('Add to Calendar')
                ->action(function ($record) {
                    // Create event
                    $event = IcsEvent::create($record->title)
                        ->description(strip_tags($record->description))
                        ->startsAt(Carbon::parse($record->start_date))
                        ->endsAt(Carbon::parse($record->end_date));

                    // Handle recurring events
                    if ($record->recurrence_type_id != 1 && $record->repeat_until) {
                        $repeatRule = match($record->frequency) {
                            'daily' => 'daily',
                            'weekly' =>  'weekly',
                            'monthly' => 'monthly',
                            'yearly' =>  'yearly',
                            default => null
                        };

                        if ($repeatRule) {
                            if($repeatRule == 'daily'){
                                $repeatRule = RRule::frequency(RecurrenceFrequency::daily());
                            }elseif($repeatRule == 'weekly'){
                                $repeatRule = RRule::frequency(RecurrenceFrequency::weekly());
                            }elseif($repeatRule == 'monthly'){
                                $repeatRule = RRule::frequency(RecurrenceFrequency::monthly());
                            }elseif($repeatRule == 'yearly'){
                                $repeatRule = RRule::frequency(RecurrenceFrequency::yearly());
                            }

                            $event->rrule(
                                $repeatRule->until(Carbon::parse($record->repeat_until))
                            );
                        }
                    }

                    $calendar_title = $record->title . ' Calendar';
                    // Create calendar and add event
                    $calendar = Calendar::create()
                        ->name($calendar_title)
                        ->event($event);

                    // Generate and return file download
                    return response()->streamDownload(function () use ($calendar) {
                        echo $calendar->get();
                    }, $calendar_title . '.ics', [
                        'Content-Type' => 'text/calendar; charset=utf-8',
                        'Content-Disposition' => 'attachment; filename="my-awesome-calendar.ics"',
                    ]);
                })
                ->icon('heroicon-o-calendar')
                ->visible(function (Event $record) {
                    $registration = $record->registrations
                        ->where('volunteer_id', auth()->user()->id)
                        ->first();

                    return $registration && $registration->status_id == 1;
                }),

            \Filament\Tables\Actions\Action::make('Register')
                ->hidden()
                ->color('primary')
                ->button()
                ->modalContent(fn ($record) => view('custom.event-modal', ['record' => $record]))
                ->form(function ($record) {
                    return [
                        CheckboxList::make('slot_type_ids')
                            ->label('Select Available Shifts')
                            ->required()
                            ->options(function (Event $record) {
                                return $this->getAvailableSlots($record);
                            })
                            ->columns(2),

                        $this->getAttachmentSection($record)
                    ];
                })
                ->action(function (Event $record, array $data) {
                    $this->handleRegistration($record, $data);
                })
                ->visible(function (Event $record) {
                    // Hide Register button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }
                    return $this->canRegister($record);
                }),

            \Filament\Tables\Actions\Action::make('Cancel Registration')
                ->hidden()
                ->color('warning')
                ->button()
                ->requiresConfirmation()
                ->form([
                    CheckboxList::make('slots_to_cancel')
                        ->label('Select Slots to Cancel')
                        ->required()
                        ->options(fn (Event $record) => $this->getCancellableSlots($record))
                        ->columns(2)
                ])
                ->action(function (Event $record, array $data) {
                    $this->handleCancellation($record, $data);
                })
                ->visible(function (Event $record) {
                    // Hide Cancel Registration button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }
                    return $this->canCancelRegistration($record);
                }),

            \Filament\Tables\Actions\Action::make('export')
                ->color('secondary')
                ->button()
                ->label('Export')
                ->url(fn (Event $record): string => route('volunteer.export', $record))
                ->visible(function (Event $record){
                    // Hide Export button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }

                    $option = false;
                    if(is_null($record->event)){
                        $option = true;
                    };
                    $user = auth()->user();
                    if($user->can('export')){
                        $option = true;
                    }
                    else {
                        $option = false;
                    }
                    return $option;
                }),

            \Filament\Tables\Actions\Action::make('Edit')
                ->icon('heroicon-o-pencil-square')
                ->tooltip('Edit registration')
                ->color('secondary')
                ->button()
                ->modalContent(fn ($record) => view('custom.event-modal', ['record' => $record]))
                ->form(function ($record) {
                    if(auth()->user()->birthday && Carbon::parse(auth()->user()->birthday)->age < 18) { // MINOR
                        $description = 'Please upload parental consent';
                        $visible = true;
                    }elseif($record->attachment_required){
                        $description = 'Please upload required files';
                        $visible = true;
                    }else{
                        $description = '';
                        $visible = false;
                    }

                    return [
                        Radio::make('slot_type_id')
                            ->label('')
                            ->required(fn(Event $record) => $record->slots->first())
                            ->options(function (Event $record) {
                                $option = [];
                                foreach($record->slots as $slot) {
                                    // Only check total capacity, not existing registrations for this user
                                    $registion_count = $record->registrations
                                        ->where('slot_type_id', $slot->id)
                                        ->where('status_id', '!=', 3)
                                        ->count();

                                    if($slot->total_slots > $registion_count) {
                                        $option[$slot->id] = $slot->shift_name.' ('.
                                            Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('g:i A').' - '.
                                            Carbon::parse(now()->format('Y-m-d') . $slot->end_time)->format('g:i A').')';
                                    }
                                }
                                return $option;
                            }),
                        Section::make('Attachments')
                            ->description($description)
                            ->visible($visible)
                            ->schema([
                                FileUpload::make('media')
                                    ->directory('event-registration-attachments')
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->label('')
                                    ->openable()
                                    ->downloadable(),
                            ])
                            ->collapsible(),
                    ];
                })
                ->action(function (Event $record, array $data){
                    $registration = EventRegistration::where('event_id', $record->id)->where('volunteer_id',auth()->user()->id)->first();

                    if($registration){
                        if($record->approval_type == "Automatic"){ // Automatic approved status for registration
                            $attendee = EventAttendee::create([
                                'event_id' => $record->id,
                                'attendee_id' => auth()->user()->id,
                                'facilitator_id' => auth()->id(),
                            ]);

                            // Generate QR code for attendee
                            (new GenerateEventQRCode())->execute($attendee);

                            $status = 2; // Approve - (Automatic)

                            // Notify the registrant
                            $message = 'Your registration for '.$record->title.' on '.Carbon::parse($record->start_date)->format('M d, Y').' has been approved.';
                        }else{
                            $status = 1; // Pending
                            $message = null;
                        }

                        $registration->update([
                            'event_id' => $record->id,
                            'volunteer_id' => auth()->user()->id,
                            'slot_type_id' => $data['slot_type_id'],
                            'created_at' => now(),
                            'status_id' => $status,
                            'message' => $message
                        ]);

                        $registration->save();

                        // Registration Attachment - Update media  - Start
                        $existing_media_array = $registration->getMedia('event-registration-attachments')->pluck('file_name')->toArray();
                        $new_media_array = array();

                        if (isset($data['media']) && $data['media']) {
                            foreach ($data['media'] as $media) {
                                $new_media_array[] = $this->remove_folder_number($media);
                            }
                        }

                        // Delete existing file
                        foreach ($registration->getMedia('event-registration-attachments') as $media) {
                            if(!in_array($media->file_name, $new_media_array)){ // If not in the uploaded file
                                $media->delete();
                            }
                        }

                        if (isset($data['media']) && $data['media']) {
                            foreach ($data['media'] as $media) {
                                $sourcePath = storage_path('app/public/' . $media);

                                if(!in_array($this->remove_folder_number($media), $existing_media_array)){ // If not in the existing file
                                    $registration->addMedia($sourcePath)->toMediaCollection('event-registration-attachments');
                                }
                            }
                        }
                        // Registration Attachment - Update media - End

                        if($record->approval_type == "Automatic"){ // Automatic approved status for registration
                            Notification::make()
                                ->title($message)
                                ->icon('far-bell')
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->button()
                                        ->url(route('filament.admin.resources.events.view', ['record' => $record->id]), shouldOpenInNewTab: true),
                                ])
                                ->sendToDatabase($registration->volunteer);
                        }else{
                            Notification::make()
                                ->title('Saved.')
                                ->success()
                                ->send();
                        }
                    }
                })
                ->visible(function (Event $record){
                    // Hide Edit button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }

                    $registration = $record->registrations->where('volunteer_id',auth()->user()->id)->first();
                    return $registration && $registration->status_id == 1;
                })
                ->mountUsing(function (Event $record, ComponentContainer $form){
                    $registration = $record->registrations()->where('volunteer_id',auth()->id())->first();

                    if($registration){
                        $data['slot_type_id'] = $registration->slot_type_id;

                        $media = [];
                        foreach ($registration->getMedia('event-registration-attachments') as $media_item) {
                            $index = strlen(storage_path('app/public/'));
                            $media[] = substr($media_item->getPath(), $index);
                        }
                        $data['media'] = $media;
                    }
                    $form->fill($data);
                }),

            \Filament\Tables\Actions\EditAction::make()
                ->mountUsing(function (Event $record, ComponentContainer $form){
                    $media = [];
                    foreach ($record->getMedia('event-attachments') as $media_item) {
                        $index = strlen(storage_path('app/public/'));
                        $media[] = substr($media_item->getPath(), $index);
                    }
                    $data['media'] = $media;

                    $form->fill($data);
                })
                ->visible(function (Event $record) {
                    // Hide EditAction button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }
                    return $record->end_date >= now();
                }),

            \Filament\Tables\Actions\DeleteAction::make()
                ->visible(function (Event $record) {
                    // Hide Delete button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }
                    return $record->end_date >= now();
                }),

            \Filament\Tables\Actions\ForceDeleteAction::make()
                ->visible(function (Event $record) {
                    // Hide Force Delete button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }
                    return $record->end_date >= now();
                }),

            \Filament\Tables\Actions\RestoreAction::make()
                ->visible(function (Event $record) {
                    // Hide Restore button for external partners on event_type_id = 1
                    if (auth()->user()->hasRole('External Partner') && $record->event_type_id == 1) {
                        return false;
                    }
                    return true;
                }),
        ]);
    }

    return $actions;
}
    private function getAvailableSlots(Event $record): array
    {
        $options = [];
        $userRegistrations = $record->registrations
            ->where('volunteer_id', auth()->user()->id)
            ->where('status_id', '!=', 3)
            ->pluck('slot_type_id')
            ->toArray();

        foreach ($record->slots as $slot) {
            // Skip if user is already registered for this slot
            if (in_array($slot->id, $userRegistrations)) {
                continue;
            }

            $registrationCount = $record->registrations
                ->where('slot_type_id', $slot->id)
                ->where('status_id', '!=', 3)
                ->count();

            if ($slot->total_slots > $registrationCount) {
                $options[$slot->id] = $this->formatSlotOption($slot);
            }
        }
        return $options;
    }

    private function formatSlotOption($slot): string
    {
        return sprintf(
            '%s (%s - %s)',
            $slot->shift_name,
            Carbon::parse($slot->start_time)->format('g:i A'),
            Carbon::parse($slot->end_time)->format('g:i A')
        );
    }

    private function getAttachmentSection($record): Section
    {
        $visible = $this->requiresAttachment();
        $description = $this->getAttachmentDescription();

        return Section::make('Attachments')
            ->visible($visible)
            ->schema([
                FileUpload::make('media')
                    ->directory('event-registration-attachments')
                    ->multiple()
                    ->maxFiles(5)
                    ->label('')
                    ->openable()
                    ->required($visible)
                    ->validationMessages([
                        'required' => $description,
                    ])
                    ->downloadable(),
            ])
            ->collapsible();
    }

    private function handleRegistration(Event $record, array $data): void
    {
        // Handle multiple slot selections
        foreach ($data['slot_type_ids'] as $slotId) {
            // Check for existing registration for this slot
            $existingRegistration = EventRegistration::where([
                'event_id' => $record->id,
                'volunteer_id' => auth()->user()->id,
                'slot_type_id' => $slotId,
            ])->first();

            if ($existingRegistration) {
                continue; // Skip if already registered for this slot
            }

            // Create registration
            $registration = EventRegistration::create([
                'event_id' => $record->id,
                'volunteer_id' => auth()->user()->id,
                'slot_type_id' => $slotId,
                'status_id' => $record->approval_type == "Automatic" ? 2 : 1,
            ]);

            // Handle attachments if provided
            if (isset($data['media']) && $data['media']) {
                foreach ($data['media'] as $media) {
                    $sourcePath = storage_path('app/public/' . $media);
                    $registration->addMedia($sourcePath)
                        ->toMediaCollection('event-registration-attachments');
                }
            }

            // If automatic approval, create attendee record
            if ($record->approval_type == "Automatic") {
                $attendee = EventAttendee::create([
                    'event_id' => $record->id,
                    'attendee_id' => auth()->user()->id,
                    'facilitator_id' => auth()->id(),
                    'slot_type_id' => $slotId,
                    'is_approve' => 1,
                ]);

                // Generate QR code for the attendee
                (new GenerateEventQRCode())->execute($attendee);

                // Send notification for automatic approval
                Notification::make()
                    ->title("Registration approved for shift")
                    ->success()
                    ->send();
            }
        }

        // Send success notification
        $count = count($data['slot_type_ids']);
        Notification::make()
            ->title($count > 1
                ? "Successfully registered for {$count} shifts"
                : "Successfully registered for shift")
            ->success()
            ->send();
    }

    private function handleCancellation(Event $record, array $data): void
    {
        // Get the registrations being cancelled
        $registrations = EventRegistration::whereIn('id', $data['slots_to_cancel'])->get();

        foreach ($registrations as $registration) {
            // Delete corresponding attendee record if it exists
            EventAttendee::where([
                'event_id' => $registration->event_id,
                'attendee_id' => $registration->volunteer_id,
                'slot_type_id' => $registration->slot_type_id,
            ])->delete();
        }

        // Delete the registrations
        EventRegistration::whereIn('id', $data['slots_to_cancel'])->delete();

        $count = count($data['slots_to_cancel']);
        Notification::make()
            ->title($count > 1
                ? "Cancelled {$count} shift registrations"
                : "Cancelled shift registration")
            ->success()
            ->send();
    }

    public function remove_folder_number($media_file)
    {
        // Find the position of the first '/'
        $pos = strpos($media_file, '/');

        // Extract the substring from the position of the first '/' to the end
        $result = substr($media_file, $pos + 1);

        return $result;
    }

    private function canRegister(Event $record): bool
    {
        // Get user's existing registrations for this event
        $userRegistrations = $record->registrations
            ->where('volunteer_id', auth()->user()->id)
            ->where('status_id', '!=', 3); // Exclude rejected registrations

        // Check if event has available slots that user hasn't registered for yet
        $hasAvailableSlots = $record->slots->some(function ($slot) use ($record, $userRegistrations) {
            // Count total registrations for this slot
            $registrationCount = $record->registrations
                ->where('slot_type_id', $slot->id)
                ->where('status_id', '!=', 3)
                ->count();

            // Check if user is not already registered for this slot
            $userNotRegistered = !$userRegistrations
                ->where('slot_type_id', $slot->id)
                ->count();

            // Slot is available if it has capacity and user isn't registered
            return $slot->total_slots > $registrationCount && $userNotRegistered;
        });

        return $hasAvailableSlots;
    }

    private function canCancelRegistration(Event $record): bool
    {
        // Can cancel if user has pending registrations
        return $record->registrations
            ->where('volunteer_id', auth()->user()->id)
            ->count() > 0;
    }

    private function getCancellableSlots(Event $record): array
    {
        return $record->registrations
            ->where('volunteer_id', auth()->user()->id)
            ->mapWithKeys(function ($registration) {
                $slot = $registration->event_slot;
                return [
                    $registration->id => sprintf(
                        '%s (%s - %s)',
                        $slot->shift_name,
                        Carbon::parse($slot->start_time)->format('g:i A'),
                        Carbon::parse($slot->end_time)->format('g:i A')
                    )
                ];
            })
            ->toArray();
    }

    private function requiresAttachment(): bool
    {
        $user = auth()->user();
        $isMinor = $user->birthday && Carbon::parse($user->birthday)->age < 18;

        // Required if user is minor or event requires attachments
        return $isMinor || ($this->record->attachment_required ?? false);
    }

    private function getAttachmentDescription(): string
    {
        $user = auth()->user();
        $isMinor = $user->birthday && Carbon::parse($user->birthday)->age < 18;

        if ($isMinor) {
            return 'Please upload parental consent';
        }

        if ($this->record->attachment_required ?? false) {
            return 'Please upload required files';
        }

        return '';
    }
}
