@php
    $record = $getRecord();
    $user   = auth()->user();

    $banner = $record->getMedia('event-banner-attachments')?->first()?->getUrl();

    // Slot availability
    $totalSlots     = $record->slots->sum('total_slots');
    $filledSlots    = $record->registrations->whereNotIn('status_id', [3])->count();
    $availableSlots = max(0, $totalSlots - $filledSlots);
    $isFull         = $availableSlots === 0 && $totalSlots > 0;

    // Current user's registration
    $myRegistration = $record->registrations
        ->where('volunteer_id', $user->id)
        ->whereNotIn('status_id', [3])
        ->first();

    // Event format / type badge
    $eventFormat    = $record->event_format ?? 'onsite';
    $hasVirtualSlot = $record->slots->contains(fn($s) => $s->slot_format === 'virtual');
    $hasOnsiteSlot  = $record->slots->contains(fn($s) => $s->slot_format === 'onsite');
    $isHybrid       = ($hasVirtualSlot && $hasOnsiteSlot)
                   || ($hasVirtualSlot && $eventFormat === 'onsite')
                   || ($hasOnsiteSlot  && $eventFormat === 'virtual');

    $formatLabel = match(true) {
        $isHybrid                  => 'Hybrid',
        $eventFormat === 'virtual' => 'Virtual',
        default                    => 'Onsite',
    };

    // "Starting in X days" logic
    $start = \Carbon\Carbon::parse($record->start_date);
    $now   = now();
    if ($now->isAfter($record->end_date)) {
        $timeBadge = null; // Completed — no time badge
    } elseif ($now->isAfter($start)) {
        $timeBadge = 'Ongoing';
    } elseif ($start->isToday()) {
        $timeBadge = 'Starting today';
    } elseif ($start->isTomorrow()) {
        $timeBadge = 'Starting tomorrow';
    } else {
        $days = (int) $now->startOfDay()->diffInDays($start->startOfDay());
        $timeBadge = "Starting in {$days} day" . ($days > 1 ? 's' : '');
    }

    // Date + time display
    $sameDay   = $start->format('Y-m-d') === \Carbon\Carbon::parse($record->end_date)->format('Y-m-d');
    $dateLabel = $start->format('M d, Y');
    if (!$sameDay) {
        $dateLabel .= ' – ' . \Carbon\Carbon::parse($record->end_date)->format('M d, Y');
    }

    // Time range from first slot
    $firstSlot  = $record->slots->first();
    $timeLabel  = null;
    if ($firstSlot && $firstSlot->start_time && $firstSlot->end_time) {
        $timeLabel = \Carbon\Carbon::parse($firstSlot->start_time)->format('g:i A')
                   . ' – '
                   . \Carbon\Carbon::parse($firstSlot->end_time)->format('g:i A');
    }

    // Program / category
    $program = $record->program?->name ?? 'Ayala Foundation';

    // Permissions
    $canEdit      = \App\Filament\Resources\EventResource::canEdit($record);
    $canDelete    = \App\Filament\Resources\EventResource::canDelete($record);
    $canSetFeatured = !$user->hasActiveRole('Facilitator')
                   && !$user->hasActiveRole('Volunteer')
                   && $user->can('set_featured_event');

    $viewUrl   = route('filament.admin.resources.events.view',   ['record' => $record->id]);
    $editUrl   = route('filament.admin.resources.events.edit',   ['record' => $record->id]);
    $cardId    = 'card-' . $record->id;
@endphp

<div id="{{ $cardId }}" class="relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-visible flex flex-col"
     style="min-height:420px;">

    {{-- ── IMAGE ─────────────────────────────────────────────────── --}}
    <div class="relative flex-shrink-0" style="height:200px; overflow:hidden; border-radius:12px 12px 0 0;">

        {{-- Banner image --}}
        <img class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
             src="{{ $banner ?? url('img/ayala-foundation-bg.jpg') }}"
             alt="{{ $record->title }}">

        {{-- Top-left: "Starting in X days" / "Ongoing" --}}
        @if($timeBadge)
            <div class="absolute" style="top:12px; left:12px;">
                <span class="inline-block bg-white text-gray-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm"
                      style="line-height:1.2;">
                    {{ $timeBadge }}
                </span>
            </div>
        @endif

        {{-- Top-right: ✓ Registered / Pending --}}
        @if($myRegistration)
            <div class="absolute" style="top:12px; right:12px;">
                @if($myRegistration->status_id == 2)
                    <span class="inline-block bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                        ✓ Registered
                    </span>
                @elseif($myRegistration->status_id == 1)
                    <span class="inline-block bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm">
                        Pending
                    </span>
                @endif
            </div>
        @endif

        {{-- Bottom-left: Onsite / Virtual / Hybrid --}}
        <div class="absolute" style="bottom:12px; left:12px;">
            <span class="inline-flex items-center gap-1 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-sm"
                  style="background:{{ $formatLabel === 'Virtual' ? '#6366f1' : ($formatLabel === 'Hybrid' ? '#a855f7' : '#16a34a') }};">
                <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24">
                    @if($formatLabel === 'Virtual')
                        <path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                    @else
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    @endif
                </svg>
                {{ $formatLabel }}
            </span>
        </div>
    </div>

    {{-- ── BODY ──────────────────────────────────────────────────── --}}
    <div class="flex flex-col flex-grow px-4 pt-4 pb-2 gap-2">

        {{-- Program / Category label --}}
        <p class="text-xs font-bold uppercase tracking-wider" style="color:#d97706; letter-spacing:.08em;">
            {{ $program }}
        </p>

        {{-- Title --}}
        <h3 class="font-bold text-gray-900 leading-snug line-clamp-2"
            style="font-size:15px; margin:0;">
            {{ $record->title }}
        </h3>

        {{-- Date row --}}
        <div class="flex items-center gap-2" style="color:#6b7280; font-size:13px; font-weight:500;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" flex-shrink="0" class="flex-shrink-0">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span>{{ $dateLabel }}
                @if($timeLabel)
                    &nbsp;·&nbsp; {{ $timeLabel }}
                @endif
            </span>
        </div>

        {{-- Location row --}}
        <div class="flex items-center gap-2" style="color:#6b7280; font-size:13px; font-weight:500;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" class="flex-shrink-0">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>
            <span class="truncate" style="max-width:220px;">
                {{ $record->location ?? 'Location TBA' }}
            </span>
        </div>

        {{-- Slots badge --}}
        @if($totalSlots > 0)
            <div class="flex items-center gap-1.5" style="font-size:12px; font-weight:600;">
                <span class="inline-block w-2 h-2 rounded-full {{ $isFull ? 'bg-red-400' : 'bg-green-400' }}"></span>
                <span class="{{ $isFull ? 'text-red-500' : 'text-green-600' }}">
                    {{ $isFull ? 'Fully booked' : "{$availableSlots} open" }}
                </span>
                @if($record->program)
                    <span class="ml-auto text-gray-400 truncate" style="max-width:120px;">
                        {{ $record->program->name }}
                    </span>
                @endif
            </div>
        @endif

        <div class="flex-grow"></div>
    </div>

    {{-- ── CTA FOOTER ────────────────────────────────────────────── --}}
    <div class="px-4 pb-4 flex items-center gap-2">

        {{-- Primary: View Details (full-width orange button) --}}
        <a href="{{ $viewUrl }}"
           class="flex-1 flex items-center justify-center gap-2 font-bold text-white rounded-lg transition-all duration-150"
           style="background:#f07a1e; padding:11px 16px; font-size:14px; text-decoration:none;"
           onmouseover="this.style.background='#d9650c'"
           onmouseout="this.style.background='#f07a1e'">
            View Details
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
        </a>

        {{-- Three-dot dropdown (admin only) --}}
        @if($canEdit || $canDelete || $canSetFeatured)
            <div x-data="{ open: false }" class="relative">
                <button @click.stop="open = !open"
                        class="flex items-center justify-center border border-gray-200 bg-white rounded-lg hover:border-gray-400 transition-all duration-150"
                        style="width:44px; height:44px; cursor:pointer;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="5" r="2"/>
                        <circle cx="12" cy="12" r="2"/>
                        <circle cx="12" cy="19" r="2"/>
                    </svg>
                </button>

                {{-- Dropdown panel --}}
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute bottom-full right-0 mb-2 bg-white border border-gray-200 rounded-xl shadow-xl z-50"
                     style="min-width:170px;"
                     @click.stop>

                    @if($canEdit)
                        <a href="{{ $editUrl }}"
                           class="flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:bg-gray-50 transition-colors rounded-t-xl"
                           style="color:#f07a1e; text-decoration:none;">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Edit
                        </a>
                    @endif

                    @if($canSetFeatured)
                        <button type="button"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:bg-gray-50 transition-colors border-t border-gray-100"
                                style="color:#d97706; cursor:pointer; background:none; border-left:none; border-right:none; border-bottom:none;"
                                onclick="confirmFeatured('{{ $record->id }}', {{ $record->is_featured ? 'true' : 'false' }})">
                            <svg width="16" height="16" fill="{{ $record->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ $record->is_featured ? 'Remove Featured' : 'Make Featured' }}
                        </button>
                    @endif

                    @if($canDelete)
                        <button type="button"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold hover:bg-red-50 transition-colors rounded-b-xl border-t border-gray-100"
                                style="color:#dc2626; cursor:pointer; background:none; border-left:none; border-right:none; border-bottom:none;"
                                onclick="confirmDelete('{{ $record->id }}')">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 9v10H8V9h8m-1.5-6h-5l-1 1H5v2h14V4h-3.5l-1-1zM18 7H6v12a2 2 0 002 2h8a2 2 0 002-2V7z"/>
                            </svg>
                            Delete
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- JS helpers for confirm actions --}}
<script>
function confirmDelete(eventId) {
    if (!confirm('Are you sure you want to delete this event? This cannot be undone.')) return;
    fetch('/admin/events/' + eventId, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }).then(r => {
        if (r.ok || r.status === 204) {
            document.getElementById('card-' + eventId)?.remove();
            // Trigger Livewire refresh if available
            if (window.Livewire) Livewire.dispatch('refresh');
        } else {
            alert('Failed to delete. You may not have permission.');
        }
    });
}

function confirmFeatured(eventId, isFeatured) {
    const msg = isFeatured
        ? 'Remove this event from featured?'
        : 'Mark this event as featured?';
    if (!confirm(msg)) return;
    fetch('/admin/events/' + eventId + '/toggle-featured', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        }
    }).then(r => {
        if (r.ok) {
            if (window.Livewire) Livewire.dispatch('refresh');
        } else {
            alert('Failed to update. Check your permissions.');
        }
    });
}
</script>
