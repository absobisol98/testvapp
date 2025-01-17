<x-filament-panels::page>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
    </head>
    <style>
        .fi-main {
            margin: 0px !important;
            padding: 20px 20px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            border-radius: 0px !important;
            max-width: 100% !important;
            max-height: 100% !important;
        }

        .fi-page section {
            padding: 0px 0px 32px 0px !important;
        }
    </style>

    <div class="w-full flex flex-col gap-8">

        <div class="w-full h-[312px] pt-40 pb-24">
            <a href="{{ asset('img/ayala-foundation-bg.jpg') }}" class="glightbox flex items-center justify-between w-full gap-4 bg-cover bg-center" data-gallery="gallery1">
                <div class="flex items-center justify-center overflow-hidden w-full" style="height: 300px;">
                    <img class="object-cover w-full" src="{{ asset('img/ayala-foundation-bg.jpg') }}">
                </div>
            </a>

            <div class="w-full grid grid-cols-4" style="margin-top: -80px; height: 300px">
                <div class="col-span-1">
                    <div class="flex flex-col items-center justify-center sm:justify-center gap-4">
                        <div class="w-[250px] h-[250px] flex items-center justify-center overflow-hidden rounded-full relative">
                            <img src="https://pagedone.io/asset/uploads/1705471668.png" alt="user-avatar-image" class="h-full w-full object-cover border-4 border-solid border-white rounded-full object-cover">
                        </div>

                        <div class="block">
                            <h3 class="font-manrope font-bold text-4xl text-gray-900 mb-1 text-center">Emma Smith</h3>
                            <p class="font-normal text-base text-gray-900 text-center">Username</p>
                            <p class="font-normal text-base text-gray-900 text-center">Email</p>
                            <p class="font-normal text-base text-gray-900 text-center">Birthday</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-3">
                    <div class="h-full flex flex-col items-end justify-end sm:justify-center gap-4">
                        <div class="w-full grid grid-cols-5 gap-4" style="padding-bottom: 20px;">
                            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8 shadow-lg">
                                {{-- <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalUpcoming }}</p> --}}
                                <p class="text-[40px] font-[700] text-[#F55E1D]">90</p>
                                <p class="text-[14px] font-[700] text-[#03498D]">UPCOMING</p>
                            </div>

                            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8 shadow-lg">
                                {{-- <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalCertificates }}</p> --}}
                                <p class="text-[40px] font-[700] text-[#F55E1D]">25</p>
                                <p class="text-[14px] font-[700] text-[#03498D]">CERTIFICATES</p>
                            </div>

                            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8 shadow-lg">
                                {{-- <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalApprovedHrs }}</p> --}}
                                <p class="text-[40px] font-[700] text-[#F55E1D]">854</p>
                                <p class="text-[14px] font-[700] text-[#03498D]">APPROVED HRS</p>
                            </div>

                            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8 shadow-lg">
                                {{-- <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalRemainingHrs }}</p> --}}
                                <p class="text-[40px] font-[700] text-[#F55E1D]">80</p>
                                <p class="text-[14px] font-[700] text-[#03498D]">REMAINING HRS</p>
                            </div>

                            <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8 shadow-lg">
                                {{-- <p class="text-[40px] font-[700] text-[#F55E1D]">{{ $totalCancelled }}</p> --}}
                                <p class="text-[40px] font-[700] text-[#F55E1D]">11</p>
                                <p class="text-[14px] font-[700] text-[#03498D]">CANCELLED</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="flex items-center justify-center flex-col sm:flex-row max-sm:gap-5 sm:justify-between mb-5">
                </div> --}}
            </div>
        </div>

        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>


        <div class="w-full grid grid-cols-4 gap-4 pr-10 pl-10">
            <div class="col-span-1 space-y-4">
                <div>
                    <h2 class="font-3xl font-semibold"> Personal Information </h2>
                </div>
                <div>
                    <p>Email: </p>
                    <p>Birthday: </p>
                </div>

                <div>
                    <p>Scholl Name: </p>
                    <p>Address: </p>
                </div>

                <div>
                    <h2>Contact in case of emergency</h2>
                    <p>Contact Name: </p>
                    <p>Relationship: </p>
                    <p>Contact Number: </p>

                </div>
            </div>

            <div class="col-span-3">
                {{-- @livewire(\App\Filament\Widgets\AFIAdminOpportunitiesWidget::class) --}}
                <div class="w-full">
                    <div class="w-full flex items-center justify-between gap-4">
                        <div class="w-fit">
                            <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Opportunities</p>
                        </div>

                        <div class="w-fit flex gap-4">
                            <div
                                class="flex items-center justify-center">
                                <p class="font-base text-[18px] text-orange underline">All</p>
                            </div>
                            <div
                                class="flex items-center justify-center">
                                <p class="font-base text-[18px] text-orange underline">Upcoming</p>
                            </div>
                            <div
                                class="flex items-center justify-center">
                                <p class="font-base text-[18px] text-orange underline">Favorite</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-full border-t border-[#E1E1E1] my-4"></div>

                    <div class="w-full flex flex-col items-center justify-between gap-4">
                        {{-- List --}}
                            <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                                <div
                                    class="w-fit h-fit md:w-[250px] md:h-[180px] flex items-center justify-center overflow-hidden">
                                    <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                                        alt="">
                                </div>

                                <div class="w-full">
                                    <div class="flex items-center justify-start gap-4 text-sm font-[400]">
                                        <p>Created by: <span class="font-[700]">AFI</span></p>

                                        <div class="flex items-center justify-start gap-2">
                                            @if(rand(0, 1))
                                                @include('custom.icons.admin-icons', ['icon' => 'published'])
                                                <p class="font-[700] text-black">PUBLISHED</p>
                                            @else
                                                @include('custom.icons.admin-icons', ['icon' => 'for-review'])
                                                <p class="font-[700] text-[#F55E1D]">FOR REVIEW</p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-[28px] font-[400] text-[#03498D]"></p>

                                    <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online,
                                    </p>

                                    <div
                                        class="w-full h-fit flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                                        <div class="w-fit flex flex-col items-start justify-between gap-0">
                                            <p class="font-[600]">DATE:</p>
                                            <p class="font-[600]"></p>

                                            <p><span class="font-[600]">SHIFTS:</span> Listen attentively and ...</p>
                                            <p>
                                                <span class="font-[600]">BATCH:</span>
                                            </p>
                                        </div>

                                        <div class="h-full min-h-[118px] max-h-[118px] border-l border-[#B6B6B6] mx-4"></div>

                                        <div class="w-fit grid grid-cols-2 gap-8">
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
                    </div>

                    <div class="w-full border-t border-[#E1E1E1] mt-4"></div>
                </div>
            </div>
        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<script>
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
    });
</script>
</x-filament-panels::page>
