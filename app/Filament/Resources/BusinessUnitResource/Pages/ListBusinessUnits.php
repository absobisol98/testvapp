<?php

namespace App\Filament\Resources\BusinessUnitResource\Pages;

use App\Filament\Resources\BusinessUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinessUnits extends ListRecords
{
    protected static string $resource = BusinessUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function mount(): void
    {
        $this->verifyuser();
    }

    public function verifyuser(){
        if(auth()->user()->hasRole('External Partner')){
            redirect()->route('filament.admin.resources.business-units.edit',['record' => auth()->user()->currentBU()->id]);
        }
    }
}
