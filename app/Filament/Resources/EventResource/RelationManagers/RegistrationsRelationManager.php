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

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return auth()->user()->can('manage_registrations_event');
    }


    public function table(Table $table): Table
    {


            
        return $table
            ->modifyQueryUsing(function (Builder $query){
                $user = auth()->user();
                
                // If super admin or external partner, return all registrations
                if($user->hasRole('super_admin') || $user->hasRole('External Partner')){
                    return $query;
                }
                
                // Get the event (owner record)
                $event = $this->getOwnerRecord();
                
                // Check if user is a facilitator for this event
                $isFacilitator = $event->facilitators->contains($user->id);
                
                // Check if user is the creator of this event
                $isCreator = $event->created_by == $user->id;
                
                // If user is neither facilitator nor creator, only show their own registrations
                if(!$isFacilitator && !$isCreator) {
                    $query->where('volunteer_id', $user->id);
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
                    ->visible(function ($record){
                        if($record->status_id == 1){
                            $user = auth()->user();
                            $isSuperAdmin = $user->hasRole('super_admin');
                            $isAdmin = $user->hasRole('Ayala Super Admin');
                            $isCreator = $record->event->created_by == $user->id;
                            $isFacilitator = $record->event->facilitators->contains($user->id);
                            
                            return $isSuperAdmin || $isAdmin || $isCreator || $isFacilitator;
                        }
                        return false;
                    }),
                Action::make('reject')
                    ->color('danger')
                    ->button()
                    ->requiresConfirmation()
                    ->form([
                        Radio::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->options([
                                'positions_filled' => 'Positions already filled or unavailable',
                                'skills_mismatch' => 'Mismatch in skills for the role',
                            ])
                            ->required()
                    ])
                    ->action(function ($record, array $data) {
                        $reasonText = $data['rejection_reason'] === 'positions_filled'
                            ? 'Positions already filled or unavailable'
                            : 'Mismatch in skills for the role';

                        $message = "{$reasonText}.\n\nWe are unable to offer you this position at this time, but we truly appreciate your willingness to contribute. Please stay tuned for upcoming openings or directly contact the program manager for this opportunity.";

                        $record->status_id = 3; // Status Reject
                        $record->message = $message;
                        $record->save();

                        // Notify the registrant
                        Notification::make()
                            ->title($reasonText)
                            ->body($message)
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
                   ->visible(function ($record) {
                        if ($record->status_id == 1) {
                            $user = auth()->user();
                            $isSuperAdmin = $user->hasRole('super_admin');
                            $isAdmin = $user->hasRole('Ayala Super Admin');
                            $isCreator = $record->event->created_by == $user->id;
                            $isFacilitator = $record->event->facilitators->contains($user->id);
                            
                            return $isSuperAdmin || $isAdmin || $isCreator || $isFacilitator;
                        }
                        return false;
                    }),
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
