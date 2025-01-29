<?php

namespace App\Filament\Resources\MessageRoomResource\Pages;

use App\Filament\Resources\MessageRoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMessageRoom extends EditRecord
{
    protected static string $resource = MessageRoomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
