<?php

namespace App\Actions;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
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

                    if($record->approval_type == "Automatic"){ // Automatic approved status for registration

                        $attendee = EventAttendee::create([
                            'event_id' => $record->id,
                            'attendee_id' => auth()->user()->id,
                            'facilitator_id' => auth()->id(),
                        ]);

                        // Generate QR code for attendee
                        (new GenerateEventQRCode())->execute($attendee);

                        $status = 2; // Approve - (Automatic)

                    }else{
                        $status = 1; // Pending
                    }

                    $event_registration = EventRegistration::create([
                        'event_id' => $record->id,
                        'volunteer_id' => auth()->user()->id,
                        'slot_type_id' => $data['slot_type_id'],
                        'created_at' => now(),
                        'status_id' => $status,
                    ]);


                    if (isset($data['media']) && $data['media']) {
                        foreach ($data['media'] as $media) {
                            $event_registration->addMedia(storage_path('app/public/'.$media))->preservingOriginal()->toMediaCollection(
                                'event-registration-attachments'
                            );
                        }
                    }

                    Notification::make()
                        ->title('You have successfully registered.')
                        ->success()
                        ->send();
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
}
