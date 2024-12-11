<x-filament-panels::page>
    <style>
        /* For AFI Dashboard Container(Start) */
        .fi-main {
            margin: 0px !important;
            padding: 0px 0px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            border-radius: 0px !important;
            max-width: 100% !important;
        }

        .fi-page section {
            padding: 0px 0px 32px 0px !important;
        }

        /* For AFI Dashboard Container(End) */
    </style>

    <div class="w-full flex flex-col items-center justify-between gap-8">
        {{-- Hero Banner --}}
        <div class="w-full">
            <div class="flex items-center justify-center relative min-h-[583px]">
                {{-- Stat Content --}}
                <div class="w-full h-full flex flex-col items-center justify-center gap-8 p-8 z-[1]">
                    <div class="w-full grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Header Text --}}
                        <div class="col-span-1 text-white flex flex-col items-center sm:items-start">
                            <p class="text-[30px] font-[700] mb-4">Welcome AFI Admin</p>
                            <p class="text-[55px] font-[700]">Your involvement <br> is important to us!</p>
                        </div>

                        {{--  --}}
                        <div class="col-span-1 flex items-center justify-start gap-4 bg-[#FFFFFF] p-4">
                            <div
                                class="w-full h-full min-h-[160px] max-w-[150px] flex items-center justify-center overflow-hidden rounded-[20px] shadow-md">
                                <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                                    alt="">
                            </div>

                            <div class="w-full">
                                <div class="w-full">
                                    <p class="capitalize text-[28px] font-bold text-[#03498D] mb-2">{{ $opportunity->title }}</p>

                                    <div
                                        class="w-full flex flex-row items-center justify-start text-[14px] font-[400] text-[#000000] mb-6 gap-4">
                                        <div class="w-fit">
                                            <p class="font-[600]">DATE:
                                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                            </p>
                                        </div>
                                        <div class="w-fit">
                                            @foreach ($opportunity->slots as $index => $slot)
                                                @if ($index == 0)
                                                    <p>
                                                        <span class="font-[600]">BATCH {{ $index + 1 }}:</span>
                                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                    </p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <a class="w-full" href="">
                                        <div
                                            class="h-[40px] w-full bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                            <p class="font-[400] text-[18px] text-white">CHECK-IN</p>
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
                    </div>

                    {{-- STATS --}}
                    <div class="w-full grid grid-cols-2 gap-4 mx-auto sm:grid-cols-3 lg:grid-cols-5">
                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalVolunteers }}</p>
                            <p class="text-[14px] font-[700] text-[#03498D]">VOLUNTEERS</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalOpportunities }}</p>
                            <p class="text-[14px] font-[700] text-[#03498D]">OPPORTUNITIES</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalOnGoing }}</p>
                            <p class="text-[14px] font-[700] text-[#03498D]">ON GOING</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalHrsRendered }}</p>
                            <p class="text-[14px] font-[700] text-[#03498D]">HRS RENDERED</p>
                        </div>

                        <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                            <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalPartners }}</p>
                            <p class="text-[14px] font-[700] text-[#03498D]">PARTNERS</p>
                        </div>
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

                        <img class="w-full h-full object-cover z-0 hidden md:block" src="{{ asset('img/hero-banner-bg_2.jpg') }}"
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

        {{-- Opportunities --}}
        <div class="w-full px-8">
            <div class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="w-fit">
                    <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Opportunities</p>
                </div>

                <button class="w-fit">
                    <div
                        class="h-[48px] w-full bg-[#005096] flex items-center justify-center py-2 px-6 hover:bg-[#1A67B1]">
                        <p class="font-[400] text-[18px] text-white">CREATE NEW OPPORTUNITY</p>
                    </div>
                </button>
            </div>

            <div class="w-full border-t border-[#E1E1E1] my-4"></div>

            <div class="w-full flex flex-col items-center justify-between gap-4">
                {{-- List --}}
                @foreach ($opportunities as $opportunity)
                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                        <div
                            class="w-fit h-fit md:w-[250px] md:h-[180px] flex items-center justify-center overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                                alt="">
                        </div>

                        <div class="w-full">
                            <p class="capitalize text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}</p>

                            <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, {{ $opportunity->location }}
                            </p>

                            <div
                                class="w-full h-fit flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                                <div class="w-fit flex flex-col items-start justify-between gap-0">
                                    <p class="font-[600]">DATE:
                                        {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}</p>
                                    <p class="font-[600]">
                                        {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}</p>

                                    <p><span class="font-[600]">SHIFTS:</span> Listen attentively and ...</p>
                                    @foreach ($opportunity->slots as $index => $slot)
                                        <p>
                                            <span class="font-[600]">BATCH {{ $index + 1 }}:</span>
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                        </p>
                                    @endforeach
                                </div>

                                <div class="h-full min-h-[118px] max-h-[118px] border-l border-[#B6B6B6] mx-4"></div>

                                <div class="w-fit grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div
                                        class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-4">
                                        <p class="text-[40px] font-[700] text-[#F55E1D]">140</p>
                                        <p class="text-[14px] font-[700] text-[#03498D]">VOLUNTEERS</p>
                                    </div>

                                    <div
                                        class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-4">
                                        <p class="text-[40px] font-[700] text-[#F55E1D]">5</p>
                                        <p class="text-[14px] font-[700] text-[#03498D]">HOURS</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="w-fit flex items-center justify-between gap-8 p-4">
                            <x-filament::icon-button icon="heroicon-s-pencil-square" wire:click="openNewUserModal"
                                size="xl" label="Edit" />
                            <x-filament::icon-button icon="heroicon-s-trash" wire:click="openDeleteConfirmation"
                                size="xl" label="Delete" />

                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" wire:model="status" />
                                <div
                                    class="w-11 h-6 bg-[#E6E0E9] rounded-full ring-2 ring-[#79747E] peer-checked:bg-[#65558F] peer-checked:ring-[#65558F] peer-checked:after:bg-white peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-[#79747E] after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                                </div>
                            </label>
                        </div>

                    </div>

                    @if (!$loop->last)
                        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                    @endif
                @endforeach
            </div>

            <div class="w-full border-t border-[#E1E1E1] mt-4"></div>
        </div>

        {{-- Business Unit / External Partners --}}
        <div class="w-full px-8">
            <div class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="w-fit">
                    <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Business Unit / External Partners
                    </p>
                </div>

                <button class="w-fit">
                    <div
                        class="h-[48px] w-full bg-[#005096] flex items-center justify-center py-2 px-6 hover:bg-[#1A67B1]">
                        <p class="font-[400] text-[18px] text-white">CREATE NEW PARTNER</p>
                    </div>
                </button>
            </div>

            <div class="w-full border-t border-[#E1E1E1] my-4"></div>

            {{-- STATS --}}
            <div class="w-full grid grid-cols-2 gap-4 mx-auto sm:grid-cols-3 lg:grid-cols-5 mb-8">
                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalVolunteers }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">PARTNERS</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalOpportunities }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">APPLICATIONS</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalOnGoing }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">BUSINESS UNIT</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalHrsRendered }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">EXTERNAL PARTNER</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalPartners }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">FACILITATOR</p>
                </div>
            </div>

            <div class="w-full">
                @livewire(\App\Livewire\PartnersTableWidget::class)
            </div>
        </div>

        {{-- Volunteers --}}
        <div class="w-full px-8">
            <div class="w-full flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="w-fit">
                    <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Opportunities</p>
                </div>

                <div class="w-fit flex items-center justify-between gap-4">
                    <button class="w-fit">
                        <div
                            class="h-[48px] w-full bg-[#005096] flex items-center justify-center py-2 px-6 hover:bg-[#1A67B1]">
                            <p class="font-[400] text-[18px] text-white">CREATE NEW VOLUNTEER</p>
                        </div>
                    </button>

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
            <div class="w-full grid grid-cols-2 gap-4 mx-auto sm:grid-cols-3 lg:grid-cols-5 mb-8">
                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalVolunteers }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">PARTNERS</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalOpportunities }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">FACILITATORS</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalOnGoing }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">ACTIVE</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalHrsRendered }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">INACTIVE</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-8">
                    <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalPartners }}</p>
                    <p class="text-[14px] font-[700] text-[#03498D]">PENDING ACTIVATION</p>
                </div>
            </div>

            <div class="w-full">
                @livewire(\App\Livewire\VolunteersTableWidget::class)
            </div>
        </div>
    </div>
</x-filament-panels::page>
