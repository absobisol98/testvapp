<x-filament-widgets::widget>
    {{-- Facilitator --}}
    <div class="w-full px-8">
        <div class="w-full flex items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Facilitator</p>
            </div>

            <a href="">
                <div class="w-fit flex items-center justify-between gap-4">
                    <button class="w-fit">
                        <div
                            class="h-auto md:h-[48px] w-full bg-[#005096] rounded-full flex items-center justify-center py-0 md:py-2 px-2 md:px-6 hover:bg-[#1A67B1]">
                            <p class="font-normal text-lg text-white hidden md:block">ASSIGN NEW FACILITATOR</p>

                            <!-- Tablet and Mobile text -->
                            <p class="text-white whitespace-nowrap block md:hidden text-2xl font-bold">+</p>
                        </div>
                    </button>
                </div>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1] my-4"></div>

        <div class="w-full">
            @livewire(\App\Filament\Widgets\FacilitatorsTableWidget::class)
        </div>
    </div>
</x-filament-widgets::widget>
