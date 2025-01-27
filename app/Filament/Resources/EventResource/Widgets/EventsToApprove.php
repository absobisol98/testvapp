<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class EventsToApprove extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'External Partners Events';


    public function table(Table $table): Table
    {
        $ext_partner = User::role('External Partner')->get()->pluck('id');


        return $table
            ->query(
                Event::query()->orderBy('is_published')->whereIn('created_by',$ext_partner)
            )
            ->columns([
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
                Tables\Columns\TextColumn::make('status.name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('sign_up_approval_required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('attachment_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_by_user.firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_by_user.firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                    
                // ...
            ]);
    }
}
