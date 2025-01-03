<x-filament-widgets::widget>
    {{-- Facilitator --}}
    <div class="w-full px-8">
        <div class="w-full flex items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Facilitator</p>
            </div>

            <div class="w-fit flex items-center justify-between gap-4">
                <button class="w-fit">
                    <div
                        class="h-[48px] w-full bg-[#005096] flex items-center justify-center py-2 px-6 hover:bg-[#1A67B1]">
                        <p class="font-[400] text-[18px] text-white">ASSIGN NEW FACILITATOR</p>
                    </div>
                </button>
            </div>
        </div>

        <div class="w-full border-t border-[#E1E1E1] my-4"></div>

        <div class="w-full">
            @livewire(\App\Filament\Widgets\FacilitatorsTableWidget::class)
        </div>
    </div>
</x-filament-widgets::widget>
