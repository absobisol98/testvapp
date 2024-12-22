<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Event;
use App\Models\EventAttendee;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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


    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('event_id')
            ->recordTitleAttribute('event_id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Volunteer Name')
                    ->formatStateUsing(function ($record): string {
                        $name = ($record->attendee) ? $record->attendee->firstname.' '.$record->attendee->lastname : null;
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
                        ->formatStateUsing(fn ($record) => number_format($record->get_totalHrs(),1)),

                    
                    Tables\Columns\TextColumn::make('updated_by')
                        ->label('Last Update')
                        ->formatStateUsing(fn ($record) => number_format($record->get_totalHrs(),1)),

            ])
            ->filters([
                //
            ])

            ->headerActions([
                // ...


                Tables\Actions\Action::make('single_w_account')
                    ->label('Add attendee')
                    ->color('success')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Select::make('attendee_id')
                                    ->label('Volunteer')
                                    ->columnSpan(2)
                                    ->options(function (){
                                        $options = array();
                                        $event = $this->getOwnerRecord();
                                        $atttended = $event->attendees->pluck('attendee_id');
                                        $not_attended = $event->registrations->whereNotIn('volunteer_id',$atttended);

                                        foreach($not_attended as $attendee){
                                            $options[$attendee->volunteer_id] = $attendee->volunteer->firstname.' '.$attendee->volunteer->lastname;
                                        }
                                        return $options;
                                    })
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
                        $data['encoding_type'] = 1;
                        $data['is_approve'] = true;

                        EventAttendee::create($data);

                        Notification::make()
                            ->title('Volunteer Attendance Added')
                            ->success()
                            ->send();
                    })
                    
                    ->modalWidth('md')
                    ->modalDescription('Add a registerered volunteer with account'),

                Tables\Actions\Action::make('single_wo_account')
                    ->label('Add attendee (without account)')
                    ->color('success')
                    ->form([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('no_account_name')
                                    ->label('Volunteer Name')
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
                        $data['encoding_type'] = 2;
                        $data['is_approve'] = true;

                        EventAttendee::create($data);

                        Notification::make()
                            ->title('Volunteer Attendance Added')
                            ->success()
                            ->send();
                    })
                    
                    ->modalWidth('md')
                    ->modalDescription('Add a volunteers that no account'),

                Tables\Actions\Action::make('bulk_adding')
                    ->label('Multiple Attendee')
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
                    ->modalDescription('Adding multiple attendee will automatically add total hrs and multiply it by number of volunteers join.'),
            ])


            ->actions([

                Action::make('approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Approve this volunteer attendance?')
                    ->visible(fn (EventAttendee $record) => ($record->is_approve)? false : true)
                    ->action(function (EventAttendee $record): void {

                        if( $record->time_in && $record->time_in){

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
                                ->body('Please input Time In and Time Out')
                                ->danger()
                                ->send();
                        }
                      
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
