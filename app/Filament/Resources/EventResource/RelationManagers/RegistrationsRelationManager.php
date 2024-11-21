<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Models\Event;
use App\Models\EventAttendee;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RegistrationsRelationManager extends RelationManager
{
    protected static string $relationship = 'registrations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('media')
                    ->columnSpanFull()
                    ->collection('event-registration-attachments')
                    ->multiple()
                    ->maxFiles(5)
                    ->label('')
                    ->openable()
                    ->downloadable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status.name')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('volunteer.firstname')
                    ->formatStateUsing(fn ($record): string => $record->volunteer->firstname.''.$record->volunteer->lastname),

                Tables\Columns\TextColumn::make('slot_type_id')
                    ->label('Slot')
                    ->formatStateUsing(function ($record){
                        $slot = $record->event_slot;
                        return $slot->type->name." (".Carbon::parse(now()->format('Y-m-d').$slot->start_time)->format('h:i').' - '.Carbon::parse(now()->format('Y-m-d').$slot->end_time)->format('h:i').")";
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading('Attachments')
                    ->icon('heroicon-o-paper-clip')
                    ->label('See Attachments')
                    ->visible(function ($record){

                        if($record->getMedia('event-registration-attachments')->count()){
                            return true;
                        }

                        return false;

                    }),
                Action::make('approve')
                    ->color('success')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function ($record){

                        $record->status_id = 2;
                        $record->save();

                        Notification::make()
                            ->title('Registration Approved')
                            ->success()
                            ->send();

                    })
                    ->visible(fn($record) => $record->status_id == 1),
                Action::make('reject')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function ($record){
                        $record->status_id = 3;
                        $record->save();

                        Notification::make()
                            ->title('Registration rejected')
                            ->success()
                            ->send();
                    })
                    ->visible(fn($record) => $record->status_id == 1),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
