<?php

namespace App\Filament\Resources\EventResource\RelationManagers;

use App\Actions\EventRegistrationButtonVisibilityAction;
use App\Actions\GenerateEventQRCode;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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

    // public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    // {
    //     return auth()->user()->hasRole('super_admin');
    // }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query){
                if(!auth()->user()->hasRole('super_admin')){ // If not super_admin
                    $query = $query->where('volunteer_id',auth()->user()->id);
                }

                return $query;
            })
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
                    }),

                Tables\Columns\TextColumn::make('message')
                    ->label('Notification'),

            ])
            ->filters([
                //
            ])
            ->headerActions([
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

                        $attendee = EventAttendee::create([
                            'event_id' => $record->event_id,
                            'attendee_id' => $record->volunteer_id,
                            'facilitator_id' => auth()->id(),
                        ]);

                        // Generate QR code for attendee
                        (new GenerateEventQRCode())->execute($attendee);

                        // Notify the registrant
                        $message = 'Your registration for '.$record->event->title.' on '.Carbon::parse($record->event->start_date)->format('M d, Y').' has been approved.';


                        $record->status_id = 2;
                        $record->message = $message;
                        $record->save();

                        Notification::make()
                            ->title('Registration Approved')
                            ->success()
                            ->send();

                        Notification::make()
                            ->title($message)
                            ->icon('far-bell')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->button()
                                    ->url(route('filament.admin.resources.events.view', ['record' => $record->event->id]), shouldOpenInNewTab: true),
                            ])
                            ->sendToDatabase($record->volunteer);

                    })
                    ->visible(fn($record) => $record->status_id == 1 && auth()->user()->hasRole('super_admin')),
                Action::make('reject')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('message')
                            ->placeholder('Your registration has been rejected...')
                            ->maxLength(150)
                            ->required(),
                    ])
                    ->action(function ($record, array $data){

                        $record->status_id = 3; // Status Reject
                        $record->message = $data['message'];
                        $record->save();

                        // Notify the registrant
                        Notification::make()
                            ->title($data['message'])
                            ->color('warning')
                            ->icon('far-bell')
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('view')
                                    ->button()
                                    ->url(route('filament.admin.resources.events.view', ['record' => $record->event->id]), shouldOpenInNewTab: true),
                            ])
                            ->sendToDatabase($record->volunteer);

                        Notification::make()
                            ->title('Registration rejected')
                            ->success()
                            ->send();
                    })
                    ->visible(fn($record) => $record->status_id == 1 && auth()->user()->hasRole('super_admin')),
                Action::make('Cancel')
                    ->color('warning')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function ($record){

                        $record->delete();

                        Notification::make()
                            ->title('Registration has been canceled.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn($record) => $record->volunteer_id == auth()->id() && $record->status_id == 1),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
