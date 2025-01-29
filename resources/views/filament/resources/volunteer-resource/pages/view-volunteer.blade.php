<x-filament-panels::page>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
        <!-- Include Swiper CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    </head>

    <style>
        /* For Profile Page Container(Start) */
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

        .fi-header {
            display: none;
        }

        /* For Profile Page Container(End) */
    </style>


    <div class="w-full flex flex-col items-center justify-center gap-8">
        {{-- <a href="{{ asset('img/ayala-foundation-bg.jpg') }}"
            class="glightbox flex items-center justify-between w-full gap-4 bg-cover bg-center" data-gallery="gallery1">
            <div class="flex items-center justify-center overflow-hidden w-full" style="height: 300px;">
                <img class="object-cover w-full" src="{{ asset('img/ayala-foundation-bg.jpg') }}">
            </div>
        </a> --}}

        <div class="w-full h-full flex flex-col items-end justify-end sm:justify-center gap-4 p-4">
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

        {{-- Profile Badge --}}
        <div class="w-full px-8">
            <div class="w-full flex flex-col lg:flex-row items-center justify-between gap-8">
                <div
                    class="w-full min-w-[400px] max-w-[400px] flex flex-row items-center justify-center lg:justify-start text-start gap-4">
                    <div
                        class="w-[120px] h-[120px] flex items-center justify-center overflow-hidden rounded-full relative">
                        <img class="h-full w-full object-cover"
                            src="{{ \Filament\Facades\Filament::getUserAvatarUrl($user) }}"
                            alt="User Profile Image">
                    </div>
     
           
                    <div>
                        <p class="text-[20px] font-[500]">{{ $user->name }}</p>
                        <p class="text-[14px] font-[300]">Member Since:
                            {{ $user->created_at->format('F j, Y') }}</p>
                        <p class="text-[18px] font-bold text-[#F55E1D]">LEVEL: 1</p>
                    </div>
                </div>

                {{-- Badges Container --}}
                <div class="swiper-container w-full max-w-[700px] overflow-hidden">
                    <div class="swiper-wrapper justify-end">
                            <!-- Badge 1 -->
                            <div class="swiper-slide flex items-center text-center w-auto">
                                @if ($badges['current_rank'] !== null)

                                <div>Your are now a <b>{{$badges['current_rank']['name']}} Member!</b></div>
                                <div class="w-[120px] h-[120px] flex  justify-self-center overflow-hidden relative">
                                    <img class="h-full w-full object-cover"
                                        src="{{$badges['current_rank']['medal']}}"
                                        alt="Badge 1">
                                </div>
                                @endif
                                @if ($badges['next_rank'] !== null)
                                    <span class="justify-self-center">{{$badges['points'] }}/{{$badges['next_rank']['pts_required']}}</span><br> 
                                    <span>Next Tier: <b>{{$badges['next_rank']['name']}}</b></span>
                                @endif
                            </div>
                    </div>
                </div>

                <!-- Swiper JS Initialization -->
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const swiper = new Swiper('.swiper-container', {
                            slidesPerView: 'auto',
                            spaceBetween: 16,
                            grabCursor: true,
                            freeMode: true,
                            centeredSlides: false,
                            breakpoints: {
                                640: {
                                    slidesPerView: 3,
                                    spaceBetween: 20,
                                },
                                // For screens >= 1024px
                                1024: {
                                    slidesPerView: 4,
                                    spaceBetween: 24,
                                },
                            },
                        });
                    });
                </script>

                {{-- Swiper JS Style --}}
                <style>
                    /* Ensure Swiper slides are sized correctly */
                    .swiper-slide {
                        width: auto !important;
                        /* Make each slide match its content width */
                    }
                </style>
            </div>

            <div class="w-full border-t border-[#E1E1E1] mt-8"></div>
        </div>

        {{-- Profile Details Tabs --}}
        <div class="w-full px-8 flex flex-col lg:flex-row items-start justify-center gap-8">
            {{-- Tab Buttons --}}
            <div class="w-full lg:max-w-[250px] flex flex-row lg:flex-col items-center justify-center text-white gap-4">
                <button id="personal-info-btn" onclick="changeTab('personal-info')"
                    class="w-full p-4 text-start hover:!bg-[#1A67B1]" style="background: #005096;">
                    Personal Information
                </button>

                <button id="badges-achievements-btn" onclick="changeTab('badges-achievements')"
                    class="w-full p-4 text-start hover:!bg-[#1A67B1] hidden" style="background: #9E9E9E;">
                    Badges / Achievements
                </button>

                <button id="events-btn" onclick="changeTab('events')" class="w-full p-4 text-start hover:!bg-[#1A67B1]"
                    style="background: #9E9E9E;">
                    Events
                </button>
            </div>

            {{-- Tab Contents --}}
            <div class="w-full flex flex-col items-center justify-center">
                <div id="personal-info-content" class="w-full flex flex-col items-center justify-center gap-8">
                    {{-- Personal Information --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-3xl mb-4">Personal Infomation</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">First Name</p>
                                    <p class="text-xl">{{ $user->firstname }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Middle Name</p>
                                    <p class="text-xl">
                                        {{ $user->middle_name ? $user->middle_name : 'N/A' }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Last Name</p>
                                    <p class="text-xl">{{ $user->lastname }}</p>
                                </div>
                            </div>

                            <div class="w-full border-t border-[#E1E1E1] my-4"></div>

                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Username</p>
                                    <p class="text-xl">{{ $user->username }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Email</p>
                                    <p class="text-xl">{{ $user->email }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Birthday</p>
                                    <p class="text-xl">
                                        {{ $user->birthday ? $user->birthday : 'N/A' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Company / School --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-3xl mb-4">Company / School</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Company Name</p>
                                    <p class="text-xl">
                                        {{ $user->company_name ? $user->company_name : 'N/A' }}</p>
                                </div>

                                

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Address</p>
                                    <p class="text-xl">
                                        {{ $user->company_address ? $user->company_address : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- In Case of Emergency --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-3xl mb-4">In Case of Emergency</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Contact Person</p>
                                    <p class="text-xl">
                                        {{ $user->emergency_contact_name ? $user->emergency_contact_name : 'N/A' }}
                                    </p>
                                </div>


                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Contact Number</p>
                                    <p class="text-xl">
                                        {{ $user->emergency_contact_number ? $user->emergency_contact_number : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Interest --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-3xl mb-4">Program</p>
                        @php
                            if($user->program_id != null){
                                $program_name = DB::table('programs')->where('id', $user->program_id)->first()->name;
                            }else{
                                $program_name = 'N/A';
                            }
                           
                        @endphp

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-xl">{{ $program_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- @dd(auth()->user()) --}}

                </div>

                <div id="badges-achievements-content" class="w-full flex items-center justify-center gap-8 hidden">
                    {{-- Badges / Achievements --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-3xl mb-4">Badges / Achievements</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                                {{-- Badge 1 --}}
                                <div
                                    class="col-span-1 flex items-center justify-center gap-1 {{ true ? '' : 'opacity-50' }}">
                                    <div
                                        class="w-[120px] h-[120px] flex items-center justify-center overflow-hidden relative">
                                        <img class="h-full w-full object-cover"
                                            src="{{ \Filament\Facades\Filament::getUserAvatarUrl(auth()->user()) }}"
                                            alt="Badge 1">
                                    </div>
                                </div>


                                {{-- Badge 2 --}}
                                <div
                                    class="col-span-1 flex items-center justify-center gap-1 {{ false ? '' : 'opacity-50' }}">
                                    <div
                                        class="w-[120px] h-[120px] flex items-center justify-center overflow-hidden relative">
                                        <img class="h-full w-full object-cover"
                                            src="{{ \Filament\Facades\Filament::getUserAvatarUrl(auth()->user()) }}"
                                            alt="Badge 1">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                </div>

                <div id="events-content" class="w-full flex items-center justify-center gap-8 hidden">
                    {{-- Events --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-3xl mb-4">Events</p>

                        {{-- Tab Buttons --}}
                        <div class="w-full flex items-center justify-start text-white gap-4 mb-4">
                            <button id="all-events-btn" onclick="changeEventsTab('all-events')"
                                class="py-2 px-4 text-start bg-[#005096] hover:bg-[#1A67B1]">
                                ALL EVENTS
                            </button>

                            <button id="favorite-events-btn" onclick="changeEventsTab('favorite-events')"
                                class="py-2 px-4 text-start bg-[#F55E1D] hover:bg-[#FF9141]">
                                FAVORITE EVENTS
                            </button>
                        </div>

                        <div class="shadow-md p-8">
                            {{-- All Events --}}
                            <div id="all-events-content" class="w-full flex flex-col items-center justify-center">
                                {{-- List --}}
                                @foreach ($allEvents as $index => $opportunity)
                                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                                        <div
                                            class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                                            <img class="w-full h-full object-cover"
                                                src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                                        </div>

                                        <div class="w-full">
                                            <p class="text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}
                                            </p>

                                            <p class="text-[18px] font-[400]">{{ $opportunity->location }}</p>
                                            <p class="font-[600]">DATE:
                                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                            </p>
                                        </div>

                                        <div class="w-[200px]">
                                            <a href="">
                                                <div
                                                    class="h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                                    <p class="font-[400] text-[18px] text-white">Download QR</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    @if (!$loop->last)
                                        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Favorite Events --}}
                            <div id="favorite-events-content"
                                class="w-full flex flex-col items-center justify-center hidden">
                                {{-- List --}}
                                @foreach ($favoriteEvents as $index => $opportunity)
                                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                                        <div
                                            class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                                            <img class="w-full h-full object-cover"
                                                src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                                        </div>

                                        <div class="w-full">
                                            <p class="text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}
                                            </p>

                                            <p class="text-[18px] font-[400]">{{ $opportunity->location }}</p>
                                            <p class="font-[600]">DATE:
                                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                            </p>
                                        </div>

                                        <div class="w-[200px]">
                                            <a href="path/to/your-image.jpg" download="QR-Code.jpg">
                                                <div
                                                    class="h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                                    <p class="font-[400] text-[18px] text-white">Download QR</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    @if (!$loop->last)
                                        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Tab Scripts --}}
                        <script>
                            // Get button and content elements
                            const allEventsBtn = document.getElementById('all-events-btn');
                            const favoriteEventsBtn = document.getElementById('favorite-events-btn');

                            const allEventsContent = document.getElementById('all-events-content');
                            const favoriteEventsContent = document.getElementById('favorite-events-content');

                            // Change Tab function
                            function changeEventsTab(tabType) {
                                // Hide all content sections
                                allEventsContent.classList.add('hidden');
                                favoriteEventsContent.classList.add('hidden');

                                // Set the clicked button to the active color and show the corresponding content
                                if (tabType === 'all-events') {
                                    allEventsContent.classList.remove('hidden');
                                } else if (tabType === 'favorite-events') {
                                    favoriteEventsContent.classList.remove('hidden');
                                }
                            }
                        </script>

                    </div>
                </div>
            </div>

            {{-- Tab Scripts --}}
            <script>
                // Get button and content elements
                const personalInfoBtn = document.getElementById('personal-info-btn');
                const badgesAchievementsBtn = document.getElementById('badges-achievements-btn');
                const eventsBtn = document.getElementById('events-btn');

                const personalInfoContent = document.getElementById('personal-info-content');
                const badgesAchievementsContent = document.getElementById('badges-achievements-content');
                const eventsContent = document.getElementById('events-content');

                // Change Tab function
                function changeTab(tabType) {
                    // Reset all buttons to the inactive color
                    personalInfoBtn.style.background = '#9E9E9E';
                    badgesAchievementsBtn.style.background = '#9E9E9E';
                    eventsBtn.style.background = '#9E9E9E';

                    // Hide all content sections
                    personalInfoContent.classList.add('hidden');
                    badgesAchievementsContent.classList.add('hidden');
                    eventsContent.classList.add('hidden');

                    // Set the clicked button to the active color and show the corresponding content
                    if (tabType === 'personal-info') {
                        personalInfoBtn.style.background = '#005096';
                        personalInfoContent.classList.remove('hidden');
                    } else if (tabType === 'badges-achievements') {
                        badgesAchievementsBtn.style.background = '#005096';
                        badgesAchievementsContent.classList.remove('hidden');
                    } else if (tabType === 'events') {
                        eventsBtn.style.background = '#005096';
                        eventsContent.classList.remove('hidden');
                    }
                }
            </script>
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
