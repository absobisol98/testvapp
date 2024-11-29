<?php

namespace App\Filament\Resources\VolunteerResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
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
                    ->modalWidth('sm')
                    ->icon('heroicon-o-qr-code')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->visible(fn($record) => $record->attendees->where('attendee_id', auth()->id())->first()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
