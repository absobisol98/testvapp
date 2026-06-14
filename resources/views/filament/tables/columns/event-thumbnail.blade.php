@php
    $record = $getRecord();
    $user   = auth()->user();

    $banner = $record->getMedia('event-banner-attachments')?->first()?->getUrl();

    // ── Slot availability ────────────────────────────────────────
    $totalSlots     = $record->slots->sum('total_slots');
    $filledSlots    = $record->registrations->whereNotIn('status_id', [3])->count();
    $availableSlots = max(0, $totalSlots - $filledSlots);
    $isFull         = $availableSlots === 0 && $totalSlots > 0;

    // ── Current user's registration ──────────────────────────────
    $myRegistration = $record->registrations
        ->where('volunteer_id', $user->id)
        ->whereNotIn('status_id', [3])
        ->first();

    // ── Format / type badge ──────────────────────────────────────
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
    $formatBg = match($formatLabel) {
        'Virtual' => '#6366f1',
        'Hybrid'  => '#a855f7',
        default   => '#16a34a',
    };

    // ── Status badge (top-left) ──────────────────────────────────
    $start      = \Carbon\Carbon::parse($record->start_date);
    $end        = $record->end_date ? \Carbon\Carbon::parse($record->end_date) : null;
    $isFinished = $end && now()->isAfter($end);
    $isOngoing  = !$isFinished && now()->isAfter($start);

    $timeBadge = match(true) {
        $isFinished                         => null,
        $isOngoing                          => 'Ongoing',
        $start->isToday()                   => 'Starting today',
        $start->isTomorrow()                => 'Starting tomorrow',
        default => 'Starting in ' . (int)now()->startOfDay()->diffInDays($start->startOfDay()) . ' day' .
                   ((int)now()->startOfDay()->diffInDays($start->startOfDay()) > 1 ? 's' : ''),
    };

    // ── Date / time display ──────────────────────────────────────
    $sameDay   = $end && $start->format('Y-m-d') === $end->format('Y-m-d');
    $dateLabel = $start->format('M d, Y');
    if ($end && !$sameDay) {
        $dateLabel .= ' – ' . $end->format('M d, Y');
    }
    $firstSlot = $record->slots->first();
    $timeLabel = ($firstSlot && $firstSlot->start_time && $firstSlot->end_time)
        ? \Carbon\Carbon::parse($firstSlot->start_time)->format('g:i A')
          . ' – '
          . \Carbon\Carbon::parse($firstSlot->end_time)->format('g:i A')
        : null;

    // ── Permissions ──────────────────────────────────────────────
    $canEdit        = \App\Filament\Resources\EventResource::canEdit($record);
    $canDelete      = \App\Filament\Resources\EventResource::canDelete($record);
    $canSetFeatured = !$user->hasActiveRole('Facilitator')
                   && !$user->hasActiveRole('Volunteer')
                   && $user->can('set_featured_event');
    $canDuplicate   = $canEdit;
    $showMenu       = $canEdit || $canDelete || $canSetFeatured;

    $program   = $record->program?->name ?? 'Ayala Foundation';
    $viewUrl   = route('filament.admin.resources.events.view', ['record' => $record->id]);
    $editUrl   = route('filament.admin.resources.events.edit', ['record' => $record->id]);
    $cardId    = 'card-' . $record->id;
@endphp

{{--
    LAYOUT STRUCTURE
    ┌─────────────────────────────────────────────┐  ← card (overflow:hidden, fixed width)
    │ ┌─────────────────────────────────────────┐ │  ← image (h-[200px])
    │ │ [Status badge]         [Reg badge]      │ │
    │ │              banner image               │ │
    │ │ [Format badge]                          │ │
    │ └─────────────────────────────────────────┘ │
    │  CATEGORY LABEL                             │
    │  Title (2-line clamp)                       │
    │  📅 Date · Time                             │
    │  📍 Location                                │
    │  ● N open          Program name             │
    │ ─────────────────────────────────────────── │
    │  [  View Details →  ]  [ ⋮ ]               │  ← flex row, view-btn flex-1, menu fixed 44px
    └─────────────────────────────────────────────┘
--}}

<div id="{{ $cardId }}"
     class="flex flex-col bg-white rounded-xl border border-gray-200 shadow-sm"
     style="overflow:hidden; min-height:400px; width:100%;">

    {{-- ── IMAGE + BADGES ─────────────────────────────────────── --}}
    <div class="relative flex-shrink-0" style="height:200px; overflow:hidden;">

        <img src="{{ $banner ?? url('img/ayala-foundation-bg.jpg') }}"
             alt="{{ $record->title }}"
             class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">

        {{-- Top-left: time/status badge --}}
        @if($timeBadge)
            <span class="absolute"
                  style="top:10px; left:10px; background:#fff; color:#1e293b; font-size:12px; font-weight:700;
                         padding:5px 11px; border-radius:8px; box-shadow:0 1px 6px rgba(0,0,0,.18);
                         white-space:nowrap; max-width:calc(100% - 100px); overflow:hidden; text-overflow:ellipsis;">
                {{ $timeBadge }}
            </span>
        @endif

        {{-- Top-right: registration status --}}
        @if($myRegistration)
            <span class="absolute"
                  style="top:10px; right:10px; font-size:12px; font-weight:700;
                         padding:5px 11px; border-radius:8px; white-space:nowrap;
                         {{ $myRegistration->status_id == 2
                            ? 'background:#16a34a; color:#fff;'
                            : 'background:#facc15; color:#713f12;' }}">
                {{ $myRegistration->status_id == 2 ? '✓ Registered' : 'Pending' }}
            </span>
        @endif

        {{-- Bottom-left: format badge --}}
        <span class="absolute inline-flex items-center gap-1"
              style="bottom:10px; left:10px; background:{{ $formatBg }}; color:#fff;
                     font-size:11px; font-weight:700; padding:4px 10px;
                     border-radius:6px; white-space:nowrap;">
            <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;">
                @if($formatLabel === 'Virtual')
                    <path d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                @else
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                @endif
            </svg>
            {{ $formatLabel }}
        </span>
    </div>

    {{-- ── BODY ────────────────────────────────────────────────── --}}
    <div class="flex flex-col flex-1 px-4 pt-4 pb-2" style="min-width:0; gap:8px; overflow:hidden;">

        {{-- Category --}}
        <p style="font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
                  color:#d97706; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin:0;">
            {{ $program }}
        </p>

        {{-- Title --}}
        <h3 style="font-size:15px; font-weight:700; line-height:1.3; margin:0;
                   display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
                   overflow:hidden; color:#111827;">
            {{ $record->title }}
        </h3>

        {{-- Date + time --}}
        <div class="flex items-start gap-2" style="min-width:0; overflow:hidden;">
            <svg width="14" height="14" fill="none" stroke="#6b7280" viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0; margin-top:2px;">
                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
            <span style="font-size:13px; color:#6b7280; font-weight:500; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:0;">
                {{ $dateLabel }}{{ $timeLabel ? ' · ' . $timeLabel : '' }}
            </span>
        </div>

        {{-- Location --}}
        <div class="flex items-start gap-2" style="min-width:0; overflow:hidden;">
            <svg width="14" height="14" fill="none" stroke="#6b7280" viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0; margin-top:2px;">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
            </svg>
            <span style="font-size:13px; color:#6b7280; font-weight:500; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; min-width:0;">
                {{ $record->location ?? 'Location TBA' }}
            </span>
        </div>

        {{-- Slots + program footer --}}
        <div class="flex items-center justify-between" style="min-width:0; overflow:hidden; gap:8px;">
            @if($totalSlots > 0)
                <span class="flex items-center gap-1.5" style="font-size:12px; font-weight:600; flex-shrink:0;">
                    <span class="inline-block w-2 h-2 rounded-full" style="background:{{ $isFull ? '#f87171' : '#4ade80' }};"></span>
                    <span style="color:{{ $isFull ? '#ef4444' : '#16a34a' }};">{{ $isFull ? 'Slot full' : "{$availableSlots} open" }}</span>
                </span>
            @else
                <span></span>
            @endif
            <span style="font-size:12px; color:#9ca3af; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:right; min-width:0;">
                {{ $program }}
            </span>
        </div>

        <div style="flex:1;"></div>
    </div>

    {{-- ── CTA ROW ─────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 px-4 pb-4" style="overflow:visible;">

        {{-- Primary: View Details --}}
        <a href="{{ $viewUrl }}"
           class="flex items-center justify-center gap-2"
           style="flex:1; min-width:0; padding:11px 14px; background:#f07a1e; color:#fff;
                  font-weight:700; font-size:14px; border-radius:8px; text-decoration:none;
                  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
                  transition:background .15s; flex-shrink:1;"
           onmouseover="this.style.background='#d9650c'"
           onmouseout="this.style.background='#f07a1e'">
            View Details
            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 stroke-width="2.5" style="flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
        </a>

        {{-- Three-dot menu (admin/editor only) --}}
        @if($showMenu)
            <div x-data="{ open: false }" style="position:relative; flex-shrink:0;">

                {{-- Trigger --}}
                <button @click.stop="open = !open"
                        type="button"
                        style="width:44px; height:44px; border:1.5px solid #e5e7eb; background:#fff;
                               border-radius:8px; display:flex; align-items:center; justify-content:center;
                               cursor:pointer; transition:border-color .15s; flex-shrink:0;"
                        onmouseover="this.style.borderColor='#9ca3af'"
                        onmouseout="this.style.borderColor='#e5e7eb'">
                    <svg width="18" height="18" fill="#374151" viewBox="0 0 24 24">
                        <circle cx="12" cy="5" r="2"/><circle cx="12" cy="12" r="2"/><circle cx="12" cy="19" r="2"/>
                    </svg>
                </button>

                {{-- Dropdown — opens UPWARD to avoid viewport overflow --}}
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     style="position:absolute; bottom:calc(100% + 6px); right:0;
                            background:#fff; border:1px solid #e5e7eb; border-radius:10px;
                            box-shadow:0 8px 24px rgba(0,0,0,.13); min-width:180px; z-index:9999;"
                     @click.stop>

                    @if($canEdit)
                        <a href="{{ $editUrl }}"
                           style="display:flex; align-items:center; gap:10px; padding:11px 16px;
                                  color:#f07a1e; text-decoration:none; font-size:14px; font-weight:600;
                                  border-radius:10px 10px 0 0; transition:background .12s;"
                           onmouseover="this.style.background='#f9fafb'"
                           onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.25a1 1 0 000-1.41l-2.34-2.34a1 1 0 00-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                            </svg>
                            Edit
                        </a>
                    @endif

                    @if($canSetFeatured)
                        <button type="button"
                                onclick="vappToggleFeatured('{{ $record->id }}', {{ $record->is_featured ? 'true' : 'false' }})"
                                style="display:flex; align-items:center; gap:10px; padding:11px 16px; width:100%;
                                       color:#d97706; font-size:14px; font-weight:600; background:none;
                                       border:none; border-top:1px solid #f3f4f6; cursor:pointer; transition:background .12s;"
                                onmouseover="this.style.background='#f9fafb'"
                                onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="{{ $record->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor"
                                 viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0;">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            {{ $record->is_featured ? 'Remove Featured' : 'Make it Featured' }}
                        </button>
                    @endif

                    @if($canDuplicate)
                        <button type="button"
                                onclick="vappDuplicate('{{ $record->id }}')"
                                style="display:flex; align-items:center; gap:10px; padding:11px 16px; width:100%;
                                       color:#6366f1; font-size:14px; font-weight:600; background:none;
                                       border:none; border-top:1px solid #f3f4f6; cursor:pointer; transition:background .12s;"
                                onmouseover="this.style.background='#f9fafb'"
                                onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0;">
                                <rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/>
                            </svg>
                            Duplicate
                        </button>
                    @endif

                    @if($canDelete)
                        <button type="button"
                                onclick="vappDelete('{{ $record->id }}')"
                                style="display:flex; align-items:center; gap:10px; padding:11px 16px; width:100%;
                                       color:#dc2626; font-size:14px; font-weight:600; background:none;
                                       border:none; border-top:1px solid #f3f4f6;
                                       border-radius:0 0 10px 10px; cursor:pointer; transition:background .12s;"
                                onmouseover="this.style.background='#fff5f5'"
                                onmouseout="this.style.background='transparent'">
                            <svg width="15" height="15" fill="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;">
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

{{-- ── JS helpers (defined once via window guard) ──────────────── --}}
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
            }
        }).then(r => {
            if (r.ok || r.status === 204) {
                document.getElementById('card-' + id)?.closest('.fi-ta-col')?.remove();
                if (window.Livewire) Livewire.dispatch('refresh');
            } else {
                alert('Delete failed — check your permissions.');
            }
        }).catch(() => alert('Network error. Please try again.'));
    };

    window.vappToggleFeatured = function(id, isFeatured) {
        const msg = isFeatured ? 'Remove this event from featured?' : 'Mark this event as featured?';
        if (!confirm(msg)) return;
        fetch('/admin/events/' + id + '/toggle-featured', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        }).then(r => {
            if (r.ok) { if (window.Livewire) Livewire.dispatch('refresh'); }
            else { alert('Failed — check your permissions.'); }
        }).catch(() => alert('Network error. Please try again.'));
    };

    window.vappDuplicate = function(id) {
        if (!confirm('Duplicate this event?')) return;
        fetch('/admin/events/' + id + '/duplicate', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        }).then(r => {
            if (r.ok) { if (window.Livewire) Livewire.dispatch('refresh'); }
            else { alert('Failed — check your permissions.'); }
        }).catch(() => alert('Network error. Please try again.'));
    };
}
</script>
