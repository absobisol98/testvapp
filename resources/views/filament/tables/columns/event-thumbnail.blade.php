@php
    $record = $getRecord();
    $user   = auth()->user();

    $banner = $record->getMedia('event-banner-attachments')?->first()?->getUrl();

    // Slot availability
    $totalSlots  = $record->slots->sum('total_slots');
    $filledSlots = $record->registrations->whereNotIn('status_id', [3])->count();
    $availableSlots = max(0, $totalSlots - $filledSlots);
    $isFull = $availableSlots === 0 && $totalSlots > 0;

    // Current user's registration (if any)
    $myRegistration = $record->registrations
        ->where('volunteer_id', $user->id)
        ->whereNotIn('status_id', [3])
        ->first();

    // Compute type badge — Hybrid auto-detected when slot formats differ
    $eventFormat    = $record->event_format ?? 'onsite';
    $hasVirtualSlot = $record->slots->contains(fn($s) => $s->slot_format === 'virtual');
    $hasOnsiteSlot  = $record->slots->contains(fn($s) => $s->slot_format === 'onsite');
    $isHybrid = ($hasVirtualSlot && $hasOnsiteSlot)
             || ($hasVirtualSlot && $eventFormat === 'onsite')
             || ($hasOnsiteSlot  && $eventFormat === 'virtual');

    $typeBadge = match(true) {
        $isHybrid                  => ['label' => 'Hybrid',  'color' => 'bg-purple-100 text-purple-700'],
        $eventFormat === 'virtual' => ['label' => 'Virtual', 'color' => 'bg-blue-100 text-blue-700'],
        default                    => ['label' => 'Onsite',  'color' => 'bg-green-100 text-green-700'],
    };

    $isFinished = now()->isAfter($record->end_date);
    $isOngoing  = !$isFinished && now()->isAfter($record->start_date);

    $viewUrl = route('filament.admin.resources.events.view', ['record' => $record->id]);
@endphp

<div class="flex flex-col h-full min-h-[280px] group cursor-pointer"
     onclick="window.location='{{ $viewUrl }}'">

    {{-- Banner --}}
    <div class="relative overflow-hidden rounded-t-xl h-40 bg-gray-100 flex-shrink-0">
        <img class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
             src="{{ $banner ?? url('img/ayala-foundation-bg.jpg') }}"
             alt="{{ $record->title }}">

        {{-- Top-left: type + status --}}
        <div class="absolute top-2 left-2 flex gap-1 flex-wrap">
            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $typeBadge['color'] }}">
                {{ $typeBadge['label'] }}
            </span>
            @if($isFinished)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gray-200 text-gray-600">Completed</span>
            @elseif($isOngoing)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-100 text-orange-700">Ongoing</span>
            @else
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Upcoming</span>
            @endif
        </div>

        {{-- Top-right: registration status --}}
        @if($myRegistration)
            <div class="absolute top-2 right-2">
                @if($myRegistration->status_id == 1)
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-yellow-400 text-yellow-900">Pending</span>
                @elseif($myRegistration->status_id == 2)
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-green-500 text-white">✓ Registered</span>
                @endif
            </div>
        @endif
    </div>

    {{-- Body --}}
    <div class="flex flex-col flex-grow p-3 space-y-1.5">

        <h3 class="font-bold text-base leading-tight line-clamp-2 capitalize group-hover:text-primary-600 transition-colors"
            title="{{ $record->title }}">
            {{ $record->title }}
        </h3>

        <p class="text-xs text-indigo-600 font-semibold uppercase tracking-wide">
            {{ \Carbon\Carbon::parse($record->start_date)->format('M d, Y') }}
            @if($record->start_date->format('Y-m-d') !== $record->end_date->format('Y-m-d'))
                – {{ \Carbon\Carbon::parse($record->end_date)->format('M d, Y') }}
            @endif
        </p>

        @if($eventFormat === 'virtual')
            <p class="text-xs text-gray-500 flex items-center gap-1">
                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
                Online / Virtual
            </p>
        @elseif($record->location)
            <p class="text-xs text-gray-500 flex items-center gap-1">
                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 384 512">
                    <path d="M215.7 499.2C267 435 384 279.4 384 192 384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2 12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/>
                </svg>
                <span class="truncate max-w-[180px]">{{ $record->location }}</span>
            </p>
        @endif

        <div class="flex-grow"></div>

        {{-- Footer --}}
        <div class="pt-2 border-t border-gray-100 flex items-center justify-between">
            @if($totalSlots > 0)
                @if($isFull)
                    <span class="text-xs font-medium text-red-500 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>Slot Full
                    </span>
                @else
                    <span class="text-xs font-medium text-green-600 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>
                        {{ $availableSlots }} open
                    </span>
                @endif
            @else
                <span class="text-xs text-gray-300">—</span>
            @endif

            @if($record->program)
                <span class="text-xs text-gray-400 truncate max-w-[120px]">{{ $record->program->name }}</span>
            @endif
        </div>
    </div>
</div>
