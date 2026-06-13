<x-filament-widgets::widget>
    <div class="w-full">
        <div class="flex items-center justify-center relative min-h-[583px]">

            {{-- Content --}}
            <div class="w-full h-full flex flex-col items-center justify-center gap-8 p-8 z-[1]">

                {{-- Heading --}}
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="col-span-1 text-white flex flex-col items-center sm:items-start">
                        <p class="text-xl md:text-3xl font-bold mb-4">Welcome {{ auth()->user()->name }}</p>
                        <p class="text-3xl md:text-5xl font-bold">Your involvement <br> is important to us!</p>
                    </div>
                </div>

                {{-- VOLUNTEER stats --}}
                @if($activeRole === 'Volunteer')
                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center md:grid-cols-4">
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.events.list') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $availableOpportunities }}</p>
                            <p class="text-sm font-bold text-[#03498D]">AVAILABLE OPPORTUNITIES</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.events.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $myUpcomingOpportunities }}</p>
                            <p class="text-sm font-bold text-[#03498D]">MY UPCOMING OPPORTUNITIES</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $myHoursRendered }}</p>
                        <p class="text-sm font-bold text-[#03498D]">TOTAL HOURS RENDERED</p>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $myCertificates }}</p>
                        <p class="text-sm font-bold text-[#03498D]">MY VOLUNTEER CERTIFICATES</p>
                    </div>
                </div>
                @endif

                {{-- ADMIN / SUPER ADMIN stats --}}
                @if(in_array($activeRole, ['admin', 'super_admin', 'Ayala Super Admin', 'author']))
                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center">
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.volunteers.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalVolunteers }}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalVolunteerHours }}</p>
                        <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS HOURS</p>
                    </div>
                </div>
                @endif

                {{-- EXTERNAL PARTNER stats --}}
                @if($activeRole === 'External Partner')
                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center md:grid-cols-3">
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.events.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $availableOpportunities }}</p>
                            <p class="text-sm font-bold text-[#03498D]">AVAILABLE OPPORTUNITIES</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.events.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $upcomingCount }}</p>
                            <p class="text-sm font-bold text-[#03498D]">UPCOMING OPPORTUNITIES</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalVolunteerHours }}</p>
                        <p class="text-sm font-bold text-[#03498D]">TOTAL HOURS RENDERED</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- Background --}}
            <div class="w-full h-full grid grid-cols-5 absolute z-0 bg-gradient-to-tr from-[#03498D] to-[#03498D]">
                <div class="col-span-3 flex items-center justify-center relative overflow-hidden">
                    <div class="w-full h-full absolute inset-0 bg-gradient-to-tr from-[#03498D] to-[#03498D] z-[2]"
                        style="clip-path: polygon(100% -20%, 100% 100%, 50% 100%);"></div>
                    <div class="w-full h-full absolute bg-gradient-to-r from-[#03488d8a] via-[#03488d8a] to-[#03488d8a] z-[1]"></div>
                    <img class="w-full h-full object-cover z-0 hidden md:block" src="{{ asset($bgImg) }}" alt="">
                </div>
                <div class="col-span-2 bg-[#03498D] flex items-center justify-center"></div>
            </div>

        </div>
    </div>
</x-filament-widgets::widget>
