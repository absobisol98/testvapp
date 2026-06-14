<x-filament-panels::page>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <meta property="og:title" content="{{$record->title}}" />
    <meta property="og:description" content="Get from SEO newbie to SEO pro in 8 simple steps." />
    <meta property="og:image" content="{{$record->getBanner()}}" />
    <meta property="og:image" content="{{$record->getAttachment()}}" />
</head>
<style>
    /* For Volunteer Dashboard Container(Start) */
    .fi-main {
        margin: 0px !important;
        padding: 0px 0px !important;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        padding-top: 20px !important;
        padding-bottom: 0px !important;
        border-radius: 0px !important;
        max-width: 100% !important;
    }

    .fi-page section {
        padding: 0px 0px 32px 0px !important;
    }
    .fi-header {
        padding-left: 20px !important;
    }
    .fi-header-heading {
        display: none;
    }

    /* For Volunteer Dashboard Container(End) */
</style>
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

@php
    // Add this at the top of your file with other PHP calculations
    $user = auth()->user();
    $isMinor = $user->birthday && Carbon\Carbon::parse($user->birthday)->age < 18;
    $requiresAttachment = $isMinor || ($record->attachment_required ?? false);
    $attachmentDescription = $isMinor
        ? 'Please upload parental consent document (required for minors)'
        : ($record->attachment_required ? 'Please upload required documents for this event' : '');
    $isEventFinished = now()->isAfter($record->end_date);
    $attendeeHours = $record->attendees()
        ->where('attendee_id', auth()->id())
        ->whereNotNull('time_in')
        ->whereNotNull('time_out')
        ->get()
        ->groupBy('slot_type_id')
        ->mapWithKeys(function($attendances) {
            $slotId = $attendances->first()->slot_type_id;
            $totalMinutes = $attendances->sum(function($attendance) {
                $timeIn = Carbon\Carbon::parse($attendance->time_in);
                $timeOut = Carbon\Carbon::parse($attendance->time_out);

                // Get time difference using diff()
                $diff = $timeOut->diff($timeIn);
                $hours = $diff->h + ($diff->days * 24);
                $minutes = $diff->i;

                return ($hours * 60) + $minutes;
            });

            // Convert minutes to hours and minutes for display
            $hours = ($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            return [$slotId => [
                'hours' => $hours,
                'minutes' => $minutes
            ]];
        });


@endphp

{{-- @dd($record->getAttachment()) --}}

<div class="flex flex-col w-full px-4 mx-auto md:px-6 lg:px-8 max-w-full space-y-6">

    <div class="w-full flex items-center justify-between">
        <h2 class="text-3xl md:text-3xl lg:text-3xl text-[#FF781E]] font-extrabold capitalize">{{ $record->title }}</h2>
        <div class="grid grid-cols-3 gap-2">
        @php
            $user = auth()->user();
            $isSuperAdmin = $user->hasRole('super_admin');
            $isAdmin = $user->hasRole('admin');
            $isCreator = $record->created_by == $user->id;
            $isFacilitator = $record->facilitators->contains($user->id);
            $canManageEvent = $isSuperAdmin || $isAdmin || $isCreator || $isFacilitator;
        @endphp

        @if($canManageEvent)
            <a href="{{ route('filament.admin.resources.events.manage-volunteers', ['record' => $record->id]) }}"
            class="py-2 px-2 flex items-center justify-center rounded-md bg-[#F55E1D] hover:bg-[#FF9141]">
                <!-- Desktop Text -->
                <p class="text-base font-normal text-white hidden md:block">Manage Volunteers</p>
                <!-- Mobile/Tablet Icon -->
                <svg class="w-6 h-6 text-white md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </a>

            <a href="{{route('filament.admin.resources.events.edit',['record' => $record->id])}}"
            class="py-2 px-2 flex items-center justify-center rounded-md bg-[#F55E1D] hover:bg-[#FF9141]">
                <!-- Desktop Text -->
                <p class="text-base font-normal text-white hidden md:block">Edit</p>
                <!-- Mobile/Tablet Icon -->
                <svg class="w-6 h-6 text-white md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </a>
        @else
            <a></a>
            <a></a>
        @endif

        <a href="{{route('filament.admin.resources.events.index')}}"
        class="py-2 px-2 flex items-center justify-center rounded-md bg-[#F55E1D] hover:bg-[#FF9141]">
            <!-- Desktop Text -->
            <p class="text-base font-normal text-white hidden md:block">Opportunity List</p>
            <!-- Mobile/Tablet Icon -->
            <svg class="w-6 h-6 text-white md:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
    </div>
</div>

    <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="w-full col-span-2 space-y-4">
            <div class="flex items-center justify-center rounded-md w-full">
                <img src="{{ $record->getBanner()}}" alt="">
            </div>
            <div>
                <p class="text-black md:pl-10 lg:pl-0 text-lg text-start font-extrabold">Supported Program</p>
                <div class="flex items-start justify-start gap-2">
                    <div class="inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#03498D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-tags"><path d="m15 5 6.3 6.3a2.4 2.4 0 0 1 0 3.4L17 19"/><path d="M9.586 5.586A2 2 0 0 0 8.172 5H3a1 1 0 0 0-1 1v5.172a2 2 0 0 0 .586 1.414L8.29 18.29a2.426 2.426 0 0 0 3.42 0l3.58-3.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="6.5" cy="9.5" r=".5" fill="currentColor"/></svg>
                    </div>
                    <p class="text-md md:text-lg lg:text-base font-normal text-[#03498D] capitalize">{{$record->program->name}}</p>
                </div>
            </div>

            <div>
                <p class="text-xl font-bold">About the Opportunity</p>
                <p class="text-md md:text-lg lg:text-base font-normal text-justify whitespace-pre-wrap">{!! strip_tags($record->description) !!}</p>
            </div>
            <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
        </div>

        <div class="col-span-1 space-y-4">
            <div class="w-full flex flex-col bg-white rounded-xl shadow-lg">
                <div class="px-4 py-4 space-y-4">
                    <h2 class="text-black md:pl-10 lg:pl-0 text-lg text-start font-extrabold">
                        Volunteer Opportunity Details
                    </h2>

                    <div class="w-full flex flex-col md:flex-row space-y-4">
                        <div class="w-full flex flex-col space-y-2">
                            <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-start">
                                <span class="w-8 h-8 flex items-center justify-center bg-blue-200 text-[#03498D] rounded-full mr-4">
                                    <svg class="w-8 h-4 " fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"> <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"></path></svg>
                                </span>
                                <strong>Location:</strong>
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($record->location) }}"
                                   target="_blank"
                                   class="ml-3 text-[#03498D] hover:text-[#FF781E] hover:underline transition-colors duration-300">
                                    {{$record->location}}
                                </a>
                            </p>
                            <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                                <span class="w-8 h-8 flex items-center justify-center bg-blue-200 text-[#03498D] rounded-full mr-4">
                                    <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M19 3h-1V2a1 1 0 1 0-2 0v1H8V2a1 1 0 1 0-2 0v1H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3zm1 16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10h16v9zM4 8V6a1 1 0 0 1 1-1h1v1a1 1 0 1 0 2"></path>
                                    </svg>
                                </span>
                                <strong>Schedule: &nbsp;</strong>{{ \Carbon\Carbon::parse($record->start_date)->format('F d, Y') }}
                            </p>

                            <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                                <span class="w-8 h-8 flex items-center justify-center bg-blue-200 text-[#03498D] rounded-full mr-4">
                                    <svg class="w-8 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 10.59l3.29 3.3a1 1 0 0 1-1.42 1.42l-3.3-3.29a1 1 0 0 1-.29-.7V7a1 1 0 0 1 2 0v5.59z"></path></svg>
                                </span>
                                <strong>Recurrence Type: &nbsp;</strong>{{$record->event_recurrence_type->name}}
                            </p>

                            <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                                <span class="w-8 h-8 flex items-center justify-center bg-blue-200 text-[#03498D] rounded-full mr-4">
                                    <svg class="w-4 h-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                        <path d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V256.9L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6h29.7c33.7 0 64.9 17.7 82.3 46.6l58.3 97c9.1 15.1 4.2 34.8-10.9 43.9s-34.8 4.2-43.9-10.9L232 256.9V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V352H152z"></path>
                                    </svg>
                                </span>
                                <strong>Volunteer Slot: &nbsp;</strong>
                                {{ $record->slots->sum('total_slots') }}
                            </p>

                            <br>
                            <button onclick="document.getElementById('volunteer-section').scrollIntoView({ behavior: 'smooth' });" class="py-2 px-2 flex items-center justify-center rounded-full bg-[#F55E1D] hover:bg-[#FF9141]">
                                @if(!$isEventFinished)
                                <p class="text-base font-normal text-white">I want to volunteer</p>
                                @else
                                    <p class="text-base font-normal text-white">Opportunity Finished</p>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col bg-white rounded-xl shadow-lg">
                <div class="px-4 py-4 space-y-4">
                    <h2 class="text-black md:pl-10 lg:pl-0 text-lg text-start font-extrabold">
                        Contact Information
                    </h2>
                    @php

                    @endphp
                    <div class="w-full flex flex-col">
                        <p class="text-md md:text-lg lg:text-base text-start font-bold">HR Representative:</p>{{$record->point_of_contact?->firstname}} {{$record->point_of_contact?->lastname}}
                        <p class="text-md md:text-lg lg:text-base text-start font-bold">Facilitator/s:</p>
                        <ul class="list-disc pl-5">
                            @foreach ($record->facilitators as $facilitator)
                                <li class="text-md">{{$facilitator->name}}</li>
                            @endforeach
                        </ul>
                        @if($record->getMedia('event-attachments')->count() > 0)
                            <div>
                                <p class="text-md md:text-lg lg:text-base text-start font-bold">File Attachment:</p>
                                <div class="flex flex-col items-start justify-start gap-2">
                                    @foreach($record->getMedia('event-attachments') as $media)
                                        <a href="{{ $media->getUrl() }}"
                                        class="flex items-center gap-2 text-black text-base font-normal hover:text-blue-600"
                                        download>
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                            </svg>
                                            <span class="underline">{{ $media->file_name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <br>
                        <div class="{{ $record->tags->isEmpty() ? 'hidden' : '' }}">
                            <p class="text-md md:text-lg lg:text-base text-start font-bold">Tags:</p>
                            <div class="w-full max-w-[70%] sm:max-w-[40%] lg:max-w-[70%] flex items-center justify-start gap-2 p-2 px-4 text-black text-xs font-normal rounded-[20px]">
                            @foreach ($record->tags as $tag)
                                <div class="w-fit px-2 py-1" style="background:#03498D; border-radius: 10px;">
                                    <p class="text-white">{{ \Illuminate\Support\Str::upper($tag->name) }} <span class="w- inline-flex items-center text-white justify-center cursor-pointer hover:font-[700]"></span></p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($record->event_type_id == 4)
            <div class="w-full flex flex-col bg-white rounded-xl shadow-lg">
                <div class="px-4 py-4 space-y-4">
                    <h2 class="text-black md:pl-10 lg:pl-0 text-lg text-start font-extrabold">
                        Share this opportunity
                    </h2>
                    <div class="sharethis-inline-share-buttons"></div>
                </div>
            </div>
        @endif
        {{-- Add this after the event details section --}}
<div class="w-full col-span-3 space-y-4">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Volunteers Bulletin</h2>
            @if($canManageEvent)
                <button onclick="document.getElementById('bulletin-form').classList.toggle('hidden')"
                        class="px-4 py-2 bg-[#F55E1D] text-white rounded-lg hover:bg-[#FF8252]">
                    Post Bulletin
                </button>
            @endif
        </div>

        {{-- Bulletin Form --}}
        @if($canManageEvent)
            <form id="bulletin-form" action="{{ route('event.post-bulletin', $record->id) }}"
                  method="POST" class="hidden space-y-4 mb-6 p-4 bg-gray-50 rounded-lg">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" required
                           class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#F55E1D] focus:ring-[#F55E1D]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea name="content" rows="3" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#F55E1D] focus:ring-[#F55E1D]"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-[#F55E1D] text-white rounded-lg hover:bg-[#FF8252]">
                        Post
                    </button>
                </div>
            </form>
        @endif

        {{-- Bulletins List --}}
        <div class="space-y-4">
            @forelse($record->bulletins()->orderBy('created_at', 'desc')->limit(2)->get() as $bulletin)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-lg">{{ $bulletin->title }}</h3>
                            <div class="flex items-center space-x-2 text-sm text-gray-500">
                                <span>{{ $bulletin->author->name }}</span>
                                <span>•</span>
                                <span>{{ $bulletin->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if($canManageEvent)
                            <form action="{{ route('event.delete-bulletin', [$record->id, $bulletin->id]) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this bulletin?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                    <p class="mt-2 text-gray-700 whitespace-pre-wrap">{{ $bulletin->content }}</p>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">No bulletins posted yet.</p>
            @endforelse
        </div>
    </div>
</div>

    </div>



</div>

@php
    $userRegistrations = $record->registrations()
        ->where('volunteer_id', auth()->id())
        ->with(['event_slot', 'status'])
        ->get();
@endphp

<div class="w-full col-span-3 p-5 gap-4 bg-gray-100 rounded" id="volunteer-section">
    <div class="w-full space-y-6">
        <!-- Current Registrations Section -->
        @if($userRegistrations->count() > 0)
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-xl font-bold mb-4">Your Registered Shifts</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($userRegistrations as $registration)
                    <div class="border rounded-lg p-4 {{ $registration->status_id == 1 ? 'bg-yellow-50' : 'bg-green-50' }}">
                        <div class="flex flex-col">
                            <h4 class="font-semibold">{{ $registration->event_slot->shift_name }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ Carbon\Carbon::parse($registration->event_slot->start_time)->format('g:i A') }} -
                                {{ Carbon\Carbon::parse($registration->event_slot->end_time)->format('g:i A') }}
                            </p>
                            <span class="inline-flex mt-2 items-center px-2.5 py-0.5 rounded-full text-xs font-medium w-fit
                                {{ $registration->status_id == 1 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $registration->status_id == 1 ? 'Pending Approval' : 'Approved' }}
                            </span>



                            @if(!$isEventFinished)
                                    @if($registration->status_id == 2 || $registration->status_id == 1 )

                                        <form action="{{ route('event.cancel-registration', $registration->id) }}"
                                            method="POST"
                                            class="mt-2"
                                            onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded">
                                                Cancel Registration
                                            </button>
                                        </form>
                                    @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Available Positions Section -->
        <div class="w-full">
            <p class="text-xl font-bold">Available Volunteer Positions</p>
        </div>

        <div class="w-full p-2 space-y-4">
            <!-- ... existing carousel navigation buttons ... -->

            <div class="stories-swiper-container w-full overflow-hidden">
                <div class="swiper-wrapper flex w-full">
                    @foreach ($record->slots as $slot)
                        @php
                            $registrationCount = $record->registrations
                                ->where('slot_type_id', $slot->id)
                                ->where('status_id', '!=', 3)
                                ->count();
                            $isAvailable = $slot->total_slots > $registrationCount;
                            $userRegistered = $userRegistrations
                                ->where('slot_type_id', $slot->id)
                                ->where('status_id', '!=', 3)
                                ->count() > 0;
                        @endphp

                        <div class="swiper-slide bg-white p-5 rounded-md shadow-md transition-shadow duration-300 hover:shadow-xl">
                            <div class="w-full h-full min-h-[200px] flex flex-col gap-4">
                                <div class="flex-grow flex flex-col gap-4">
                                    <p class="text-xl font-semibold leading-none">{{$slot->shift_name}}</p>
                                    <p class="text-[#03498D] text-md">
                                        <span class="font-medium">Available Slots:</span>
                                        {{ $slot->total_slots - $registrationCount }}/{{ $slot->total_slots }}
                                    </p>
                                    <p class="text-lg font-semibold leading-none">Key Responsibility</p>
                                    <p class="text-md overflow-y-scroll custom-scrollbar max-h-[200px] md:text-lg lg:text-base pr-4 font-normal">
                                        {{ $slot->responsibilities }}
                                    </p>
                                </div>

                                <div class="w-full flex items-center justify-center mt-auto">

                                    @if($isEventFinished)

                                        @if(isset($attendeeHours[$slot->id]) && $attendeeHours[$slot->id]['hours'] >= 0)
                                            <span class="h-10 w-[200px] bg-blue-100 text-blue-800 flex items-center justify-center rounded-full">
                                                {{ number_format($attendeeHours[$slot->id]['hours']) }}
                                                @if ($attendeeHours[$slot->id]['hours'] > 1)
                                                    Hours
                                                @else
                                                    Hour
                                                @endif Completed
                                            </span>
                                        @else
                                            <span class="h-10 w-[200px] bg-gray-100 text-gray-800 flex items-center justify-center rounded-full">
                                                Opportunity Finished
                                            </span>
                                        @endif
                                    @elseif($isAvailable && !$userRegistered)
                                        <form action="{{ route('event.register-slot', ['event' => $record->id, 'slot' => $slot->id]) }}"
                                              method="POST"
                                              enctype="multipart/form-data"
                                              class="min-w-full flex justify-between">
                                            @csrf
                                            {{-- Volunteers consented to the privacy policy on registration --}}
                                            <input type="hidden" name="privacy_policy" value="1">

                                            @if($requiresAttachment)
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                                        {{ $attachmentDescription }}
                                                    </label>
                                                    <input type="file"
                                                           name="media[]"
                                                           multiple
                                                           class="block w-full text-sm text-gray-500
                                                                  file:mr-4 file:py-2 file:px-4
                                                                  file:rounded-full file:border-0
                                                                  file:text-sm file:font-semibold
                                                                  file:bg-[#F55E1D] file:text-white
                                                                  hover:file:bg-[#FF8252]"
                                                           required>
                                                    @error('media')
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif

                                            <button type="submit"
                                                    class="h-10 w-[200px] bg-[#ff7b00] text-white font-medium rounded-full hover:bg-[#e06e00] flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M11 20c-3.2 0-5.6-1.5-7-3.8L2 14h3v-2H0v5h2v-1.8C3.7 17.5 6.7 22 11 22v-2zm5 0v2c4.3 0 7.3-4.5 8-6.8V17h2v-5h-5v2h3l-1.4 2.2C21.3 18.5 18.8 20 16 20zm-4.9-7.3c.3.3.6.4.9.4s.6-.1.9-.4c1.6-1.6 3.1-2.9 3.1-4.2C17 6.9 15.9 6 14.8 6c-.6 0-1.2.3-1.8.9-.6-.6-1.2-.9-1.8-.9C10.1 6 9 6.9 9 8.5c0 1.3 1.5 2.6 3.1 4.2z"/>
                                                </svg>
                                                Volunteer for this
                                            </button>
                                        </form>
                                    @elseif($userRegistered)
                                        @if(isset($attendeeHours[$slot->id]) && ($attendeeHours[$slot->id]['hours'] ?? 0) > 0)
                                            <span class="h-10 w-[200px] bg-blue-100 text-blue-800 flex items-center justify-center rounded-full">
                                                {{ number_format($attendeeHours[$slot->id]['hours'], 1) }} Hours Completed
                                            </span>
                                        @else
                                            <span class="h-10 w-[200px] bg-green-100 text-green-800 flex items-center justify-center rounded-full">
                                                Already Registered
                                            </span>
                                        @endif
                                    @else
                                        <span class="h-10 w-[200px] bg-gray-100 text-gray-800 flex items-center justify-center rounded-full">
                                            Slot Full
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>




    <!-- Swiper Script -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <script>
        const storiesSwiper = new Swiper('.stories-swiper-container', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 20,
        navigation: {
            nextEl: '.stories-button-24-next',
            prevEl: '.stories-button-24-prev',
            },
            breakpoints: {
            640: { slidesPerView: 1 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
            },
        });
    </script>

</x-filament-panels::page>
