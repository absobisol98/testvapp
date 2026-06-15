<x-filament-widgets::widget>
    {{-- Volunteers --}}
    <div class="w-full px-8">
        <div class="w-full flex flex-row items-start md:items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#0433ff] font-[700]">Volunteers</p>
            </div>

            <div class="w-fit flex items-center justify-between gap-4">
                <a href="/admin/volunteers/create">
                    <button class="w-fit">
                        <div
                            class="h-auto md:h-[48px] w-full bg-[#0433ff] flex items-center rounded-full justify-center py-0 md:py-2 px-2 md:px-6  hover:bg-[#1A67B1]">
                            <p class="font-normal text-lg text-white hidden md:block">CREATE NEW VOLUNTEER</p>

                            <!-- Tablet and Mobile text -->
                            <p class="text-white whitespace-nowrap block md:hidden text-2xl font-bold">+</p>
                    </button>
                </a>

                {{-- <a href="">
                    <button class="w-fit">
                        <div
                            class="h-[48px] w-full bg-[#F55E1D] flex items-center justify-center py-2 px-6 hover:bg-[#f7723a]">
                            <p class="font-normal text-base md:text-lg text-white">IMPORT</p>
                        </div>
                    </button>
                </a> --}}
            </div>
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
                <p class="text-sm font-bold text-[#0433ff]">FACILITATORS</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat3 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">ACTIVE</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat4 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">INACTIVE</p>
            </div>

            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat5 }}</p>
                <p class="text-sm font-bold text-[#0433ff]">PENDING ACTIVATION</p>
            </div>
        </div> --}}

        <div class="w-full">
            @livewire(\App\Filament\Widgets\VolunteersTableWidget::class)
        </div>
    </div>
</x-filament-widgets::widget>
