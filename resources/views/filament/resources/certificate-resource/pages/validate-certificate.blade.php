<x-filament-panels::page>
    <div class="space-y-6">
        <div class="p-6 bg-white rounded-xl shadow">
            <h2 class="text-xl font-bold mb-4">Certificate Validation</h2>
            <form wire:submit="validateCertificate" class="space-y-6">
                {{ $this->form }}

                <x-filament::button type="submit">
                    Validate Certificate
                </x-filament::button>
            </form>
        </div>
    </div>
</x-filament-panels::page>
