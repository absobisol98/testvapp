<x-filament-widgets::widget>
    {{-- Business Unit / External Partners --}}
    <div class="w-full px-8">
        <div class="w-full flex items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#0433ff] font-[700]">Business Unit / External Partners
                </p>
            </div>

            <a href="/admin/business-units/create">
                <button class="w-fit">
                    <div class="h-auto md:h-[48px] w-full bg-[#0433ff] flex items-center justify-center rounded-full py-0 md:py-2 px-2 md:px-6 hover:bg-[#1A67B1]">
                        <p class="font-normal text-lg text-white hidden md:block">CREATE NEW PARTNER</p>

                        <!-- Tablet and Mobile text -->
                        <p class="text-white whitespace-nowrap block md:hidden text-2xl font-bold">+</p>
                    </div>
                </button>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1] my-4"></div>

        {{-- STATS --}}
        {{-- <div class="w-full grid grid-cols-2 gap-4 mb-8 mx-auto sm:grid-cols-3 lg:grid-cols-5">
            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat1 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">PARTNERS</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat2 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">APPLICATIONS</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat3 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">BUSINESS UNIT</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat4 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">EXTERNAL PARTNER</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat5 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">FACILITATOR</p>
            </div>
        </div> --}}

        <div class="w-full">
            @livewire(\App\Filament\Widgets\PartnersTableWidget::class)
        </div>
    </div>
</x-filament-widgets::widget>
