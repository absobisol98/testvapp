<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use App\Imports\VolunteersImport;
use App\Models\Volunteer;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;

class ListVolunteers extends ListRecords
{
    protected static string $resource = VolunteerResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Import Volunteers')
                ->form([
                    FileUpload::make('file')
                        ->label('CSV or Excel File')
                        ->required()
                        ->acceptedFileTypes(['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->maxSize(5120),
                ])
                ->action(function (array $data) {
                    try {
                        $import = new VolunteersImport();
                        Excel::import($import, $data['file']);

                        Notification::make()
                            ->success()
                            ->title('Import Complete')
                            ->body("Created: {$import->created} | Updated: {$import->updated} | Skipped: {$import->skipped}")
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Import Failed')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Actions\CreateAction::make()
                ->label('Add Volunteer'),
        ];
    }
}
