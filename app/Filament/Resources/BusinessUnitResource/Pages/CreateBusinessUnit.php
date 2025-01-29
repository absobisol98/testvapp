<?php

namespace App\Filament\Resources\BusinessUnitResource\Pages;

use App\Filament\Resources\BusinessUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBusinessUnit extends CreateRecord
{
    protected static string $resource = BusinessUnitResource::class;


    protected function handleRecordCreation(array $data): Model
    {
        $data['created_by'] = auth()->user()->id;
        
        return static::getModel()::create($data);
    }

}
