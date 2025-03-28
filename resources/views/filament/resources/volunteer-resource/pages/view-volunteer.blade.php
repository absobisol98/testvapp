<x-filament-panels::page>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
        <!-- Include Swiper CSS and JS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        .badge-overlay {
            background-color: rgba(31, 41, 55, 0.7); /* This is equivalent to bg-gray-800 with 70% opacity */
        }

        .badge-overlay:hover {
            background-color: rgba(31, 41, 55, 0.5); /* Optional: lighter on hover */
            transition: background-color 0.3s ease;
        }
    </style>

    <div class="flex justify-end mb-8 pt-8 mr-8">
        @foreach($this->getHeaderActions() as $action)
            {{ $action }}
        @endforeach
    </div>

    <div class="w-full flex flex-col items-center justify-center gap-8">
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
                        <p class="text-[20px] font-[500] capitalize">{{ $user->name }}</p>
                        <p><span class="text-[14px] font-[300] font-bold">Member Since:</span>
                            {{ $user->created_at->format('F j, Y') }}</p>

                    </div>
                </div>

                {{-- Badges Container --}}
                <div class="swiper-container w-full max-w-[700px] overflow-hidden">
                    <div class="swiper-wrapper justify-center md:justify-end">
                            <!-- Badge 1 -->
                            <div class="swiper-slide flex items-center text-center w-auto">
                                @if ($badges['current_rank'] !== null)

                                <div>You are now a <b>{{$badges['current_rank']['name']}}!</b></div>
                                <div class="w-[120px] h-[120px] flex  justify-self-center overflow-hidden relative">
                                    <img class="h-full w-full object-cover"
                                        src="{{$badges['current_rank']['medal']}}"
                                        alt="Badge 1">

                                </div>
                                @endif
                                @if ($badges['next_rank'] !== null)
                                    <span class="justify-self-center">{{$badges['points'] }}/{{$badges['next_rank']['required']}}</span><br>
                                    <span>Next Badge: <b>{{$badges['next_rank']['name']}}</b></span>
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
            <div class="w-full lg:max-w-[250px] flex flex-col items-center justify-center text-white gap-4">
                <button id="events-btn" onclick="changeTab('events')" class="w-full p-4 text-start hover:!bg-[#1A67B1]"
                style="background: #005096;">
                My Volunteer Opportunities
            </button>

            <button id="personal-info-btn" onclick="changeTab('personal-info')"
                class="w-full p-4 text-start hover:!bg-[#1A67B1]" style="background: #9E9E9E;">
                Personal Information
            </button>

                <button id="badges-achievements-btn" onclick="changeTab('badges-achievements')"
                    class="w-full p-4 text-start hover:!bg-[#1A67B1]" style="background: #9E9E9E;">
                    Badges / Achievements
                </button>
            </div>

            {{-- Tab Contents --}}
            <div class="w-full flex flex-col items-center justify-center">
                <div id="personal-info-content" class="w-full flex flex-col items-center justify-center gap-8 hidden">
                    {{-- Personal Information --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-2xl md:text-3xl mb-4">Personal Infomation</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">First Name</p>
                                    <p class="text-lg md:text-xl capitalize">{{ $user->firstname }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Nickname</p>
                                    <p class="text-lg md:text-xl capitalize">
                                        {{ $user->nickname ? $user->nickname : 'N/A' }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Last Name</p>
                                    <p class="text-lg md:text-xl capitalize">{{ $user->lastname }}</p>
                                </div>
                            </div>

                            <div class="w-full border-t border-[#E1E1E1] my-4"></div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Email</p>
                                    <p class="text-lg md:text-xl">{{ $user->email }}</p>
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    {{-- <p class="text-sm">Username</p>
                                    <p class="text-lg md:text-xl">{{ $user->username }}</p> --}}
                                </div>

                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Age Range</p>
                                    <p class="text-lg md:text-xl">
                                        {{ $user->age_range ? $user->age_range : 'N/A' }}</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Company / School --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-2xl md:text-3xl mb-4">Company</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Company Name</p>
                                    <p class="text-lg md:text-xl">
                                        {{ $user->company_name ? $user->company_name : 'N/A' }}</p>
                                </div>



                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Address</p>
                                    <p class="text-lg md:text-xl">
                                        {{ $user->company_address ? $user->company_address : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- In Case of Emergency --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-2xl md:text-3xl mb-4">In Case of Emergency</p>

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Contact Person</p>
                                    <p class="text-lg md:text-xl capitalize">
                                        {{ $user->emergency_contact_name ? $user->emergency_contact_name : 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Relationship</p>
                                    <p class="text-lg md:text-xl capitalize">
                                        {{ $user->emergency_contact_relationship ? $user->emergency_contact_relationship : 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-sm">Contact Number</p>
                                    <p class="text-lg md:text-xl">
                                        {{ $user->emergency_contact_number ? $user->emergency_contact_number : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Interest --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-2xl md:text-3xl mb-4">Program</p>

                        @php


                            if($user->program_id != null){
                                $programs = DB::table('program_volunteer')->where('volunteer_id', $user->id)->get();

                            }else{
                                $programs = 'N/A';
                            }
                        @endphp

                        <div class="shadow-md p-8">
                            <div class="grid grid-cols-3 gap-4">
                                <div class="col-span-1 flex flex-col items-start justify-center gap-1">
                                    <p class="text-lg md:text-xl capitalize">
                                        @foreach($programs as $program)

                                            @php
                                                $prog_name = \App\Models\Program::where('id', $program->program_id)->first();
                                            @endphp
                                            {{ $prog_name->name }} <br>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- @dd(auth()->user()) --}}

                </div>

                <div id="badges-achievements-content" class="w-full flex items-center justify-center gap-8 hidden">
                    {{-- Badges / Achievements --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-2xl md:text-3xl mb-4">Badges / Achievements</p>

                        <div class="shadow-md p-8 min-h-[50vh] flex flex-col lg:flex-row gap-6">
                            <!-- Challenges Section -->
                            <div class="w-full h-[400px] lg:w-1/3 space-y-4 overflow-y-scroll custom-scrollbar">
                                <!-- Pending Challenges First -->
                                <h2 class="font-bold text-lg">Pending Challenges</h2>
                                <div class="space-y-4 mb-8">
                                    <!-- Registration Challenge -->
                                    @if(!$user->created_at)
                                        <div class="bg-blue-700 text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+50 <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Create an account</p>
                                        </div>
                                    @endif

                                    <!-- First Opportunity -->
                                    @if($user->eventAttended()->count() == 0)
                                        <div class="bg-blue-700 text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+50 <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Register for first opportunity</p>
                                        </div>
                                    @endif

                                    <!-- Next Hour Goal -->
                                    @if($totalHours < 20)
                                        <div class="bg-blue-700 text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+50 <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Complete {{ $nextHourGoal }} hours</p>
                                            <p class="text-xs">{{ $totalHours }}/{{ $nextHourGoal }} hours completed</p>
                                            <div class="w-full h-2 bg-gray-300 rounded-full mt-2">
                                                <div class="h-2 bg-white rounded-full" style="width: {{ min(($totalHours/$nextHourGoal * 100), 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Other pending challenges... -->

                                    <!-- Hour-based challenges -->
                                    @php
                                        $hourMilestones = [100, 250, 500, 1000];
                                        $nextHourMilestone = null;
                                        foreach ($hourMilestones as $milestone) {
                                            if ($totalHours < $milestone) {
                                                $nextHourMilestone = $milestone;
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if($nextHourMilestone)
                                        <div class="bg-blue-700 text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+{{ match($nextHourMilestone) {
                                                100 => '250',
                                                250 => '1500',
                                                500 => '2500',
                                                1000 => '5000'
                                            } }} <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Complete {{ $nextHourMilestone }} volunteer hours</p>
                                            <p class="text-xs">{{ $totalHours }}/{{ $nextHourMilestone }} hours completed</p>
                                            <div class="w-full h-2 bg-gray-300 rounded-full mt-2">
                                                <div class="h-2 bg-white rounded-full" style="width: {{ min(($totalHours/$nextHourMilestone * 100), 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Opportunity-based challenges -->
                                    @php
                                        $opportunityMilestones = [25, 50, 100];
                                        $nextOpportunityMilestone = null;
                                        foreach ($opportunityMilestones as $milestone) {
                                            if ($user->eventAttended()->count() < $milestone) {
                                                $nextOpportunityMilestone = $milestone;
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if($nextOpportunityMilestone)
                                        <div class="bg-blue-700 text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+{{ match($nextOpportunityMilestone) {
                                                25 => '250',
                                                50 => '1500',
                                                100 => '2500'
                                            } }} <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Complete {{ $nextOpportunityMilestone }} volunteer positions</p>
                                            <p class="text-xs">{{ $user->eventAttended()->count() }}/{{ $nextOpportunityMilestone }} positions completed</p>
                                            <div class="w-full h-2 bg-gray-300 rounded-full mt-2">
                                                <div class="h-2 bg-white rounded-full" style="width: {{ min(($user->eventAttended()->count()/$nextOpportunityMilestone * 100), 100) }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Completed Challenges Second -->
                                <!-- Completed Challenges Second -->
                                    <h2 class="font-bold text-lg">Completed Challenges</h2>
                                    <div class="space-y-4">
                                        <!-- Registration Challenge -->
                                        @if($user->created_at)
                                            <div class="bg-[#F55E1D] text-white p-4 rounded-md shadow">
                                                <p class="font-bold text-xl">+50 <span class="text-sm">VP</span></p>
                                                <p class="text-sm">Create an account</p>
                                                <p class="text-xs mt-2">✓ Completed</p>
                                            </div>
                                        @endif

                                        <!-- First Opportunity Challenge -->
                                        @if($user->eventAttended()->count() > 0)
                                            <div class="bg-[#F55E1D] text-white p-4 rounded-md shadow">
                                                <p class="font-bold text-xl">+50 <span class="text-sm">VP</span></p>
                                                <p class="text-sm">Register for first opportunity</p>
                                                <p class="text-xs mt-2">✓ Completed</p>
                                            </div>
                                        @endif

                                        <!-- Hour Milestone Challenges -->
                                       <!-- Hour Milestone Challenges -->
                                @php
                                $completedHourMilestones = [];
                                // Only include the 20-hour milestone if actually completed
                                if ($totalHours >= 20) {
                                    $completedHourMilestones[] = 20;
                                }
                                foreach ([100, 250, 500, 1000] as $milestone) {
                                    if ($totalHours >= $milestone) {
                                        $completedHourMilestones[] = $milestone;
                                    }
                                }
                                @endphp

                                        @foreach($completedHourMilestones ?? [] as $milestone)
                                        <div class="bg-[#F55E1D] text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+{{ match($milestone) {
                                                20 => '50',
                                                100 => '250',
                                                250 => '1500',
                                                500 => '2500',
                                                1000 => '5000',
                                                default => '50'
                                            } }} <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Complete {{ $milestone }} volunteer hours</p>
                                            <p class="text-xs mt-2">✓ Completed</p>
                                        </div>
                                        @endforeach

                                        <!-- Opportunity Milestone Challenges -->
                                        @foreach($completedOpportunityMilestones ?? [] as $milestone)
                                        <div class="bg-[#F55E1D] text-white p-4 rounded-md shadow">
                                            <p class="font-bold text-xl">+{{ match($milestone) {
                                                5 => '50',
                                                10 => '100',
                                                25 => '250',
                                                50 => '1500',
                                                100 => '2500',
                                                default => '50'
                                            } }} <span class="text-sm">VP</span></p>
                                            <p class="text-sm">Complete {{ $milestone }} volunteer positions</p>
                                            <p class="text-xs mt-2">✓ Completed</p>
                                        </div>
                                        @endforeach

                                        @foreach($completedOpportunityMilestones as $milestone)
                                            <div class="bg-[#F55E1D] text-white p-4 rounded-md shadow">
                                                <p class="font-bold text-xl">+{{ match($milestone) {
                                                    5 => '50',
                                                    10 => '100',
                                                    25 => '250',
                                                    50 => '1500',
                                                    100 => '2500',
                                                    default => '50'
                                                } }} <span class="text-sm">VP</span></p>
                                                <p class="text-sm">Complete {{ $milestone }} volunteer positions</p>
                                                <p class="text-xs mt-2">✓ Completed</p>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>

                            <!-- Progress Section -->
                            <div class="w-full lg:w-2/3 space-y-4">
                                @php
                                    $badges = $user->getBadges();
                                    $currentRank = $badges['current_rank'];

                                    $nextRank = $badges['next_rank'];
                                @endphp

                                <h2 class="font-bold text-lg">Next Badge: <span class="text-orange-600">{{ $nextRank['name'] }}</span></h2>
                                <p class="text-sm font-bold">{{ $badges['points'] }}/{{ $nextRank['required'] }}</p>
                                <div class="w-full h-2 bg-gray-300 rounded-full">
                                    <div class="h-2 bg-orange-600" style="width: {{ min((($badges['points'] - $currentRank['required']) / ($nextRank['required'] - $currentRank['required'])) * 100, 100) }}%"></div>
                                </div>

                                <!-- Progress List -->
                                <h2 class="font-bold text-lg mt-4">Progress</h2>
                                <div class="space-y-3">
                                    @foreach(['No Rank', 'Bronze', 'Silver', 'Gold', 'Platinum'] as $rank)
                                        <div class="flex items-center justify-between border-b pb-2 {{ $currentRank['name'] !== $rank ? 'opacity-50' : '' }}">
                                            <div class="flex items-center gap-2">
                                                <img src="{{ asset('medals/'.strtolower(str_replace(' ', '-', $rank)).'.png') }}" alt="{{ $rank }}" class="w-10 h-10">
                                                <span class="font-bold text-sm {{ $currentRank['name'] === $rank ? 'text-orange-600' : 'text-gray-500' }}">{{ $rank }}</span>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $rank === 'No Rank' ? '1250' : ($rank === 'Bronze' ? '2500' : ($rank === 'Silver' ? '5000' : ($rank === 'Gold' ? '10000' : '10000+'))) }}VP</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="events-content" class="w-full flex items-center justify-center gap-8">
                    {{-- Events --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-2xl md:text-3xl mb-4">Events</p>

                        {{-- Tab Buttons --}}
                        <div class="w-full flex items-center justify-start text-white gap-4 mb-4">
                            <button id="all-events-btn" onclick="changeEventsTab('all-events')"
                                class="py-2 px-4 text-base md:text-lg text-start bg-[#005096] hover:bg-[#1A67B1]">
                                All Joined Opportunities
                            </button>

                            {{-- <button id="favorite-events-btn" onclick="changeEventsTab('favorite-events')"
                                class="py-2 px-4 text-base md:text-lg text-start bg-[#F55E1D] hover:bg-[#FF9141]">
                                My Favorite Opportunities
                            </button> --}}
                        </div>

                        <div class="shadow-md p-8">
                            {{-- All Events --}}
                            <div id="all-events-content" class="w-full flex flex-col items-center justify-center">
                                {{-- List --}}
                                @if ($allEvents->isEmpty())
                                    <div class="w-full flex items-center justify-center py-8">
                                        <p class="text-[18px] text-gray-500">No opportunities available at the moment.</p>
                                    </div>
                                @else
                                    @foreach ($favoriteEvents as $index => $opportunity)
                                        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                                            <div class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                                                <img class="w-full h-full object-cover"
                                                    src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                                            </div>

                                            <div class="w-full">
                                                <p class="text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}</p>
                                                <p class="text-[18px] font-[400]">{{ $opportunity->location }}</p>
                                                <p class="font-[600]">DATE:
                                                    {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                                </p>
                                            </div>
                                            @php
                                                $att_details = $opportunity->attendees->where('attendee_id', $user->id)->first();
                                            @endphp
                                            @if ($att_details)
                                                <div class="w-[200px]">
                                                    <a href="{{ secure_asset(Storage::url($att_details->id . '-qr-code.png')) }}"
                                                        onclick="event.preventDefault(); forceDownload(this)"
                                                        data-filename="qr-code.png"
                                                        class="cursor-pointer">
                                                         <div class="h-auto md:h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                                             <p class="font-medium text-base md:text-[18px] text-white">Download QR</p>
                                                         </div>
                                                     </a>
                                                </div>

                                                @php
                                                    $isEventFinished = \Carbon\Carbon::parse($opportunity->end_date)->isPast();

                                                @endphp

                                                @if ($isEventFinished)
                                                    <div class="w-[200px]">
                                                        <a href="{{ route('volunteer.certificate', ['attendee_id' => $user->id, 'event_id' => $opportunity->id ]) }}"
                                                           class="cursor-pointer">
                                                            <div class="h-auto md:h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                                                <p class="font-medium text-base md:text-[18px] text-white">Download Certificate</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        @if ($att_details)
                                            <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8 mt-4">
                                                <div class="w-full">
                                                    <p class="text-[20px] font-[400] text-[#03498D]">Registered Slots:</p>
                                                    @foreach ($opportunity->slots as $slot)
                                                        @php
                                                            // Find attendee record for this specific slot
                                                            $slotAttendee = $opportunity->attendees()
                                                                ->where('attendee_id', $user->id)
                                                                ->where('slot_type_id', $slot->id)
                                                                ->first();
                                                        @endphp
                                                        @if ($slotAttendee)
                                                            <div class="text-[18px] font-[400] p-4 bg-gray-50 rounded-lg mb-2">
                                                                <div class="flex justify-between items-start">
                                                                    <div>
                                                                        <p class="font-semibold text-[#03498D]">{{ $slot->shift_name }}</p>
                                                                        <p class="text-sm text-gray-600">
                                                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('M-d-Y h:i A') }} -
                                                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('M-d-Y h:i A') }}
                                                                        </p>
                                                                        @if($slotAttendee->time_in && $slotAttendee->time_out)
                                                                            <p class="text-sm text-green-600 mt-2">
                                                                                @php
                                                                                    $timeIn = \Carbon\Carbon::parse($slotAttendee->time_in);
                                                                                    $timeOut = \Carbon\Carbon::parse($slotAttendee->time_out);
                                                                                    $diff = $timeOut->diff($timeIn);
                                                                                    $hours = $diff->h + ($diff->days * 24);
                                                                                    $minutes = $diff->i;
                                                                                @endphp
                                                                                <span class="font-medium">Hours Completed:</span>
                                                                                {{ $hours }}H {{ $minutes }}M
                                                                            </p>
                                                                        @else
                                                                            <p class="text-sm text-orange-600 mt-2">
                                                                                <span class="font-medium">Status:</span>
                                                                                Pending Attendance
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                    @if($slotAttendee->is_approve)
                                                                        <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded">
                                                                            Approved
                                                                        </span>
                                                                    @elseif($slotAttendee->is_rejected)
                                                                        <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded">
                                                                            Rejected
                                                                        </span>
                                                                    @else
                                                                        <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded">
                                                                            Pending
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if (!$loop->last)
                                            <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>

                            {{-- Favorite Events --}}
                             <div id="favorite-events-content"
                                class="w-full flex flex-col items-center justify-center hidden">
                                {{-- List
                                @if ($allEvents->isEmpty())
                                    <div class="w-full flex items-center justify-center py-8">
                                        <p class="text-[18px] text-gray-500">No favorite opportunities.</p>
                                    </div>
                                @else
                                    @foreach ($allEvents as $index => $opportunity)
                                        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                                            <div class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                                                <img class="w-full h-full object-cover"
                                                    src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                                            </div>

                                            <div class="w-full">
                                                <p class="text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}</p>
                                                <p class="text-[18px] font-[400]">{{ $opportunity->location }}</p>
                                                <p class="font-[600]">DATE:
                                                    {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}
                                                </p>
                                            </div>

                                            <div class="w-[200px]">
                                                <a href="">
                                                    <div class="h-auto md:h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                                        <p class="font-[400] text-base md:text-[18px] text-white">Download QR</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        @if (!$loop->last)
                                            <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                                        @endif
                                    @endforeach
                                @endif

                            </div> --}}
                        </div>

                        {{-- Tab Scripts --}}
                        <script>
                            // Get button and content elementss
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
                function forceDownload(link) {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", link.href, true);
                    xhr.responseType = "blob";

                    xhr.onload = function() {
                        var blob = xhr.response;
                        var a = document.createElement('a');
                        a.href = window.URL.createObjectURL(blob);
                        a.download = link.getAttribute('data-filename');
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        window.URL.revokeObjectURL(a.href);
                    };

                    xhr.send();
                }
                </script>
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
