<x-filament-widgets::widget>
    {{-- My Opportunities --}}
    <div class="w-full px-8">
        <div class="w-full flex items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">My Opportunities</p>
            </div>

            <div class="w-fit flex items-center justify-between gap-4">
                <button class="w-fit">
                    <div
                        class="h-[48px] w-full bg-[#F55E1D] flex items-center justify-center py-2 px-6 hover:bg-[#FF9141]">
                        <p class="font-[400] text-[18px] text-white">IMPORT</p>
                    </div>
                </button>
            </div>
        </div>

        <div class="w-full border-t border-[#E1E1E1] my-4"></div>

        {{-- STATS --}}
        <div class="w-full grid grid-cols-2 gap-4 mb-8 mx-auto sm:grid-cols-3 lg:grid-cols-5">
            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalStat1 }}</p>
                <p class="text-[14px] font-[700] text-[#03498D]">OPPORTUNITY</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalStat2 }}</p>
                <p class="text-[14px] font-[700] text-[#03498D]">FACILITATORS</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalStat3 }}</p>
                <p class="text-[14px] font-[700] text-[#03498D]">HRS RENDERED</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalStat4 }}</p>
                <p class="text-[14px] font-[700] text-[#03498D]">ON-GOING</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalStat5 }}</p>
                <p class="text-[14px] font-[700] text-[#03498D]">CANCELLED</p>
            </div>
        </div>

        <div class="w-full">
            @livewire(\App\Filament\Widgets\MyOpportunitiesTableWidget::class)
        </div>
    </div>
</x-filament-widgets::widget>
