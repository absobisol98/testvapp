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

                    {{-- featured opportunity --}}

                    @php
                        $latestOpportunity = $opportunity->get()->sortByDesc('created_at')->first();
                    @endphp


                    @if ($opportunity)
                        <div class="col-span-1 flex items-center justify-between gap-4 bg-[#FFFFFF] p-4">
                            <div
                                class="w-full h-full min-h-[160px] max-w-[150px] flex items-center justify-center overflow-hidden rounded-[20px] shadow-md">
                                <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                                    alt="">
                            </div>

                            <div class="w-full">
                                <div class="w-full">
                                    <p class="capitalize text-[28px] font-bold text-[#03498D] mb-2">
                                        {{ $latestOpportunity->title }}</p>

                                    <div
                                        class="w-full flex flex-row items-center justify-start text-[14px] font-[400] text-[#000000] mb-6 gap-4">
                                        <div class="w-fit">
                                            <p><span class="font-[600]">DATE:</span>
                                                {{ \Carbon\Carbon::parse($latestOpportunity->start_date)->format('M-d-Y') }}
                                            </p>
                                        </div>
                                        <div class="w-fit">
                                                    <p>
                                                        <span class="font-[600]">BATCH:</span>
                                                        {{ \Carbon\Carbon::parse($latestOpportunity->start_date)->format('h:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($latestOpportunity->end_date)->format('h:i A') }}
                                                    </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <a class="w-full" href="\admin/events">
                                        <div
                                            class="h-[40px] w-full bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                            <p class="font-[400] text-[18px] text-white">REGISTER</p>
                                        </div>
                                    </a>

                                    <a class="w-full" href="">
                                        <div
                                            class="h-[40px] w-full bg-[#FFFFFF] flex items-center justify-center p-2 hover:bg-[#f1f1f1]">
                                            <p class="font-[400] text-[18px] text-black">CANCEL</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- STATS --}}
                <div class="w-full grid grid-cols-2 gap-4 mx-auto sm:grid-cols-3 lg:grid-cols-5">
                    {{-- For Volunteers --}}
                    @if (false)
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat1 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">UPCOMING</p>

                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat2 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">CERTIFICATES</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat3 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">APPROVED HRS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat4 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">REMAINING HRS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat5 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">CANCELLED</p>
                        </div>
                    @endif

                    {{-- For AFI Admins --}}
                    @if (false)
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">

                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat1 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">VOLUNTEERS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat2 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">OPPORTUNITIES</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat3 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">ON GOING</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat4 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">HRS RENDERED</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalStat5 }}</p>
                            <p class="text-sm font-bold text-[#03498D]">PARTNERS</p>
                        </div>
                    @endif

                    {{-- For Partners --}}
                    @if (true)
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\User::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">VOLUNTEERS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Event::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">OPPORTUNITIES</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Program::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">PROGRAMS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\Company::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">BUSINESS UNIT</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ \App\Models\EventFacilitator::count() }}</p>
                            <p class="text-sm font-bold text-[#03498D]">FACILITATORS</p>
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

        <div class="w-full flex items-center justify-end">
            <div class="w-[95%] h-[30px] bg-[#F55E1D]"></div>
        </div>
    </div>
</x-filament-widgets::widget>
