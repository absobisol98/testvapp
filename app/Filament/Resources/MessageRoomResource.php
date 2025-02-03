<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageRoomResource\Pages;
use App\Filament\Resources\MessageRoomResource\RelationManagers;
use App\Models\MessageRoom;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;



class MessageRoomResource extends Resource
{
    protected static ?string $model = MessageRoom::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static bool $shouldRegisterNavigation = false;
    public static function form(Form $form): Form
    {

                return $form
                ->schema([
                    Forms\Components\Select::make('event_id')
                        ->label('Event Name')
                        ->relationship('event', 'title')
                        ->required(),
                    // Forms\Components\TextInput::make('name')
                    //     ->required()
                    //     ->maxLength(255),
                ]);


    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event.title')
                    ->label('Event Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('messages_count')
                    ->counts('messages')
                    ->label('Messages'),
                Tables\Columns\TextColumn::make('participants_count')
                    ->counts('participants')
                    ->label('Participants'),

            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([])
            ->defaultView('grid')
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMessageRooms::route('/'),
            'create' => Pages\CreateMessageRoom::route('/create'),
            'edit' => Pages\EditMessageRoom::route('/{record}/edit'),
            'view' => Pages\ViewMessageRoom::route('/{record}'),
        ];
    }

}
