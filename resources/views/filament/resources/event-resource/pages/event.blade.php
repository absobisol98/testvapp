<x-filament-panels::page>
<head>
    <meta property="og:title" content="{{$record->title}}" />
    <meta property="og:description" content="Volunteer opportunity at Ayala Foundation." />
    <meta property="og:image" content="{{$record->getBanner()}}" />
</head>
<style>
    .fi-main { margin: 0 !important; padding: 0 !important; padding-top: 20px !important; max-width: 100% !important; }
    .fi-page section { padding: 0 0 32px 0 !important; }
    .fi-header { padding-left: 20px !important; }
    .fi-header-heading { display: none; }
    [x-cloak] { display: none !important; }
</style>

@php
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
                $timeIn  = Carbon\Carbon::parse($attendance->time_in);
                $timeOut = Carbon\Carbon::parse($attendance->time_out);
                $diff    = $timeOut->diff($timeIn);
                return ($diff->h + ($diff->days * 24)) * 60 + $diff->i;
            });
            return [$slotId => ['hours' => $totalMinutes / 60, 'minutes' => $totalMinutes % 60]];
        });

    $isSuperAdmin  = $user->hasRole('super_admin');
    $isAdmin       = $user->hasRole('admin');
    $isCreator     = $record->created_by == $user->id;
    $isFacilitator = $record->facilitators->contains($user->id);
    $canManageEvent = !$user->hasActiveRole('Volunteer') && ($isSuperAdmin || $isAdmin || $isCreator || $isFacilitator);

    $userRegistrations = $record->registrations()
        ->where('volunteer_id', auth()->id())
        ->with(['event_slot', 'status'])
        ->get();

    // Status badge logic
    $start    = Carbon\Carbon::parse($record->start_date);
    $end      = $record->end_date ? Carbon\Carbon::parse($record->end_date) : null;
    $isFinished = $isEventFinished;
    $isOngoing  = !$isFinished && now()->isAfter($start);
    $daysAway   = (int) now()->startOfDay()->diffInDays($start->copy()->startOfDay());

    $statusLabel = match(true) {
        $isFinished          => 'Opportunity Finished',
        $isOngoing           => 'Ongoing',
        $start->isToday()    => 'Starts Today',
        $start->isTomorrow() => 'Tomorrow',
        default              => 'Starting in ' . $daysAway . ' day' . ($daysAway !== 1 ? 's' : ''),
    };

    $statusDotClass = match(true) {
        $isFinished => 'bg-red-500',
        $isOngoing  => 'bg-green-500 animate-pulse',
        default     => 'bg-amber-500',
    };

    // Date / time strings
    $sameDay = $end && $start->format('Y-m-d') === $end->format('Y-m-d');
    $dateStr = $start->format('M d, Y');
    if ($end && !$sameDay) {
        $dateStr .= ' – ' . $end->format('M d, Y');
    }

    $firstSlot = $record->slots->first();
    $timeStr = ($firstSlot && $firstSlot->start_time && $firstSlot->end_time)
        ? Carbon\Carbon::parse($firstSlot->start_time)->format('g:i A')
          . ' – '
          . Carbon\Carbon::parse($firstSlot->end_time)->format('g:i A')
        : null;

    $category       = $record->program?->name ?? 'Ayala Foundation';
    $location       = $record->location ?? 'Location TBA';
    $totalPositions = $record->slots->count();

    // Resolve each slot's effective format (slot override beats event-level)
    $eventFormat      = $record->event_format ?? 'onsite';
    $resolvedFormats  = $record->slots->map(fn($s) =>
        ($s->slot_format && $s->slot_format !== '' && $s->slot_format !== 'inherit')
            ? $s->slot_format
            : $eventFormat
    );
    // Hybrid when: ≥1 virtual slot AND ≥1 onsite slot (after resolving overrides)
    $hasVirtualSlot = $resolvedFormats->contains('virtual');
    $hasOnsiteSlot  = $resolvedFormats->contains('onsite');
    $isHybrid       = $hasVirtualSlot && $hasOnsiteSlot;
    $locationType   = match(true) {
        $isHybrid                  => 'Hybrid',
        $eventFormat === 'virtual' => 'Online',
        default                    => 'Onsite',
    };

    // Group slots by shift_date for the date-tab rail
    $groupedSlots = $record->slots
        ->groupBy(fn($s) => Carbon\Carbon::parse($s->shift_date ?? $record->start_date)->format('Y-m-d'))
        ->sortKeys()
        ->map(function ($slots, $dateKey) use ($isFinished) {
            $date = Carbon\Carbon::parse($dateKey);
            return [
                'key'       => $dateKey,
                'dayName'   => $date->format('D'),
                'dayNum'    => (int) $date->format('j'),
                'monthName' => $date->format('M'),
                'fullLabel' => $date->format('D, M j, Y'),
                'isPast'    => $date->isPast() && !$date->isToday(),
                'isToday'   => $date->isToday(),
                'slotCount' => $slots->count(),
            ];
        })
        ->values()
        ->toArray();
    $totalDates = count($groupedSlots);

    $manageUrl = route('filament.admin.resources.events.manage-volunteers', ['record' => $record->id]);
    $editUrl   = route('filament.admin.resources.events.edit', ['record' => $record->id]);
    $listUrl   = route('filament.admin.resources.events.index');
    $image     = $record->getBanner();
@endphp

<div class="flex flex-col gap-5 pb-12 px-3 sm:px-4 md:px-6 overflow-x-hidden">

    {{-- ── HERO ─────────────────────────────────────────────────────── --}}
    <div class="relative rounded-2xl overflow-hidden shadow-[0_2px_6px_rgba(0,20,50,.07),0_6px_20px_rgba(0,20,50,.07)]"
         style="min-height:280px;">

        @if($image)
            <img src="{{ $image }}" alt="{{ $record->title }}"
                 class="absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0 bg-gradient-to-br from-[#afc9de] to-[#c4d9e9]"></div>
        @endif

        {{-- Dark gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-[#072b54]/85 via-[#072b54]/30 to-transparent"></div>

        {{-- Status badge – top right --}}
        <div class="absolute top-4 right-4 z-10">
            <span class="inline-flex items-center gap-1.5 bg-black/50 backdrop-blur-sm
                         border border-white/15 rounded-full px-3.5 py-1.5
                         text-[12px] font-semibold text-white/90">
                <span class="w-2 h-2 rounded-full flex-shrink-0 {{ $statusDotClass }}"></span>
                {{ $statusLabel }}
            </span>
        </div>

        {{-- Bottom: title + action buttons --}}
        <div class="absolute inset-x-0 bottom-0 z-10 flex flex-wrap items-end justify-between gap-4 px-6 sm:px-8 pb-6 pt-14">

            <div class="min-w-0">
                <span class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-sm border border-white/25
                             rounded-full px-3 py-1 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#f55e1d] flex-shrink-0"></span>
                    <span class="text-[11px] font-bold tracking-[.1em] uppercase text-white">{{ $category }}</span>
                </span>
                <h1 class="text-[22px] sm:text-[28px] font-extrabold text-white leading-tight tracking-tight drop-shadow-md">
                    {{ $record->title }}
                </h1>
            </div>

            <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                @if($canManageEvent)
                    <a href="{{ $manageUrl }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg
                              bg-white/12 hover:bg-white/22 backdrop-blur-sm border border-white/25
                              text-white text-[12.5px] font-semibold transition-colors duration-150">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span class="hidden sm:inline">Manage Volunteers</span>
                    </a>
                    <a href="{{ $editUrl }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg
                              bg-white/12 hover:bg-white/22 backdrop-blur-sm border border-white/25
                              text-white text-[12.5px] font-semibold transition-colors duration-150">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        <span class="hidden sm:inline">Edit</span>
                    </a>
                @endif

                <a href="{{ $listUrl }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-lg
                          bg-[#f55e1d] hover:bg-[#d44e14] text-white text-[12.5px] font-semibold
                          shadow-[0_3px_10px_rgba(242,101,34,.35)] transition-colors duration-150">
                    <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>
                    </svg>
                    <span class="hidden sm:inline">Opportunity List</span>
                </a>
            </div>
        </div>
    </div>{{-- /hero --}}


    {{-- ── TWO-COLUMN LAYOUT ───────────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-5 items-start">

        {{-- ── LEFT COLUMN ──────────────────────────────────────────── --}}
        <div class="flex flex-col gap-5">

            {{-- About the Opportunity --}}
            <div class="bg-white rounded-2xl shadow-[0_2px_6px_rgba(0,20,50,.07),0_6px_20px_rgba(0,20,50,.07)] overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-[14px] font-bold text-[#072b54] tracking-tight">About the Opportunity</h2>
                </div>
                <div class="px-6 py-5">
                    <span class="inline-flex items-center gap-1.5 bg-[#fff3eb] border border-[#f55e1d]/20
                                 rounded-full px-3 py-1 text-[12px] font-bold text-[#f55e1d] mb-4">
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><path d="M7 7h.01"/>
                        </svg>
                        {{ $category }}
                    </span>
                    <p class="text-[13.5px] text-[#4a5568] leading-relaxed whitespace-pre-wrap">
                        {!! strip_tags($record->description) !!}
                    </p>
                </div>
            </div>

            {{-- Available Volunteer Positions --}}
            <div class="bg-white rounded-2xl shadow-[0_2px_6px_rgba(0,20,50,.07),0_6px_20px_rgba(0,20,50,.07)] overflow-hidden"
                 id="volunteer-section"
                 x-data="{
                     activeIdx: 0,
                     select(i) {
                         this.activeIdx = i;
                         this.$nextTick(() => {
                             const chip = this.$refs.rail?.children[i];
                             if (chip) chip.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
                         });
                     },
                     scrollRail(dir) {
                         if (this.$refs.rail) this.$refs.rail.scrollLeft += dir * 200;
                     }
                 }">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-[14px] font-bold text-[#072b54] tracking-tight">Available Volunteer Positions</h2>
                    <span class="text-[12px] font-semibold text-[#718096] bg-[#edf2f7] px-3 py-1 rounded-full">
                        {{ $totalDates }} {{ Str::plural('date', $totalDates) }} · {{ $totalPositions }} {{ Str::plural('position', $totalPositions) }}
                    </span>
                </div>

                {{-- Registered shifts banner --}}
                @if($userRegistrations->count() > 0)
                    <div class="px-6 py-4 border-b border-gray-100 bg-[#f7fafc]">
                        <p class="text-[13px] font-bold text-[#072b54] mb-3">Your Registered Shifts</p>
                        <div class="flex flex-col gap-2">
                            @foreach($userRegistrations as $registration)
                                <div class="flex items-center justify-between bg-white border border-[#e2e8f0] rounded-xl px-4 py-3">
                                    <div>
                                        <p class="text-[13px] font-semibold text-[#1a2332]">{{ $registration->event_slot->shift_name }}</p>
                                        <p class="text-[12px] text-[#718096]">
                                            {{ Carbon\Carbon::parse($registration->event_slot->start_time)->format('g:i A') }} –
                                            {{ Carbon\Carbon::parse($registration->event_slot->end_time)->format('g:i A') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                            {{ $registration->status_id == 1 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $registration->status_id == 1 ? 'Pending Approval' : 'Approved' }}
                                        </span>
                                        @if(!$isEventFinished && ($registration->status_id == 2 || $registration->status_id == 1))
                                            <form action="{{ route('event.cancel-registration', $registration->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure you want to cancel this registration?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[12px] text-red-500 hover:text-red-700 font-semibold">
                                                    Cancel
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($totalPositions === 0)
                    <div class="py-10 text-center text-[#718096] text-[13px]">No volunteer positions added yet.</div>
                @else

                    {{-- Date Rail --}}
                    <div class="relative border-b border-[#e2e8f0]">
                        <button type="button" @click="scrollRail(-1)"
                                class="absolute left-2 top-1/2 -translate-y-1/2 z-10 w-7 h-7 rounded-full
                                       bg-white border border-[#e2e8f0] flex items-center justify-center
                                       text-[#4a5568] shadow-sm hover:bg-[#072b54] hover:border-[#072b54]
                                       hover:text-white transition-all duration-150">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                        </button>

                        <div x-ref="rail" class="flex gap-2 overflow-x-auto px-10 py-3"
                             style="scrollbar-width:none;-ms-overflow-style:none;">
                            @foreach($groupedSlots as $gIdx => $dateGroup)
                                <button type="button"
                                        @click="select({{ $gIdx }})"
                                        :class="activeIdx === {{ $gIdx }}
                                            ? 'bg-[#072b54] border-[#072b54] shadow-[0_4px_14px_rgba(7,43,84,.25)]'
                                            : 'bg-white border-[#e2e8f0] hover:border-[#c4cdd8] hover:bg-[#f8fafc]'"
                                        class="relative flex flex-col items-center gap-0.5 flex-shrink-0
                                               border rounded-xl px-3.5 py-2.5 min-w-[72px]
                                               transition-all duration-150 cursor-pointer">
                                    <span :class="activeIdx === {{ $gIdx }} ? 'text-white/70' : '{{ $dateGroup['isToday'] ? 'text-[#f55e1d]' : 'text-[#718096]' }}'"
                                          class="text-[10px] font-bold tracking-[.06em] uppercase leading-none">
                                        {{ $dateGroup['dayName'] }}
                                    </span>
                                    <span :class="activeIdx === {{ $gIdx }} ? 'text-white' : '{{ $dateGroup['isToday'] ? 'text-[#f55e1d]' : 'text-[#072b54]' }}'"
                                          class="text-[18px] font-extrabold leading-none">
                                        {{ $dateGroup['dayNum'] }}
                                    </span>
                                    <span :class="activeIdx === {{ $gIdx }} ? 'text-white/70' : 'text-[#4a5568]'"
                                          class="text-[10px] font-semibold leading-none">
                                        {{ $dateGroup['monthName'] }}
                                    </span>
                                    <span class="absolute -top-1.5 -right-1.5 w-[18px] h-[18px] rounded-full
                                                 bg-[#f55e1d] text-white text-[9px] font-extrabold
                                                 flex items-center justify-center border-2"
                                          :class="activeIdx === {{ $gIdx }} ? 'border-[#072b54]' : 'border-white'">
                                        {{ $dateGroup['slotCount'] }}
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        <button type="button" @click="scrollRail(1)"
                                class="absolute right-2 top-1/2 -translate-y-1/2 z-10 w-7 h-7 rounded-full
                                       bg-white border border-[#e2e8f0] flex items-center justify-center
                                       text-[#4a5568] shadow-sm hover:bg-[#072b54] hover:border-[#072b54]
                                       hover:text-white transition-all duration-150">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                        </button>
                    </div>{{-- /date rail --}}

                    {{-- Slot panels per date (server-side rendered, Alpine shows/hides) --}}
                    @foreach($groupedSlots as $gIdx => $dateGroup)
                        @php
                            $dateFinished = $isFinished || ($dateGroup['isPast']);
                            $slotsForDate = $record->slots->filter(
                                fn($s) => Carbon\Carbon::parse($s->shift_date ?? $record->start_date)->format('Y-m-d') === $dateGroup['key']
                            );
                        @endphp
                        <div x-show="activeIdx === {{ $gIdx }}" x-cloak class="p-5">

                            {{-- Date header --}}
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-[9px] bg-[#e8eef8] flex items-center justify-center flex-shrink-0">
                                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#0e4f99" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[13.5px] font-bold text-[#072b54]">{{ $dateGroup['fullLabel'] }}</p>
                                    <p class="text-[12px] text-[#718096]">
                                        {{ $dateGroup['slotCount'] }} {{ Str::plural('position', $dateGroup['slotCount']) }} available
                                        @if($dateFinished)
                                            <span class="text-red-500 font-semibold"> · Finished</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                @foreach($slotsForDate as $slot)
                                    @php
                                        $registrationCount = $record->registrations
                                            ->where('slot_type_id', $slot->id)
                                            ->where('status_id', '!=', 3)
                                            ->count();
                                        $available      = max(0, $slot->total_slots - $registrationCount);
                                        $pct            = $slot->total_slots > 0
                                                          ? round(($registrationCount / $slot->total_slots) * 100)
                                                          : 100;
                                        $isFull         = $registrationCount >= $slot->total_slots;
                                        $userRegistered = $userRegistrations
                                            ->where('slot_type_id', $slot->id)
                                            ->where('status_id', '!=', 3)
                                            ->count() > 0;
                                        $barColor   = $pct >= 100 ? '#dc2626' : ($pct >= 80 ? '#d97706' : '#f55e1d');
                                        $pctColor   = $pct >= 100 ? 'color:#dc2626' : ($pct >= 80 ? 'color:#d97706' : 'color:#f55e1d');
                                        $badgeClass = $isFull
                                            ? 'border-red-400 text-red-500'
                                            : ($pct >= 80 ? 'border-amber-400 text-amber-600' : 'border-[#f55e1d] text-[#f55e1d]');

                                        // Resolve effective format: slot-level overrides event-level
                                        $resolvedFormat = ($slot->slot_format && $slot->slot_format !== '' && $slot->slot_format !== 'inherit')
                                            ? $slot->slot_format
                                            : $eventFormat;
                                        $isVirtual = $resolvedFormat === 'virtual';
                                    @endphp

                                    <div class="bg-[#f7fafc] border border-[#e2e8f0] rounded-xl overflow-hidden
                                                hover:border-[#c4cdd8] hover:shadow-[0_4px_16px_rgba(0,20,50,.08)]
                                                transition-all duration-150 flex flex-col">

                                        {{-- Head --}}
                                        <div class="flex items-start justify-between gap-2 px-4 pt-4 pb-3 border-b border-[#e2e8f0]">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                                                    <p class="text-[14px] font-extrabold text-[#072b54]">{{ $slot->shift_name }}</p>
                                                    {{-- Format pill --}}
                                                    @if($isVirtual)
                                                        <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-600 border border-blue-200 rounded-full px-2 py-0.5 text-[10px] font-bold">
                                                            <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 10l4.553-2.069A1 1 0 0 1 21 8.82v6.36a1 1 0 0 1-1.447.889L15 14M3 8a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8z"/></svg>
                                                            Online
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 rounded-full px-2 py-0.5 text-[10px] font-bold">
                                                            <svg width="9" height="9" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                                            Onsite
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-1.5 text-[11.5px] text-[#718096]">
                                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"/><path d="M12 7v5l3 2"/>
                                                    </svg>
                                                    {{ Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                                    – {{ Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                </div>
                                            </div>
                                            <span class="inline-flex items-center gap-1 bg-white rounded-full px-2.5 py-0.5
                                                         text-[11.5px] font-bold flex-shrink-0 border-[1.5px] {{ $badgeClass }}">
                                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                                                </svg>
                                                {{ $isFull ? 'Full' : $available . ' left' }} / {{ $slot->total_slots }}
                                            </span>
                                        </div>

                                        {{-- Body --}}
                                        <div class="px-4 py-3 flex-1">
                                            <p class="text-[10.5px] font-bold text-[#718096] uppercase tracking-[.08em] mb-1.5">
                                                Key Responsibility
                                            </p>
                                            <p class="text-[12.5px] text-[#4a5568] leading-relaxed max-h-[100px] overflow-y-auto">
                                                {{ $slot->responsibilities }}
                                            </p>

                                            {{-- Meeting link for virtual slots --}}
                                            @if($isVirtual && $slot->meeting_link)
                                                <a href="{{ $slot->meeting_link }}" target="_blank"
                                                   class="mt-2 inline-flex items-center gap-1.5 text-[12px] text-blue-600 font-semibold hover:underline">
                                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                                    </svg>
                                                    Join meeting link
                                                </a>
                                            @endif

                                            {{-- Fill bar --}}
                                            <div class="mt-3">
                                                <div class="flex justify-between mb-1.5">
                                                    <span class="text-[11px] font-semibold text-[#718096]">Slots filled</span>
                                                    <span class="text-[11px] font-bold" style="{{ $pctColor }}">{{ $pct }}%</span>
                                                </div>
                                                <div class="h-1.5 bg-[#e2e8f0] rounded-full overflow-hidden">
                                                    <div class="h-full rounded-full transition-all duration-500"
                                                         style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Footer / CTA --}}
                                        <div class="px-4 pb-4 pt-1">
                                            @if($dateFinished)
                                                @if(isset($attendeeHours[$slot->id]) && ($attendeeHours[$slot->id]['hours'] ?? 0) > 0)
                                                    <div class="w-full flex items-center justify-center gap-1.5
                                                                bg-blue-50 text-blue-700 rounded-full px-4 py-2.5 text-[12.5px] font-bold">
                                                        {{ number_format($attendeeHours[$slot->id]['hours'], 1) }}
                                                        {{ $attendeeHours[$slot->id]['hours'] > 1 ? 'Hours' : 'Hour' }} Completed
                                                    </div>
                                                @else
                                                    <div class="w-full flex items-center justify-center gap-1.5
                                                                bg-red-50 text-red-600 rounded-full px-4 py-2.5 text-[12.5px] font-bold">
                                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                                            <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>
                                                        </svg>
                                                        Opportunity Finished
                                                    </div>
                                                @endif

                                            @elseif($userRegistered)
                                                @if(isset($attendeeHours[$slot->id]) && ($attendeeHours[$slot->id]['hours'] ?? 0) > 0)
                                                    <div class="w-full flex items-center justify-center gap-1.5
                                                                bg-blue-50 text-blue-700 rounded-full px-4 py-2.5 text-[12.5px] font-bold">
                                                        {{ number_format($attendeeHours[$slot->id]['hours'], 1) }} Hours Completed
                                                    </div>
                                                @else
                                                    <div class="w-full flex items-center justify-center gap-1.5
                                                                bg-green-50 text-green-700 rounded-full px-4 py-2.5 text-[12.5px] font-bold">
                                                        Already Registered
                                                    </div>
                                                @endif

                                            @elseif($isFull)
                                                <div class="w-full flex items-center justify-center gap-1.5
                                                            bg-amber-50 text-amber-600 rounded-full px-4 py-2.5 text-[12.5px] font-bold">
                                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/>
                                                    </svg>
                                                    Slots Full
                                                </div>

                                            @else
                                                <form action="{{ route('event.register-slot', ['event' => $record->id, 'slot' => $slot->id]) }}"
                                                      method="POST" enctype="multipart/form-data" class="w-full">
                                                    @csrf
                                                    <input type="hidden" name="privacy_policy" value="1">

                                                    @if($requiresAttachment)
                                                        <div class="mb-3">
                                                            <label class="block text-[12px] font-medium text-[#4a5568] mb-1.5">
                                                                {{ $attachmentDescription }}
                                                            </label>
                                                            <input type="file" name="media[]" multiple required
                                                                   class="block w-full text-[12px] text-[#718096]
                                                                          file:mr-3 file:py-1.5 file:px-3
                                                                          file:rounded-full file:border-0
                                                                          file:text-[12px] file:font-semibold
                                                                          file:bg-[#f55e1d] file:text-white
                                                                          hover:file:bg-[#d44e14]">
                                                            @error('media')
                                                                <p class="mt-1 text-[11px] text-red-600">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    <button type="submit"
                                                            class="w-full flex items-center justify-center gap-1.5
                                                                   bg-[#f55e1d] hover:bg-[#d44e14] text-white
                                                                   rounded-full px-4 py-2.5 text-[12.5px] font-bold
                                                                   shadow-[0_3px_10px_rgba(242,101,34,.28)]
                                                                   transition-colors duration-150">
                                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
                                                        </svg>
                                                        Volunteer for this
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                @endif
            </div>{{-- /positions --}}

        </div>{{-- /left column --}}


        {{-- ── RIGHT SIDEBAR ────────────────────────────────────────── --}}
        <div class="flex flex-col gap-4">

            {{-- Opportunity Details --}}
            <div class="bg-white rounded-2xl shadow-[0_2px_6px_rgba(0,20,50,.07),0_6px_20px_rgba(0,20,50,.07)] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-[14px] font-bold text-[#072b54]">Opportunity Details</h2>
                </div>

                <div class="divide-y divide-[#f7fafc]">

                    {{-- Location --}}
                    <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-[#fafbfc] transition-colors">
                        <div class="w-8 h-8 rounded-[9px] bg-[#e8eef8] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#0e4f99]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em]">Location</p>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-full
                                    {{ $locationType === 'Hybrid' ? 'bg-purple-50 text-purple-600' : ($locationType === 'Online' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-700') }}">
                                    {{ $locationType }}
                                </span>
                            </div>
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location) }}"
                               target="_blank"
                               class="text-[13.5px] font-semibold text-[#0e4f99] mt-0.5 hover:underline block break-words">
                                {{ $location }}
                            </a>
                        </div>
                    </div>

                    {{-- Schedule --}}
                    <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-[#fafbfc] transition-colors">
                        <div class="w-8 h-8 rounded-[9px] bg-[#fff3eb] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#f55e1d]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em]">Schedule</p>
                            <p class="text-[13.5px] font-semibold text-[#1a2332] mt-0.5">{{ $dateStr }}</p>
                        </div>
                    </div>

                    {{-- Time (from first slot) --}}
                    @if($timeStr)
                        <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-[#fafbfc] transition-colors">
                            <div class="w-8 h-8 rounded-[9px] bg-[#e6f4ec] flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-[#16a34a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 7v5l3 2"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em]">Time</p>
                                <p class="text-[13.5px] font-semibold text-[#1a2332] mt-0.5">{{ $timeStr }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Recurrence Type --}}
                    <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-[#fafbfc] transition-colors">
                        <div class="w-8 h-8 rounded-[9px] bg-[#f0ebfa] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#7c3aed]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em]">Recurrence Type</p>
                            <p class="text-[13.5px] font-semibold text-[#1a2332] mt-0.5">{{ $record->event_recurrence_type->name }}</p>
                        </div>
                    </div>

                    {{-- Volunteer Slots --}}
                    <div class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-[#fafbfc] transition-colors">
                        <div class="w-8 h-8 rounded-[9px] bg-[#e8eef8] flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-[#0e4f99]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em]">Volunteer Slots</p>
                            <p class="text-[13.5px] font-semibold text-[#1a2332] mt-0.5">
                                {{ $record->slots->sum('total_slots') }} total
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Status CTA --}}
                <div class="px-5 py-4">
                    @if($isFinished)
                        <div class="w-full flex items-center justify-center gap-2
                                    bg-red-50 text-red-600 border border-red-100
                                    rounded-xl px-4 py-3 text-[13px] font-bold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>
                            </svg>
                            Opportunity Finished
                        </div>
                    @elseif($isOngoing)
                        <div class="w-full flex items-center justify-center gap-2
                                    bg-green-50 text-green-700 border border-green-100
                                    rounded-xl px-4 py-3 text-[13px] font-bold">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>
                            </svg>
                            Currently Ongoing
                        </div>
                    @else
                        <button onclick="document.getElementById('volunteer-section').scrollIntoView({ behavior: 'smooth' });"
                                class="w-full flex items-center justify-center gap-2
                                       bg-[#f55e1d] hover:bg-[#d44e14] text-white
                                       rounded-xl px-4 py-3 text-[13px] font-bold
                                       shadow-[0_3px_10px_rgba(242,101,34,.3)]
                                       transition-colors duration-150">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                            I want to volunteer
                        </button>
                    @endif
                </div>
            </div>{{-- /details --}}


            {{-- Contact Information --}}
            <div class="bg-white rounded-2xl shadow-[0_2px_6px_rgba(0,20,50,.07),0_6px_20px_rgba(0,20,50,.07)] overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-[14px] font-bold text-[#072b54]">Contact Information</h2>
                </div>
                <div class="px-6 py-4 flex flex-col gap-4">

                    {{-- HR Representative --}}
                    <div>
                        <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em] mb-2">HR Representative</p>
                        @if($record->point_of_contact)
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-[#1a2c3e] text-white text-[10px] font-extrabold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($record->point_of_contact->firstname ?? 'HR', 0, 2)) }}
                                </div>
                                <span class="text-[13px] font-semibold text-[#1a2332]">
                                    {{ $record->point_of_contact->firstname }} {{ $record->point_of_contact->lastname }}
                                </span>
                            </div>
                        @else
                            <p class="text-[13px] text-[#718096]">—</p>
                        @endif
                    </div>

                    <div class="h-px bg-[#e2e8f0] -mx-6"></div>

                    {{-- Facilitators --}}
                    <div>
                        <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em] mb-2">Facilitator/s</p>
                        @forelse($record->facilitators as $fac)
                            <div class="flex items-center gap-2.5 mb-2 last:mb-0">
                                <div class="w-7 h-7 rounded-full bg-[#1e40af] text-white text-[10px] font-extrabold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($fac->name, 0, 2)) }}
                                </div>
                                <span class="text-[13px] font-semibold text-[#1a2332]">{{ $fac->name }}</span>
                            </div>
                        @empty
                            <p class="text-[13px] text-[#718096]">No facilitators assigned.</p>
                        @endforelse
                    </div>

                    @if($record->getMedia('event-attachments')->count() > 0)
                        <div class="h-px bg-[#e2e8f0] -mx-6"></div>
                        <div>
                            <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em] mb-2">File Attachments</p>
                            @foreach($record->getMedia('event-attachments') as $media)
                                <a href="{{ $media->getUrl() }}" download
                                   class="flex items-center gap-2 text-[#0e4f99] hover:text-[#f55e1d] text-[13px] font-semibold mb-1.5 last:mb-0 transition-colors">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                    </svg>
                                    <span class="underline break-all">{{ $media->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if(!$record->tags->isEmpty())
                        <div class="h-px bg-[#e2e8f0] -mx-6"></div>
                        <div>
                            <p class="text-[11px] font-bold text-[#718096] uppercase tracking-[.07em] mb-2">Tags</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($record->tags as $tag)
                                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold text-white"
                                          style="background:#03498D;">
                                        {{ Str::upper($tag->name) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>{{-- /contact --}}


            {{-- Volunteers Bulletin --}}
            <div class="bg-white rounded-2xl shadow-[0_2px_6px_rgba(0,20,50,.07),0_6px_20px_rgba(0,20,50,.07)] overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h2 class="text-[14px] font-bold text-[#072b54]">Volunteers Bulletin</h2>
                    @if($canManageEvent)
                        <button type="button"
                                onclick="document.getElementById('bulletin-form').classList.toggle('hidden')"
                                class="inline-flex items-center gap-1.5 bg-[#f55e1d] hover:bg-[#d44e14]
                                       text-white text-[12px] font-bold rounded-lg px-3 py-1.5
                                       transition-colors duration-150">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                            Post Bulletin
                        </button>
                    @endif
                </div>

                {{-- Post form --}}
                @if($canManageEvent)
                    <div id="bulletin-form" class="hidden border-b border-gray-100 px-5 py-4 bg-[#f7fafc]">
                        <form method="POST" action="{{ route('event.post-bulletin', $record->id) }}">
                            @csrf
                            <input type="text" name="title" placeholder="Bulletin title…"
                                   class="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-[13px]
                                          placeholder-[#a0aec0] mb-2
                                          focus:outline-none focus:ring-2 focus:ring-[#f55e1d]/30 focus:border-[#f55e1d]
                                          transition-all">
                            <textarea name="content" rows="2" placeholder="Write your message…"
                                      class="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-[13px]
                                             placeholder-[#a0aec0] resize-none mb-3
                                             focus:outline-none focus:ring-2 focus:ring-[#f55e1d]/30 focus:border-[#f55e1d]
                                             transition-all"></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button"
                                        onclick="document.getElementById('bulletin-form').classList.add('hidden')"
                                        class="px-3 py-1.5 text-[12px] font-semibold text-[#718096] hover:text-[#1a2332] transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-1.5 bg-[#f55e1d] hover:bg-[#d44e14] text-white text-[12px] font-bold rounded-lg transition-colors">
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Bulletin list --}}
                <div class="flex flex-col divide-y divide-[#f7fafc]">
                    @forelse($record->bulletins()->orderBy('created_at', 'desc')->limit(5)->get() as $bulletin)
                        <div class="px-5 py-4 hover:bg-[#fafbfc] transition-colors">
                            <div class="flex items-start justify-between mb-1.5">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="w-6 h-6 rounded-full bg-[#1a2c3e] text-white text-[9px] font-extrabold flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($bulletin->author->name ?? 'U', 0, 2)) }}
                                    </div>
                                    <span class="text-[12.5px] font-bold text-[#1a2332]">
                                        {{ $bulletin->author->name ?? 'Unknown' }}
                                    </span>
                                    <span class="text-[11px] text-[#718096]">· {{ $bulletin->created_at->diffForHumans() }}</span>
                                </div>
                                @if($canManageEvent)
                                    <form action="{{ route('event.delete-bulletin', [$record->id, $bulletin->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this bulletin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-6 h-6 flex items-center justify-center rounded-md
                                                       text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            @if($bulletin->title)
                                <p class="text-[13px] font-bold text-[#072b54] mb-1">{{ $bulletin->title }}</p>
                            @endif
                            <p class="text-[12.5px] text-[#4a5568] leading-snug whitespace-pre-wrap">
                                {{ $bulletin->content }}
                            </p>
                        </div>
                    @empty
                        <div class="py-8 text-center">
                            <p class="text-[13px] text-[#718096]">No bulletins posted yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>{{-- /bulletin --}}

        </div>{{-- /right sidebar --}}

    </div>{{-- /two-col --}}

</div>{{-- /page --}}
</x-filament-panels::page>
