<?php

namespace App\Filament\Resources\MessageRoomResource\Pages;

use App\Filament\Resources\MessageRoomResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions;
use App\Filament\Resources\MessageRoomResource\Widgets\ChatRoom;



class ViewMessageRoom extends ViewRecord
{
    protected static string $resource = MessageRoomResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->url(MessageRoomResource::getUrl())
                ->color('gray'),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ChatRoom::class,
        ];
    }




}
