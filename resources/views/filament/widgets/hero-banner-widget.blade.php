<x-filament-widgets::widget>
    <div class="w-full">
        <div class="flex items-center justify-center relative min-h-[583px]">
            {{-- Stat Content --}}
            <div class="w-full h-full flex flex-col items-center justify-center gap-8 p-8 z-[1]">
                <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Header Text --}}
                    <div class="col-span-1 text-white flex flex-col items-center sm:items-start">
                        <p class="text-xl md:text-3xl font-bold mb-4">Welcome {{ auth()->user()->name }}</p>
                        <p class="text-3xl md:text-5xl font-bold">Your involvement <br> is important to us!</p>
                    </div>
                </div>

                {{-- STATS --}}
                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center md:grid-cols-3">
                    {{-- For AFI Admins --}}
                    @if (auth()->user()->hasRole('External Partner'))
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.business-units.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $businessunit }}</p>
                                <p class="text-sm font-bold text-[#03498D]">BUSINESS UNIT</p>
                            </a>
                        </div>


                        {{-- <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Event::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">MY OPPORTUNITIES</p>
                        </div> --}}

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.events.index') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $upcoming->count() }}</p>
                                <p class="text-sm font-bold text-[#03498D]">UPCOMING OPPORTUNITIES</p>
                            </a>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{$overall_hrs}}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL HOURS RENDERED</p>
                        </div>

                        {{-- <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]"> {{$businessunit}}</p>
                            <p class="text-sm font-bold text-[#03498D]">PARTNERS</p>
                        </div> --}}
                    @endif
                </div>


                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center md:grid-cols-4">
                    {{-- For Volunteers --}}
                    @if (auth()->user()->hasRole('Volunteer'))
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.events.list') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{$opportunities->count()}}</p>
                                <p class="text-sm font-bold text-[#03498D]">AVAILABLE OPPORTUNITIES</p>
                            </a>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.events.index') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{$upcoming->count()}}</p>
                                <p class="text-sm font-bold text-[#03498D]">MY UPCOMING OPPORTUNITIES</p>
                            </a>
                        </div>
                        {{-- @dd($upcoming); --}}

                        {{-- <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Program::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">PROGRAMS</p>
                        </div> --}}
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{$total_hours_since_creation}}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL HOURS RENDERED</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">0</p>
                            <p class="text-sm font-bold text-[#03498D]">MY VOLUNTEER CERTIFICATES</p>
                        </div>
                    @endif

                    {{-- For Partners --}}
                    @if (auth()->user()->hasRole('super_admin'))
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.volunteers.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $volunteer }}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS</p>
                        </a>
                    </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{$totalHours}}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS HOURS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.programs.index') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Program::count() }}</p>
                                <p class="text-sm font-bold text-[#03498D]">PROGRAMS</p>
                            </a>
                        </div>

                        {{-- <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $businessunit }}</p>
                            <p class="text-sm font-bold text-[#03498D]">BUSINESS UNIT</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\EventFacilitator::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">FACILITATORS</p>
                        </div> --}}

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.events.index') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Event::count() }}</p>
                                <p class="text-sm font-bold text-[#03498D]">OPPORTUNITIES</p>
                            </a>
                        </div>
                    @endif

                    @if (auth()->user()->hasRole('Ayala Super Admin'))
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.volunteers.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $volunteer }}</p>
                                <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS</p>
                            </a>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{$ayala_hours}}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS HOURS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.programs.index') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Program::count() }}</p>
                                <p class="text-sm font-bold text-[#03498D]">PROGRAMS</p>
                            </a>
                        </div>

                        {{-- <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $businessunit }}</p>
                            <p class="text-sm font-bold text-[#03498D]">BUSINESS UNIT</p>
                        </div> --}}
{{--
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\EventFacilitator::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">FACILITATORS</p>
                        </div> --}}

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <a href="{{ route('filament.admin.resources.events.index') }}"
                               class="hover:text-[#FF9141] transition-colors duration-200">
                                <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Event::count() }}</p>
                                <p class="text-sm font-bold text-[#03498D]">OPPORTUNITIES</p>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Stat Background --}}
            <div class="w-full h-full grid grid-cols-5 absolute z-0 bg-gradient-to-tr from-[#03498D] to-[#03498D]">
                <!-- Left Section -->
                <div class="col-span-3 flex items-center justify-center relative overflow-hidden">
                    <div class="w-full h-full absolute inset-0 bg-gradient-to-tr from-[#03498D] to-[#03498D] z-[2]"
                        style="clip-path: polygon(100% -20%, 100% 100%, 50% 100%);"></div>

                    <div
                        class="w-full h-full absolute bg-gradient-to-r from-[#03488d8a] via-[#03488d8a] to-[#03488d8a] z-[1]">
                    </div>

                    <img class="w-full h-full object-cover z-0 hidden md:block" src="{{ asset($bgImg) }}"
                        alt="">
                </div>

                <!-- Right Section -->
                <div class="col-span-2 bg-[#03498D] flex items-center justify-center"></div>
            </div>
        </div>

        {{-- <div class="w-full flex items-center justify-end">
            <div class="w-[95%] h-[30px] bg-[#F55E1D]"></div>
        </div> --}}
    </div>
</x-filament-widgets::widget>
