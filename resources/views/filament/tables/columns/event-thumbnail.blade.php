@php
    $record = $getRecord();
    $user   = auth()->user();

    $image = $record->getMedia('event-banner-attachments')?->first()?->getUrl();

    $start      = \Carbon\Carbon::parse($record->start_date);
    $end        = $record->end_date ? \Carbon\Carbon::parse($record->end_date) : null;
    $isFinished = $end && now()->isAfter($end);
    $isOngoing  = !$isFinished && now()->isAfter($start);

    // Compute diff once, reuse in match
    $daysAway = (int) now()->startOfDay()->diffInDays($start->startOfDay());

    $timeBadge = match(true) {
        $isFinished          => null,
        $isOngoing           => null,
        $start->isToday()    => 'Today',
        $start->isTomorrow() => 'Tomorrow',
        default              => 'Starting in ' . $daysAway . ' day' . ($daysAway > 1 ? 's' : ''),
    };

    $eventFormat    = $record->event_format ?? 'onsite';
    $hasVirtualSlot = $record->slots->contains(fn($s) => $s->slot_format === 'virtual');
    $hasOnsiteSlot  = $record->slots->contains(fn($s) => $s->slot_format === 'onsite');
    $isHybrid       = ($hasVirtualSlot && $hasOnsiteSlot)
                   || ($hasVirtualSlot && $eventFormat === 'onsite')
                   || ($hasOnsiteSlot  && $eventFormat === 'virtual');

    $locationType = match(true) {
        $isHybrid                  => 'Hybrid',
        $eventFormat === 'virtual' => 'Online',
        default                    => 'Onsite',
    };

    $sameDay = $end && $start->format('Y-m-d') === $end->format('Y-m-d');
    $dateStr = $start->format('M d, Y');
    if ($end && !$sameDay) {
        $dateStr .= ' – ' . $end->format('M d, Y');
    }

    $firstSlot = $record->slots->first();
    $timeStr   = ($firstSlot && $firstSlot->start_time && $firstSlot->end_time)
        ? \Carbon\Carbon::parse($firstSlot->start_time)->format('g:i A')
          . ' – '
          . \Carbon\Carbon::parse($firstSlot->end_time)->format('g:i A')
        : null;

    $category   = $record->program?->name ?? 'Ayala Foundation';
    $location   = $record->location ?? 'Location TBA';
    $detailsUrl = route('filament.admin.resources.events.view', ['record' => $record->id]);

    $canEdit        = \App\Filament\Resources\EventResource::canEdit($record);
    $canDelete      = \App\Filament\Resources\EventResource::canDelete($record);
    $canSetFeatured = !$user->hasActiveRole('Facilitator')
                   && !$user->hasActiveRole('Volunteer')
                   && $user->can('set_featured_event');
@endphp

<div class="bg-white rounded-2xl overflow-hidden flex flex-col hover:shadow-[0_8px_28px_rgba(0,20,50,.14)] transition-shadow duration-200"
     style="width:100%; box-shadow:0 2px 8px rgba(0,20,50,.08), 0 6px 24px rgba(0,20,50,.07);">

    {{-- ── Image ── --}}
    <div class="relative h-44 overflow-hidden bg-gray-200 flex-shrink-0">

        @if($image)
            <img src="{{ $image }}" alt="{{ $record->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        @if($timeBadge)
            <div class="absolute top-2.5 left-2.5">
                <span class="inline-flex items-center bg-white/90 backdrop-blur-sm text-gray-800 text-[11px] font-semibold px-2.5 py-1 rounded-full shadow-sm leading-none">
                    {{ $timeBadge }}
                </span>
            </div>
        @endif

        <div class="absolute bottom-2.5 left-2.5">
            <span class="inline-flex items-center gap-1 bg-black/50 backdrop-blur-sm text-white text-[11px] font-medium px-2.5 py-1 rounded-full leading-none">
                @if($locationType === 'Online')
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/>
                    </svg>
                @elseif($locationType === 'Hybrid')
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/>
                    </svg>
                @else
                    <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                @endif
                {{ $locationType }}
            </span>
        </div>

    </div>

    {{-- ── Body ── --}}
    <div class="flex flex-col flex-1 px-4 pt-4 pb-3 gap-2">

        <p class="text-[11px] font-bold tracking-widest uppercase text-[#f26522] leading-none">
            {{ $category }}
        </p>

        <h3 class="text-[14px] font-bold leading-snug text-gray-900 line-clamp-3">
            {{ $record->title }}
        </h3>

        <div class="flex flex-col gap-1.5 mt-0.5">

            {{-- FIX: {!! !!} so &nbsp; renders as HTML, not literal text --}}
            <div class="flex items-center gap-2 text-gray-500 text-[12px] leading-none">
                <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8"  y1="2" x2="8"  y2="6"/>
                    <line x1="3"  y1="10" x2="21" y2="10"/>
                </svg>
                <span>{!! $dateStr . ($timeStr ? ' &nbsp;·&nbsp; ' . $timeStr : '') !!}</span>
            </div>

            {{-- FIX: min-w-0 on parent + child for proper truncation in flex --}}
            <div class="flex items-start gap-2 min-w-0 text-gray-500 text-[12px] leading-snug">
                <svg class="w-3.5 h-3.5 flex-shrink-0 mt-px text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="truncate min-w-0">{{ $location }}</span>
            </div>

        </div>

    </div>

    {{-- ── Footer ── --}}
    <div class="px-4 pb-4" style="display:flex; align-items:center; gap:8px;">

        <a href="{{ $detailsUrl }}"
           class="bg-[#f26522] hover:bg-[#d4541a] active:bg-[#c04a16] text-white text-[13px] font-semibold rounded-xl transition-colors"
           style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:12px 16px; white-space:nowrap; text-decoration:none;">
            View Details
            <svg style="width:16px; height:16px; flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        @if($canEdit || $canDelete || $canSetFeatured)
            <div x-data="{ open: false }" style="position:relative; flex-shrink:0;">

                <button @click.stop="open = !open" type="button"
                        class="w-10 h-10 flex-shrink-0 flex items-center justify-center
                               rounded-xl border border-gray-200 bg-white
                               text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="5"  cy="12" r="1.5"/>
                        <circle cx="12" cy="12" r="1.5"/>
                        <circle cx="19" cy="12" r="1.5"/>
                    </svg>
                </button>

                <div
                    x-show="open"
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    style="position:absolute; bottom:calc(100% + 6px); right:0; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.13); min-width:180px; z-index:9999;"
                    @click.stop
                >
                    @if($canEdit)
                        <a href="{{ route('filament.admin.resources.events.edit', ['record' => $record->id]) }}"
                           class="flex items-center gap-2.5 px-4 py-3 text-[13px] font-semibold text-[#f26522] hover:bg-gray-50 transition-colors w-full"
                           style="display:flex; text-decoration:none; border-bottom:1px solid #f3f4f6;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.25a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Edit
                        </a>
                    @endif

                    @if($canSetFeatured)
                        <button type="button"
                                onclick="vappToggleFeatured('{{ $record->id }}', {{ $record->is_featured ? 'true' : 'false' }})"
                                class="flex items-center gap-2.5 px-4 py-3 text-[13px] font-semibold text-[#d97706] w-full hover:bg-gray-50 transition-colors bg-none border-none cursor-pointer"
                                style="display:flex; border-bottom:1px solid #f3f4f6;">
                            <svg width="16" height="16" fill="{{ $record->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ $record->is_featured ? 'Remove Featured' : 'Make it Featured' }}
                        </button>
                    @endif

                    @if($canDelete)
                        <button type="button"
                                onclick="vappDelete('{{ $record->id }}')"
                                class="flex items-center gap-2.5 px-4 py-3 text-[13px] font-semibold text-[#dc2626] w-full hover:bg-red-50 transition-colors bg-none border-none cursor-pointer"
                                style="display:flex; border-bottom:1px solid #f3f4f6;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12a2 2 0 002 2h8a2 2 0 002-2V7z"/>
                            </svg>
                            Delete
                        </button>
                    @endif

                    @if(\Illuminate\Support\Facades\Auth::user()->can('export', $record))
                        <button type="button"
                                onclick="vappExport('{{ $record->id }}')"
                                class="flex items-center gap-2.5 px-4 py-3 text-[13px] font-semibold text-[#3b82f6] w-full hover:bg-blue-50 transition-colors bg-none border-none cursor-pointer"
                                style="display:flex;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Export
                        </button>
                    @endif

                </div>

            </div>
        @endif

    </div>

</div>

<script>
if (!window._vappCardHelpersLoaded) {
    window._vappCardHelpersLoaded = true;

    window.vappDelete = function(id) {
        if (!confirm('Delete this event? This cannot be undone.')) return;
        fetch('/admin/events/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => {
            if (r.ok || r.status === 204) {
                document.getElementById('card-' + id)?.closest('.fi-ta-col')?.remove();
                if (window.Livewire) Livewire.dispatch('refresh');
            } else {
                alert('Delete failed — check your permissions.');
            }
        })
        .catch(() => alert('Network error. Please try again.'));
    };

    window.vappToggleFeatured = function(id, isFeatured) {
        if (!confirm(isFeatured ? 'Remove this event from featured?' : 'Mark this event as featured?')) return;
        fetch('/admin/events/' + id + '/toggle-featured', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        })
        .then(r => {
            if (r.ok) {
                if (window.Livewire) Livewire.dispatch('refresh');
            } else {
                alert('Failed — check your permissions.');
            }
        })
        .catch(() => alert('Network error. Please try again.'));
    };

    window.vappExport = function(id) {
        window.location.href = '/admin/events/' + id + '/export-registrants';
    };
}
</script>
