<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVolunteer extends EditRecord
{
    protected static string $resource = VolunteerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function afterSave(): void
    {
        // Get all selected program IDs
        $programIds = collect($this->data['programs'] ?? [])->toArray();

        if (!empty($programIds)) {
            // First set all to non-primary
            foreach ($programIds as $programId) {
                $this->record->programs()->updateExistingPivot($programId, [
                    'is_primary' => false
                ]);
            }

            // Set the first one as primary
            $primaryProgramId = $programIds[0] ?? null;
            if ($primaryProgramId) {
                $this->record->programs()->updateExistingPivot($primaryProgramId, [
                    'is_primary' => true
                ]);
            }
        }
    }
}
