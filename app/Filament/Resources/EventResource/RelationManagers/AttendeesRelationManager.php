<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\EventAttendee;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
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
                Tables\Columns\TextColumn::make('attendee.firstname')
                    ->label('Volunteer Name')
                    ->formatStateUsing(fn ($record): string => $record->attendee->firstname.' '.$record->attendee->lastname),


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
            ->actions([

                Action::make('approve')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalDescription('Approve this volunteer attendance?')
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
                    ->form([
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('time_in')
                                    ->seconds(false)
                                    ->live()
                                    ->displayFormat('Y-m-d h:i A'),
                                DateTimePicker::make('time_out')
                                    ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                    ->seconds(false),
                            ])
                    ])
                    ->action(function (array $data, EventAttendee $record): void {
                        $data['updated_at'] = now();
                        $data['updated_by'] = auth()->user()->id;
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
