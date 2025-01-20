<?php

namespace App\Filament\Resources\VolunteerResource\RelationManagers;

use App\Models\Event;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventsRelationManager extends RelationManager
{
    protected static string $relationship = 'events';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('events')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Schedule')
                    ->formatStateUsing(function ($record){
                        $date_start = Carbon::parse($record->start_date)->format('d M Y g:i A');
                        $end = Carbon::parse($record->end_date)->format('g:i A');
                        return $date_start.' - '.$end;
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Schedule')
                    ->formatStateUsing(function ($record){
                        // dd($record);
                        $date_start = Carbon::parse($record->start_date)->format('d M Y g:i A');
                        $end = Carbon::parse($record->end_date)->format('g:i A');
                        return $date_start.' - '.$end;
                    }),

                Tables\Columns\TextColumn::make('attendees')
                    ->label('Hrs Rendered')
                    ->formatStateUsing(function ($record) {
                        $attendee = $record->attendees->where('attendee_id', auth()->id())->first();
                        $hrs = 0;
                        if($attendee){
                            $hrs = number_format($attendee->get_totalHrs(),1);
                        }
                        return $hrs;
                    }),

                Tables\Columns\TextColumn::make('is_approve')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(function ($record){
                        if($record->is_approve){
                            return 'Approve';
                        }

                        if($record->is_rejected){
                            return 'Rejected';
                        }
                        return 'Pending';
                    })
                    ->color(function ($record): string {
                        $list = [
                            'Approve' => 'success',
                            'Rejected' => 'danger',
                            'Pending' => 'warning',
                        ];
                    
                        if($record->is_approve){
                            return $list['Approve'];
                        }

                        if($record->is_rejected){
                            return $list['Rejected'];
                        }
                        return $list['Pending'];
                        
                    }),


            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
                Tables\Actions\Action::make('QR')
                    ->label('QR code')
                    ->form(function ($record){

                        $attendee = $record->attendees->where('attendee_id', auth()->id())->first();

                        return [
                            Forms\Components\ViewField::make('rating')
                                ->view('filament.components.qr',['attendee' => $attendee])
                        ];
                    })
                    ->color('success')
                    ->modalWidth('sm')
                    ->icon('heroicon-o-qr-code')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->visible(function ($record){
                        $attendee = $record->attendees->where('attendee_id', auth()->id())->first();
                        if(!$attendee){
                            return false;
                        }
                        if($attendee->is_approve){
                            return false;
                        }
                        return true;
                    }),

                    Tables\Actions\Action::make('edit_logged_hours')
                        ->fillForm(function ($record): array {

                            $attendee = $record->attendees->where('attendee_id', auth()->id())->first();
                            return [
                                'time_in' => $attendee->time_in,
                                'time_out' => $attendee->time_out,
                            ];

                        })

                        ->label('Edit Time In/ Time Out')
                        ->form([
                            Grid::make(2)
                            ->schema([
                                DateTimePicker::make('time_in')
                                    ->seconds(false)
                                    ->minDate(fn ($record) => $record->start_date)
                                    ->maxDate(fn ($record) => $record->end_date)
                                    ->live()
                                    ->displayFormat('Y-m-d h:i A'),
                                DateTimePicker::make('time_out')
                                    ->minDate(fn ( \Filament\Forms\Get $get) => $get('time_in'))
                                    ->maxDate(fn ($record) => $record->end_date)

                                    ->seconds(false),
                            ])  
                        ])
                        ->action(function (array $data, Event $record): void {
                            $attendee = $record->attendees->where('attendee_id', auth()->id())->first();
                            $data['updated_at'] = now();
                            $data['updated_by'] = auth()->user()->id;
                            $attendee->update($data);
                            
                            Notification::make()
                                ->title('Hours Updated')
                                ->success()
                                ->send();
                        })
                        ->icon('heroicon-o-pencil')
                        ->visible(function ($record){
                            $attendee = $record->attendees->where('attendee_id', auth()->id())->first();
                            if(!$attendee){
                                return false;
                            }
                            if($attendee->is_approve){
                                return false;
                            }
                            return true;
                        }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
