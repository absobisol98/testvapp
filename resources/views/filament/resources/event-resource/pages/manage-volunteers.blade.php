<x-filament-panels::page>
    <div class="flex flex-col space-y-4">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold">{{ $record->title }}</h2>
            <a href="{{ route('filament.admin.resources.events.view', ['record' => $record->id]) }}"
               class="filament-button filament-button-size-md inline-flex items-center justify-center py-1 gap-1 font-medium rounded-lg border transition-colors focus:outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700">
                Back to Event
            </a>
        </div>

        @livewire(\App\Filament\Resources\EventResource\RelationManagers\RegistrationsRelationManager::class, [
            'ownerRecord' => $record,
            'pageClass' => get_class($this),
        ])

        @livewire(\App\Filament\Resources\EventResource\RelationManagers\AttendeesRelationManager::class, [
            'ownerRecord' => $record,
            'pageClass' => get_class($this),
        ])


    </div>
</x-filament-panels::page>
