<?php

namespace App\Filament\Resources\BusinessUnitResource\Pages;

use App\Filament\Resources\BusinessUnitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;


class EditBusinessUnit extends EditRecord
{
    protected static string $resource = BusinessUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('visit_page')
                ->label('Visit Page')
                ->color('warning')
                ->url(route('businessunit.homepage.view',['slug' => $this->record->slug]),shouldOpenInNewTab:true),
            DeleteAction::make(),
        ];
    }
}
