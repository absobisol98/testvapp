<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use App\Models\Scopes\PublishedEventScope;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EventsToApprove extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'External Partners Events';


    public static function canView(): bool
    {
        if (auth()->user()->hasRole('super_admin')) {
            return true;
        } else {
            return false;
        }
    }


    public function table(Table $table): Table
    {
        $ext_partner = User::role('External Partner')->get()->pluck('id');


        return $table
            ->query(
                Event::query()->withoutGlobalScope(PublishedEventScope::class)->where('is_published',false)->orderBy('is_published')->whereIn('created_by',$ext_partner)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_by_user.id')
                    ->label('Business Unit Name')
                    ->formatStateUsing( function ($state){
                        $user = User::find($state);
                        return $user->currentBU()?->name;
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recurrence_type_id')
                    ->label('Recurrence type')
                    ->formatStateUsing(function (Event $record,string $state){
                        if($state == 2){
                            return ucfirst($record->frequency);

                        }
                        return $record->event_recurrence_type->name;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('point_of_contact_id')
                    ->label('HR Representative (Point-of-contact)')
                    ->formatStateUsing(function (Event $record,string $state){
                        $user = User::find($state);
                        return $user->firstname.' '.$user->lastname;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('program.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('sign_up_approval_required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('attachment_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_by_user.firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                // ...
            ])
            ->actions([
                Action::make('approve_events')
                    ->label('Approve')
                    ->modalDescription('Approve this business unit event? ')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record){
                        $record->is_published = true;
                        $record->updated_by = auth()->user()->id;
                        $record->update();

                        
                        Notification::make()
                            ->title('External Partner Event Approve.')
                            ->success()
                            ->send();
                    })


            ]);
    }
}
