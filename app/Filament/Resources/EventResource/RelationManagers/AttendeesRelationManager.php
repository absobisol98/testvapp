<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Event;
use App\Models\EventAttendee;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }


    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->user()->can('manage_attendees_event');
    }


    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('event_id')
            ->recordTitleAttribute('event_id')
            ->columns([

                Tables\Columns\TextColumn::make('slot_type_id')
                    ->label('Slot')
                    ->formatStateUsing(function ($record) {
                        return $record->slot?->name ?? '—';
                    }),
                Tables\Columns\TextColumn::make('id')
                    ->label('Volunteer Name')
                    ->formatStateUsing(function ($record): string {
                        $name = ($record->attendee) ? $record->attendee->firstname.' '.$record->attendee->lastname : ' ';
                        //change dependeing  on encode type
                        if($record->encoding_type == '3'){
                            $name = 'Multiple Attendees';
                        }
                        if($record->encoding_type == '2'){
                            $name = $record->no_account_name;
                        }
                        return $name;
                    }),


                    Tables\Columns\TextColumn::make('time_in')
                        ->label('Time In')
                        ->formatStateUsing(fn ($state) => ($state) ? Carbon::parse($state)->format('Y-m-d h:i A')  : null  ),

                    Tables\Columns\TextColumn::make('time_out')
                        ->label('Time Out')
                        ->formatStateUsing(fn ($state) => ($state) ? Carbon::parse($state)->format('Y-m-d h:i A')  : null  ),


                    Tables\Columns\TextColumn::make('event_id')
                        ->label('Total Hours')
                        ->formatStateUsing(fn ($record) => number_format($record->get_totalHrs(),1)),



                    Tables\Columns\TextColumn::make('updated_at')
                        ->label('Last Change')
                        ->formatStateUsing(fn ($state) => ($state) ? Carbon::parse($state)->format('Y-m-d h:i A')  : null),


                    Tables\Columns\TextColumn::make('updatedBy')
                        ->label('Last Update')
                        ->formatStateUsing(fn ($state) =>  $state->firstname.' '.$state->lastname),

            ])
            ->filters([
                //
            ])

            ->headerActions([
                // ...


                Tables\Actions\Action::make('add_volunteer_hours')
                    ->label('Set Volunteer Hours')
                    ->color('success')
                    ->modalWidth('md')
                    // ->slideOver()
                    ->form([

                        Grid::make(2)
                            ->schema([
                                Select::make('slot_type_id')
                                    ->label('Select Slot')
                                    ->options(function () {
                                        return $this->getOwnerRecord()
                                            ->slots
                                            ->pluck('shift_name', 'id')
                                            ->toArray();
                                    })
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        $slot = $this->getOwnerRecord()->slots->find($state);
                                        if ($slot) {
                                            $set('time_in', $slot->start_time);
                                            $set('time_out', $slot->end_time);
                                        }
                                    })
                                    ->columnSpan(2),

                                DateTimePicker::make('time_in')
                                    ->seconds(false)
                                    ->live()
                                    ->required()
                                    ->minDate(fn () => $this->getOwnerRecord()->start_date)
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->displayFormat('Y-m-d h:i A'),
                                DateTimePicker::make('time_out')
                                    ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->seconds(false),

                                Select::make('select_volunteers')
                                    ->label('Select Volunteers')
                                    ->multiple()
                                    ->columnSpan(2)
                                    ->options(function (\Filament\Forms\Get $get) {
                                        $options = [];
                                        $event = $this->getOwnerRecord();
                                        $slot_type_id = $get('slot_type_id');

                                        $not_attended = $event->attendees()
                                            ->where('time_in', null)
                                            ->where('slot_type_id', $slot_type_id)
                                            ->with('attendee')
                                            ->get();

                                        foreach ($not_attended as $att) {
                                            if ($att->attendee) {
                                                $options[$att->attendee->id] = $att->attendee->firstname . ' ' . $att->attendee->lastname;
                                            }
                                        }
                                        return $options;
                                    })
                                    ->required(),
                            ])
                    ])
                    ->action(function (array $data): void {
                        if(!$data['time_out']){
                            $event_time_end = $this->getOwnerRecord()->end_date;
                            $data['time_out'] =  $event_time_end;
                        }
                        $attendee  = $this->getOwnerRecord()->attendees()
                            ->whereIn('attendee_id',$data['select_volunteers'])
                            ->where('slot_type_id', $data['slot_type_id'])
                            ->get();
                        foreach($attendee as $attendance_details){
                            if( $attendance_details){
                                $attendance_details->update([
                                    'time_in' => $data['time_in'],
                                    'time_out' => $data['time_out'],
                                    'is_approve' => true,
                                    'encoding_type' => 1,
                                    'updated_at' => now(),
                                    'updated_by' => auth()->id(),
                                    'slot_type_id' => $data['slot_type_id']
                                ]);
                            }
                        }
                        Notification::make()
                            ->title('Volunteer Hours Updated')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('md')
                    ->modalDescription('Add volunteer hours to an existing volunteer'),

                Tables\Actions\Action::make('single_wo_account')
                    ->label('Set Volunteer Hours (without account)')
                    ->color('success')
                    ->form([
                        Grid::make(2)
                            ->schema([

                                Select::make('slot_type_id')
                                ->label('Select Slot')
                                ->options(function () {
                                    return $this->getOwnerRecord()
                                        ->slots
                                        ->pluck('shift_name', 'id')
                                        ->toArray();
                                })
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function ($state, Set $set) {
                                    $slot = $this->getOwnerRecord()->slots->find($state);
                                    if ($slot) {
                                        $set('time_in', $slot->start_time);
                                        $set('time_out', $slot->end_time);
                                    }
                                })
                                ->columnSpan(2),
                                DateTimePicker::make('time_in')
                                    ->seconds(false)
                                    ->live()
                                    ->required()
                                    ->minDate(fn () => $this->getOwnerRecord()->start_date)
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->displayFormat('Y-m-d h:i A'),

                                DateTimePicker::make('time_out')
                                    ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->seconds(false),

                                TextInput::make('volunteers')
                                    ->label('How many volunteers?')
                                    ->minValue(1)
                                    ->maxValue(20)

                                    ->numeric()
                                    ->live()
                                    ->columnSpan(2)
                                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                        // dd($get('members'));
                                        $arr = array();
                                        for ($x=0 ;$x<$state; $x++) {
                                            $arr[] = [
                                                'no_name' => null,
                                            ];
                                        }
                                        $set('members',$arr);
                                        // ...
                                    })
                                    ->required(),

                                Repeater::make('members')
                                    ->label('Volunteers')
                                    ->live()
                                    ->reorderable(false)
                                    ->schema([
                                        TextInput::make('no_name')->label('Name')->required(),
                                    ])
                                    ->columnSpan(2)
                                    ->columns(1)
                            ])
                    ])
                    ->slideOver()
                    ->action(function (array $data): void {
                        if(!$data['time_out']){
                            $event_time_end = $this->getOwnerRecord()->end_date;
                            $data['time_out'] =  $event_time_end;
                        }
                        foreach($data['members'] as $attendee){
                            $details = array();
                            $details['time_in'] = $data['time_in'];
                            $details['time_out'] = $data['time_out'];
                            $details['no_account_name'] = $attendee['no_name'];
                            $details['event_id'] = $this->getOwnerRecord()->id;
                            $details['facilitator_id'] = auth()->id();
                            $details['updated_at'] = now();
                            $details['updated_by'] = auth()->user()->id;
                            $details['encoding_type'] = 2;
                            $details['is_approve'] = true;
                            EventAttendee::create($details);
                        }
                        Notification::make()
                            ->title('Volunteer Attendance Added')
                            ->success()
                            ->send();
                    })

                    ->modalWidth('md')
                    ->modalDescription('Add hours to volunteers without accounts'),

                Tables\Actions\Action::make('bulk_adding')
                    ->label('Bulk Attendance')
                    ->color('info')

                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('volunteer_count')
                                    ->numeric()
                                    ->minValue(1)
                                    ->columnSpan(2)
                                    ->required(),
                                DateTimePicker::make('time_in')
                                    ->seconds(false)
                                    ->live()
                                    ->required()
                                    ->minDate(fn () => $this->getOwnerRecord()->start_date)
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->displayFormat('Y-m-d h:i A'),
                                DateTimePicker::make('time_out')
                                    ->required()
                                    ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->seconds(false),
                            ])
                    ])
                    ->action(function (array $data): void {
                        $data['event_id'] = $this->getOwnerRecord()->id;
                        $data['facilitator_id'] = auth()->id();
                        $data['updated_at'] = now();
                        $data['updated_by'] = auth()->user()->id;
                        $data['encoding_type'] = 3;
                        $data['is_approve'] = true;

                        EventAttendee::create($data);

                        Notification::make()
                            ->title('Volunteer Attendance Added')
                            ->success()
                            ->send();
                    })

                    ->modalWidth('sm')
                    ->modalDescription('Total hours will be based on time in and time out and number of volunteers. '),
            ])

            ->bulkActions([

                BulkAction::make('edit_hours')
                    ->label('Edit/Approve Hours')
                    ->fillForm(fn (Collection $records): array => [
                        'volunteers' => $records,
                    ])

                    ->slideOver()
                    ->form([

                        Repeater::make('volunteers')
                            ->label('Volunteers')
                            ->reorderable(false)
                            ->addable(false)
                            ->deletable(false)
                            ->schema([
                                Grid::make(2)
                                ->schema([

                                    TextInput::make('id')
                                        ->hidden(),

                                    TextInput::make('label')
                                        ->label('Name')
                                        ->disabled()
                                        ->formatStateUsing(function (Get $get): string {
                                            $record = EventAttendee::find($get('id'));
                                            if(!$record){
                                                return '';
                                            }
                                            $name = ($record->attendee) ? $record->attendee->firstname.' '.$record->attendee->lastname : ' ';
                                            if($record->encoding_type == '3'){
                                                $name = 'Multiple Attendees';
                                            }
                                            if($record->encoding_type == '2'){
                                                $name = $record->no_account_name;
                                            }
                                            return $name;
                                        })
                                        ->columnSpan(2),

                                    DateTimePicker::make('time_in')
                                        ->seconds(false)
                                        ->live()
                                        ->required()
                                        ->minDate(fn () => $this->getOwnerRecord()->start_date)
                                        ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                        ->displayFormat('Y-m-d h:i A'),
                                    DateTimePicker::make('time_out')
                                        ->required()
                                        ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                        ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                        ->seconds(false),
                                ])
                            ])
                            ->columnSpan(2)
                            ->columns(1),
                    ])
                    ->action(function (Collection $records, array $data){
                        foreach ($data['volunteers'] as $volunteer) {
                            $volunteer['updated_by'] = auth()->user()->id;
                            $volunteer['updated_at'] = now();
                            $update = EventAttendee::where('id', $volunteer['id'])->first();
                            $update->update($volunteer);
                        }

                        Notification::make()
                            ->title('Updated')
                            ->body('Hours has been update on selected volunteers')
                            ->success()
                            ->send();
                    }),

                BulkAction::make('delete')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn (Collection $records) => $records->each->delete())
                // ...
            ])
            ->actions([

                Action::make('approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Approve this volunteer attendance? ')
                    ->visible(fn (EventAttendee $record) => ($record->is_approve)? false : true)
                    ->action(function (EventAttendee $record): void {
                        if( $record->time_in){
                            if(!$record->time_out){
                                $data['time_out'] = $record->event->end_date;
                            }
                            $data['updated_at'] = now();
                            $data['updated_by'] = auth()->user()->id;
                            $data['is_approve'] = true;
                            $record->update($data);
                            Notification::make()
                                ->title('Total Hours Approve')
                                ->success()
                                ->send();
                        }
                        else{
                            Notification::make()
                                ->title('Approval Error')
                                ->body('Please input Time In')
                                ->danger()
                                ->send();
                        }

                    }),

                    Action::make('reject')
                        ->color('danger')
                        ->label('Decline')
                        ->requiresConfirmation()
                        ->modalHeading('Deny Hours')
                        ->modalDescription('This will remove encoded hours and status changed to rejected')
                        ->visible(fn (EventAttendee $record) => ($record->is_approve)? false : true)
                        ->action(function (EventAttendee $record): void {
                            $record->time_out = null;
                            $record->time_in = null;
                            $record->is_rejected = true;
                            $record->update();



                            Notification::make()
                                ->title('Hours Decline')
                                ->icon('far-bell')
                                ->body(Str::markdown('Encoded Hours for event:  <b>'.$record->event->title.'</b> has been denied. Please re-encode'))
                                ->actions([
                                    \Filament\Notifications\Actions\Action::make('view')
                                        ->button()
                                        ->url(route('filament.admin.resources.volunteers.view', ['record' => $record->attendee_id]), shouldOpenInNewTab: true),
                                ])
                                ->sendToDatabase($record->attendee);


                            Notification::make()
                                ->title('Hours Denied')
                                ->success()
                                ->send();

                        }),


                Tables\Actions\Action::make('edit_hours')
                ->fillForm(fn (EventAttendee $record): array => [
                    'time_in' => $record->time_in,
                    'time_out' => $record->time_out
                ])
                    ->form([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('time_in')
                                    ->seconds(false)
                                    ->required()
                                    ->minDate(fn () => $this->getOwnerRecord()->start_date)
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->live()
                                    ->displayFormat('Y-m-d h:i A'),
                                DateTimePicker::make('time_out')
                                    ->required()
                                    ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                    ->maxDate(fn () => $this->getOwnerRecord()->end_date)
                                    ->seconds(false),
                            ])
                    ])
                    ->visible(fn (EventAttendee $record) => ($record->is_approve)? false : true)
                    ->action(function (array $data, EventAttendee $record): void {
                        $data['updated_at'] = now();
                        $data['updated_by'] = auth()->user()->id;
                        $data['is_approve'] = true;
                        $record->update($data);

                        Notification::make()
                            ->title('Hours Updated')
                            ->success()
                            ->send();
                    })
                    ->icon('heroicon-o-pencil'),
            ]);
    }
}
