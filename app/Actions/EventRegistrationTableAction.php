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
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class EventRegistrationTableAction
{

    public function execute()
    {
        return [
            \Filament\Tables\Actions\Action::make('Register')
                ->color('primary')
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
                            ->options(function (Event $record){
                                $option = [];
                                foreach($record->slots as $slot) {
                                    $registion_count = $record->registrations->where('slot_type_id',$slot->id)->where('status_id','!=',3)->count();

                                    if($slot->total_slots - $registion_count){
                                        $option[$slot->id] = $slot->shift_name.' ('.Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('g:i A').' - '.Carbon::parse(now()->format('Y-m-d') . $slot->end_time)->format('g:i A').')';
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
                ->action(function (Event $record,array $data){
                    // throw notif for duplciate registration
                    if($record->attendees->where('attendee_id',auth()->user()->id)->first()){
                        Notification::make()
                            ->title('You are already registered in this event')
                            ->icon('far-bell')
                            ->danger()
                            ->send()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->button()
                                    ->url(route('filament.admin.resources.events.view', ['record' => $record->id]), shouldOpenInNewTab: true),
                            ]);


                    }
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

                    $event_registration = EventRegistration::create([
                        'event_id' => $record->id,
                        'volunteer_id' => auth()->user()->id,
                        'slot_type_id' => $data['slot_type_id'],
                        'created_at' => now(),
                        'status_id' => $status,
                        'message' => $message
                    ]);

                    if (isset($data['media']) && $data['media']) {
                        foreach ($data['media'] as $media) {
                            $event_registration->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-registration-attachments'
                            );
                        }
                    }

                    if($record->approval_type == "Automatic"){ // Automatic approved status for registration
                        Notification::make()
                            ->title($message)
                            ->icon('far-bell')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->button()
                                    ->url(route('filament.admin.resources.events.view', ['record' => $record->id]), shouldOpenInNewTab: true),
                            ])
                            ->sendToDatabase($event_registration->volunteer);
                    }else{
                        Notification::make()
                            ->title('You have successfully registered.')
                            ->success()
                            ->send();
                    }
                    //notification for slot threshold
                    $slot_treshold = $event_registration->event_slot->total_slots;
                    $registered = $event_registration->event_slot->event_registrations->count();
                    $slot_treshold  = ($slot_treshold > 1) ? $slot_treshold: 1;


                    $thresholds = [
                        [
                            'percentage'  => $slot_treshold * .9,
                            'description' => '90%',
                        ],
                        
                        [
                            'percentage'  => $slot_treshold * .5,
                            'description' => '50%',
                        ],
                        [
                            'percentage'  => $slot_treshold * .75,
                            'description' => '75%',
                        ],
                        [
                            'percentage'  => $slot_treshold * .25,
                            'description' => '25%',
                        ],

                    ];
                    $throw_notif = false;
                    $notif_to_show = null;
                    foreach( $thresholds as $threshold){
                        if($registered >= $threshold['percentage']){
                            $throw_notif = true;
                            $notif_to_show = $threshold;
                            break;
                        }
                    }
                          
                    if( $throw_notif && $notif_to_show){
                        foreach($record->notifiable() as $recipient){
                            Notification::make()
                                ->title('Event slot reach threshold')
                                ->icon('far-bell')
                                ->body(Str::markdown('The Shift Slot: <b>'.$event_registration->event_slot->shift_name.'</b> for Event: <b>'.$record->title.'</b> <br> Reaches '. $notif_to_show['description'].'  Registrant Capacity!'))
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->button()
                                        ->url(route('filament.admin.resources.events.view', ['record' => $record->id]), shouldOpenInNewTab: true),
                                ])
                                ->sendToDatabase($recipient);
                        }
                    }

                    foreach ($record->notifiable() as $recipient){
                        Notification::make()
                            ->title('You have new event registration for '.$record->title)
                            ->icon('far-bell')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->button()
                                    ->url(route('filament.admin.resources.events.view', ['record' => $record->id]), shouldOpenInNewTab: true),
                            ])
                            ->sendToDatabase($recipient);
                    }

                })
                ->visible(function (Event $record){

                    if(!$record->registrations->where('volunteer_id',auth()->user()->id)->first() && auth()->user()->hasRole(['super_admin'])){
                        return true;
                    }
                    if($record->registrations->where('volunteer_id',auth()->user()->id)->first()){
                        return false;
                    }

                    return (new EventRegistrationButtonVisibilityAction())->execute($record);

                }),
            \Filament\Tables\Actions\Action::make('Cancel Registration')
                ->color('warning')
                ->button()
                ->requiresConfirmation()
                ->action(function (Event $record){

                    EventRegistration::where('event_id', $record->id)->where('volunteer_id',auth()->user()->id)->delete();

                    Notification::make()
                        ->title('Registration has been canceled.')
                        ->success()
                        ->send();
                })
                ->visible(function (Event $record){

                    $registration = $record->registrations->where('volunteer_id',auth()->user()->id)->first();

                    if($registration && $registration->status_id == 1){
                        return true;
                    }

                    return false;


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
                         ->options(function (Event $record){
                             $option = [];
                             foreach($record->slots as $slot) {
                                 $registion_count = $record->registrations->where('slot_type_id',$slot->id)->where('status_id','!=',3)->count();

                                 if($slot->total_slots - $registion_count){
                                     $option[$slot->id] = $slot->shift_name.' ('.Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('g:i A').' - '.Carbon::parse(now()->format('Y-m-d') . $slot->end_time)->format('g:i A').')';
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
                ->action(function (Event $record,array $data){

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

                    $registration = $record->registrations->where('volunteer_id',auth()->user()->id)->first();

                    if($registration && $registration->status_id == 1){
                        return true;
                    }

                    return false;


                })
                ->mountUsing(function (Event $record,ComponentContainer $form){


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

            \Filament\Tables\Actions\ViewAction::make()
                ->mountUsing(function (Event $record,ComponentContainer $form){
                    $media = [];
                    foreach ($record->getMedia('event-attachments') as $media_item) {
                        $index = strlen(storage_path('app/public/'));
                        $media[] = substr($media_item->getPath(), $index);
                    }
                    $data['media'] = $media;

                    $form->fill($data);

                }),
            \Filament\Tables\Actions\EditAction::make()
                ->mountUsing(function (Event $record,ComponentContainer $form){
                    $media = [];
                    foreach ($record->getMedia('event-attachments') as $media_item) {
                        $index = strlen(storage_path('app/public/'));
                        $media[] = substr($media_item->getPath(), $index);
                    }
                    $data['media'] = $media;

                    $form->fill($data);

                }),
            \Filament\Tables\Actions\DeleteAction::make(),
            \Filament\Tables\Actions\ForceDeleteAction::make(),
            \Filament\Tables\Actions\RestoreAction::make(),

        ];
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
