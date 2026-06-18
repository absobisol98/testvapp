<x-filament-panels::page>
<style>
    .fi-main  { margin:0!important; padding:0!important; max-width:100%!important; }
    .fi-page section { padding:0 0 80px 0!important; }
    .fi-header { display:none; }

    /* ── Section padding ── */
    .vp-wrap { padding: 0 16px; }
    @media (min-width: 640px) { .vp-wrap { padding: 0 28px; } }

    /* ── Tab bar: 3 equal sticky columns ── */
    .vp-tabs {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border-bottom: 1px solid #e5e7eb;
        background: #fff;
        position: sticky; top: 0; z-index: 30;
    }
    .vp-tab {
        display: flex; flex-direction: column; align-items: center;
        justify-content: center; gap: 3px;
        padding: 12px 4px; font-size: 11px; font-weight: 600;
        color: #9ca3af; border-bottom: 3px solid transparent;
        cursor: pointer; transition: .18s; background: none;
        border-left: none; border-right: none; border-top: none;
        -webkit-tap-highlight-color: transparent;
    }
    .vp-tab svg { width: 20px; height: 20px; }
    .vp-tab.active { color: #f07a1e; border-bottom-color: #f07a1e; }
    .vp-tab:not(.active):active { background: #f9fafb; }

    /* ── Label / value ── */
    .info-label { font-size: .7rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: .06em; }
    .info-value { font-size: .95rem; color: #111827; margin-top: .2rem; word-break: break-word; }

    /* ── Section title ── */
    .vp-section-title {
        font-size: .875rem; font-weight: 700; color: #374151;
        display: flex; align-items: center; gap: 8px; margin-bottom: 14px;
    }
    .vp-section-title::before {
        content: ''; display: inline-block; width: 4px; height: 18px;
        border-radius: 4px; background: #f07a1e; flex: none;
    }

    /* ── Milestone ── */
    .vp-milestone {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 14px; border-radius: 12px; border: 1px solid #e5e7eb; background: #fff;
    }
    .vp-milestone.done { background: #f0fdf4; border-color: #bbf7d0; }
    .vp-milestone-icon {
        width: 38px; height: 38px; border-radius: 10px; flex: none;
        display: flex; align-items: center; justify-content: center; background: #f3f4f6;
    }
    .vp-milestone.done .vp-milestone-icon { background: #22c55e; }

    /* ── Progress bars ── */
    .vp-bar { height: 8px; background: rgba(255,255,255,.2); border-radius: 99px; overflow: hidden; margin-top: 8px; }
    .vp-bar-fill { height: 100%; background: #f07a1e; border-radius: 99px; transition: width .9s ease; }
    .vp-bar.light { background: #f3f4f6; }

    /* ── Rank card ── */
    .vp-rank-card {
        background: linear-gradient(135deg, #072b54 0%, #0e4f99 100%);
        border-radius: 18px; padding: 22px; color: #fff;
        display: flex; align-items: center; gap: 18px; margin-bottom: 20px;
    }

    /* ── Status badge ── */
    .vp-status-badge {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px;
    }

    /* ── Event card ── */
    .vp-event-card { border: 1px solid #e5e7eb; border-radius: 14px; overflow: hidden; background: #fff; }
    .vp-event-img  { width: 100%; height: 140px; object-fit: cover; display: block; background: #f3f4f6; }

    /* ── Action button ── */
    .vp-btn-sm {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12px; font-weight: 700; padding: 8px 14px; border-radius: 10px;
        text-decoration: none; transition: .15s; flex: 1; justify-content: center;
    }
</style>

@php
    $blue   = '#0e4f99';
    $orange = '#f07a1e';
@endphp

{{-- ─── HERO ───────────────────────────────────────────────────────────────── --}}
<div style="background: linear-gradient(135deg, #072b54 0%, #0e4f99 60%, #1565c4 100%); position: relative; overflow: hidden;">
    <div class="absolute inset-0 opacity-10"
         style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
    <div class="relative pt-8 pb-16">
        <div class="vp-wrap flex flex-col items-center text-center gap-3
                    md:flex-row md:items-center md:text-left md:gap-6">

            {{-- Avatar --}}
            <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-white shadow-xl overflow-hidden flex-shrink-0">
                <img class="w-full h-full object-cover"
                     src="{{ \Filament\Facades\Filament::getUserAvatarUrl($user) }}"
                     alt="{{ $user->name }}">
            </div>

            {{-- Text block --}}
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap justify-center md:justify-start items-center gap-1.5 mb-2">
                    @if($user->volunteer_id)
                        <span class="text-xs font-bold tracking-wider px-2.5 py-0.5 rounded-full bg-white/20 text-white">
                            {{ $user->volunteer_id }}
                        </span>
                    @endif
                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full text-white" style="background:{{ $orange }};">
                        {{ $badges['current_rank']['name'] ?? 'Volunteer' }}
                    </span>
                    @if($canEdit)
                        <a href="{{ $editUrl }}"
                           class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-white/20 hover:bg-white/30 text-white inline-flex items-center gap-1 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                            </svg>
                            Edit Profile
                        </a>
                    @endif
                </div>
                <h1 class="text-xl md:text-2xl font-bold text-white capitalize leading-tight">{{ $user->name }}</h1>
                <p class="text-white/60 text-xs mt-0.5">Member since {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>
    </div>
</div>

{{-- ─── STAT CARDS ─────────────────────────────────────────────────────────── --}}
<div class="-mt-10 mb-5 relative z-10">
    <div class="vp-wrap">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                ['value' => number_format($totalHours, 1), 'label' => 'Total Hours',       'path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['value' => $totalOpportunities,            'label' => 'Events Attended',  'path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                ['value' => $badges['points'],              'label' => 'Volunteer Points', 'path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['value' => $currentStreak,                 'label' => 'Current Streak',   'path' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ] as $s)
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:{{ $blue }}18;">
                    <svg class="w-5 h-5" fill="none" stroke="{{ $blue }}" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['path'] }}"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-lg font-bold leading-none" style="color:{{ $blue }}">{{ $s['value'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5 leading-tight">{{ $s['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ─── MAIN CARD (tabs + panels) ──────────────────────────────────────────── --}}
<div class="vp-wrap">
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Tab bar --}}
    <div class="vp-tabs rounded-t-2xl overflow-hidden">
        <button id="tab-personal"      onclick="switchTab('personal')"      class="vp-tab active">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            Profile
        </button>
        <button id="tab-opportunities" onclick="switchTab('opportunities')" class="vp-tab">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Activities
        </button>
        <button id="tab-badges"        onclick="switchTab('badges')"        class="vp-tab">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Badges
        </button>
    </div>

    {{-- ══ PROFILE TAB ══════════════════════════════════════════════════════ --}}
    <div id="panel-personal" class="p-5 space-y-6">

        <div>
            <p class="vp-section-title">Personal Details</p>
            <div class="bg-gray-50 rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach([
                    ['label' => 'First Name',  'value' => $user->firstname],
                    ['label' => 'Last Name',   'value' => $user->lastname],
                    ['label' => 'Middle Name', 'value' => $user->middle_name ?? '—'],
                    ['label' => 'Birthday',    'value' => $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('M d, Y') : '—'],
                    ['label' => 'Age Range',   'value' => $user->formatted_age_range ?? '—'],
                    ['label' => 'Email',       'value' => $user->email],
                ] as $f)
                <div>
                    <p class="info-label">{{ $f['label'] }}</p>
                    <p class="info-value">{{ $f['value'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div>
            <p class="vp-section-title">Company / Affiliation</p>
            <div class="bg-gray-50 rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="info-label">Affiliation</p>
                    <p class="info-value">
                        {{ $user->affiliate_type_id == 1 ? 'Ayala Employee' : ($user->affiliate_type_id == 2 ? 'External' : '—') }}
                    </p>
                </div>
                @if($user->affiliate_type_id == 1)
                    <div><p class="info-label">Cluster</p><p class="info-value">{{ $user->cluster?->name ?? '—' }}</p></div>
                    <div><p class="info-label">Company</p><p class="info-value">{{ $user->company?->name ?? '—' }}</p></div>
                @else
                    <div><p class="info-label">Company Name</p><p class="info-value">{{ $user->external_company_name ?? '—' }}</p></div>
                @endif
                <div><p class="info-label">Address</p><p class="info-value">{{ $user->company_address ?? '—' }}</p></div>
                <div><p class="info-label">Contact No.</p><p class="info-value">{{ $user->company_contact_number ?? '—' }}</p></div>
            </div>
        </div>

        <div>
            <p class="vp-section-title">Emergency Contact</p>
            <div class="bg-gray-50 rounded-2xl p-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach([
                    ['label' => 'Contact Person',  'value' => $user->emergency_contact_name ?? '—'],
                    ['label' => 'Relationship',    'value' => $user->emergency_contact_relationship ?? '—'],
                    ['label' => 'Contact Number',  'value' => $user->emergency_contact_number ?? '—'],
                ] as $f)
                <div>
                    <p class="info-label">{{ $f['label'] }}</p>
                    <p class="info-value">{{ $f['value'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        @if(!empty($user->skills))
        <div>
            <p class="vp-section-title">Skills</p>
            <div class="flex flex-wrap gap-2 p-4 bg-gray-50 rounded-2xl">
                @foreach((array)$user->skills as $skill)
                    <span class="px-3 py-1 text-sm font-semibold text-white rounded-full" style="background:{{ $blue }}">
                        {{ $skill }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif

    </div>{{-- end panel-personal --}}

    {{-- ══ ACTIVITIES TAB ═══════════════════════════════════════════════════ --}}
    <div id="panel-opportunities" class="p-5 hidden">
        @if($allEvents->isEmpty())
            <div class="flex flex-col items-center justify-center py-14 text-gray-400 text-center">
                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="font-semibold text-gray-600 text-base">No activities yet</p>
                <p class="text-sm text-gray-400 mt-1 max-w-xs">
                    Once you join and attend events, they'll show up here with your QR code and certificate.
                </p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($allEvents as $opportunity)
                @php
                    $att_details = $opportunity->attendees->where('attendee_id', $user->id)->first();
                    $isFinished  = \Carbon\Carbon::parse($opportunity->end_date)->isPast();
                    $isOngoing   = !$isFinished && \Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($opportunity->start_date));
                    $hasApprovedAttendance = $opportunity->attendees
                        ->where('attendee_id', $user->id)
                        ->filter(fn($a) => $a->is_approve && $a->time_in && $a->time_out)
                        ->isNotEmpty();
                    $banner      = $opportunity->getMedia('event-banner-attachments')?->first()?->getUrl()
                                   ?? asset('img/ayala-foundation-bg.jpg');
                @endphp
                <div class="vp-event-card">
                    <img class="vp-event-img" src="{{ $banner }}" alt="{{ $opportunity->title }}">
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <p class="font-bold text-gray-800 leading-snug flex-1">{{ $opportunity->title }}</p>
                            @if($isFinished)
                                <span class="vp-status-badge bg-gray-100 text-gray-500 flex-none">Completed</span>
                            @elseif($isOngoing)
                                <span class="vp-status-badge bg-green-50 text-green-700 flex-none">● Ongoing</span>
                            @else
                                <span class="vp-status-badge bg-amber-50 text-amber-700 flex-none">Upcoming</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-3">
                            <svg class="w-4 h-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M d, Y') }}
                            @if($opportunity->location)
                                <span class="text-gray-300">·</span>
                                <svg class="w-4 h-4 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <span class="truncate">{{ $opportunity->location }}</span>
                            @endif
                        </div>
                        @foreach($opportunity->slots as $slot)
                        @php
                            $slotAtt    = $opportunity->attendees()
                                ->where('attendee_id', $user->id)
                                ->where('slot_type_id', $slot->id)
                                ->first();
                            $slotFormat = $slot->slot_format ?? $opportunity->event_format;
                            $isSelfLog  = in_array($opportunity->event_format, ['virtual', 'hybrid'])
                                          || $slotFormat === 'virtual';
                        @endphp
                        @if($slotAtt)
                        <div class="bg-gray-50 rounded-xl px-3 py-2.5 mb-2">
                            <div class="flex items-center justify-between gap-2 flex-wrap">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">{{ $slot->shift_name }}</p>
                                    <p class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                        – {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                        @if($slotAtt->time_in && $slotAtt->time_out)
                                        @php
                                            $diff = \Carbon\Carbon::parse($slotAtt->time_out)->diff(\Carbon\Carbon::parse($slotAtt->time_in));
                                            $hrs  = $diff->h + ($diff->days * 24);
                                        @endphp
                                        <span class="text-green-600 font-semibold ml-1">· {{ $hrs }}h {{ $diff->i }}m</span>
                                        @endif
                                    </p>
                                </div>
                                @if($slotAtt->is_approve)
                                    <span class="vp-status-badge bg-green-100 text-green-700">✓ Approved</span>
                                @elseif($slotAtt->is_rejected)
                                    <span class="vp-status-badge bg-red-100 text-red-600">Rejected</span>
                                @else
                                    <span class="vp-status-badge bg-amber-50 text-amber-700">Pending</span>
                                @endif
                            </div>
                            {{-- Self-service time log for virtual / hybrid slots --}}
                            @if($isSelfLog && !$slotAtt->is_approve && !$slotAtt->is_rejected)
                            @php
                                $withinWindow = \Carbon\Carbon::now()->between(
                                    \Carbon\Carbon::parse($opportunity->start_date),
                                    \Carbon\Carbon::parse($opportunity->end_date)
                                );
                            @endphp
                            <div class="mt-2 pt-2 border-t border-gray-200">
                                @if(!$slotAtt->time_in)
                                    @if($withinWindow)
                                    <button onclick="logTime({{ $slotAtt->id }}, this)"
                                            class="vp-btn-sm text-white" style="background:{{ $blue }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                        Time In
                                    </button>
                                    @else
                                    <p class="text-xs text-gray-400 text-center">
                                        {{ \Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($opportunity->start_date))
                                            ? 'Available on ' . \Carbon\Carbon::parse($opportunity->start_date)->format('M d, Y h:i A')
                                            : 'Event window has closed' }}
                                    </p>
                                    @endif
                                @elseif(!$slotAtt->time_out)
                                    <p class="text-xs text-green-600 mb-1.5 text-center">
                                        ✓ Timed in at {{ \Carbon\Carbon::parse($slotAtt->time_in)->format('h:i A') }}
                                    </p>
                                    @if($withinWindow)
                                    <button onclick="logTime({{ $slotAtt->id }}, this)"
                                            class="vp-btn-sm text-white" style="background:{{ $orange }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Time Out
                                    </button>
                                    @else
                                    <p class="text-xs text-gray-400 text-center">Event window has closed</p>
                                    @endif
                                @else
                                    <p class="text-xs text-gray-400 text-center">Attendance submitted — awaiting admin approval</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endif
                        @endforeach
                        {{-- QR code block (in-person only) + Certificate --}}
                        @if($att_details)
                        @php
                            $isInPerson = !in_array($opportunity->event_format, ['virtual', 'hybrid']);
                            $qrUrl      = url(\Illuminate\Support\Facades\Storage::url($att_details->id . '-qr-code.png'));
                        @endphp
                        <div class="mt-3 flex flex-col gap-2">
                            @if($isInPerson)
                            <div class="border border-gray-100 rounded-xl p-3 bg-gray-50">
                                <p class="text-xs font-semibold text-gray-500 text-center mb-2 uppercase tracking-wide">Your Time In/Out QR Code</p>
                                <div class="flex justify-center mb-2">
                                    <img src="{{ $qrUrl }}"
                                         alt="Time In/Out QR Code"
                                         class="w-36 h-36 object-contain rounded-lg border border-gray-200 bg-white p-1"
                                         onerror="this.parentElement.style.display='none'">
                                </div>
                                <p class="text-xs text-gray-400 text-center mb-3">Show this to the facilitator to record your time in/out</p>
                                <div class="flex justify-center">
                                    <a href="{{ $qrUrl }}"
                                       onclick="event.preventDefault(); forceDownload(this)"
                                       data-filename="qr-{{ $opportunity->id }}.png"
                                       class="vp-btn-sm text-white" style="background:{{ $blue }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download QR
                                    </a>
                                </div>
                            </div>
                            @endif
                            @if($hasApprovedAttendance)
                            <a href="{{ route('volunteer.certificate', ['attendee_id' => $user->id, 'event_id' => $opportunity->id]) }}"
                               target="_blank"
                               class="vp-btn-sm text-white self-start" style="background:{{ $orange }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Certificate
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>{{-- end panel-opportunities --}}

    {{-- ══ BADGES TAB ════════════════════════════════════════════════════════ --}}
    <div id="panel-badges" class="p-5 hidden">
        @php
            $currentRank = $badges['current_rank'];
            $nextRank    = $badges['next_rank'];
            $points      = $badges['points'];
            $pct = ($nextRank && $nextRank['required'] > $currentRank['required'])
                ? min(round(($points - $currentRank['required']) / ($nextRank['required'] - $currentRank['required']) * 100), 100)
                : 100;
            $rankLadder = [
                ['name' => 'Bronze Volunteer',   'required' => 1250,  'pts' => '1,250'],
                ['name' => 'Silver Volunteer',   'required' => 2500,  'pts' => '2,500'],
                ['name' => 'Gold Volunteer',     'required' => 5000,  'pts' => '5,000'],
                ['name' => 'Platinum Volunteer', 'required' => 10000, 'pts' => '10,000'],
            ];
            $hourMilestones = [
                ['label' => '4 hours',     'target' => 4,    'vp' => 50],
                ['label' => '8 hours',     'target' => 8,    'vp' => 50],
                ['label' => '12 hours',    'target' => 12,   'vp' => 50],
                ['label' => '16 hours',    'target' => 16,   'vp' => 50],
                ['label' => '20 hours',    'target' => 20,   'vp' => 50],
                ['label' => '100 hours',   'target' => 100,  'vp' => 250],
                ['label' => '250 hours',   'target' => 250,  'vp' => 1500],
                ['label' => '500 hours',   'target' => 500,  'vp' => 2500],
                ['label' => '1,000 hours', 'target' => 1000, 'vp' => 5000],
            ];
            $oppMilestones = [
                ['label' => '10 events',  'target' => 10,  'vp' => 50],
                ['label' => '25 events',  'target' => 25,  'vp' => 250],
                ['label' => '50 events',  'target' => 50,  'vp' => 1500],
                ['label' => '100 events', 'target' => 100, 'vp' => 2500],
            ];
        @endphp

        <div class="vp-rank-card">
            <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('medals/'.strtolower(str_replace(' ', '-', $currentRank['name'] ?? 'volunteer')).'.png') }}"
                     class="w-12 h-12 object-contain" onerror="this.style.display='none'"
                     alt="{{ $currentRank['name'] ?? 'Volunteer' }}">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold uppercase tracking-wider text-white/60 mb-0.5">Your Rank</p>
                <p class="text-lg font-bold text-white leading-tight">{{ $currentRank['name'] ?? 'Volunteer' }}</p>
                <p class="text-sm font-semibold mt-0.5" style="color:{{ $orange }}">{{ number_format($points) }} VP</p>
                @if($nextRank)
                <div class="mt-2">
                    <div class="flex justify-between text-xs text-white/60 mb-1">
                        <span>Next: {{ $nextRank['name'] }}</span>
                        <span>{{ $pct }}%</span>
                    </div>
                    <div class="vp-bar"><div class="vp-bar-fill" style="width:{{ $pct }}%"></div></div>
                </div>
                @else
                    <p class="text-xs font-semibold text-green-300 mt-1">🏆 Maximum rank achieved!</p>
                @endif
            </div>
        </div>

        <div class="mb-5">
            <p class="vp-section-title">Rank Ladder</p>
            <div class="flex gap-3 overflow-x-auto pb-1 -mx-1 px-1">
                @foreach($rankLadder as $rank)
                @php $unlocked = $points >= $rank['required']; @endphp
                <div class="flex-shrink-0 w-32 border rounded-2xl p-3 text-center {{ $unlocked ? 'border-orange-200 bg-orange-50/40' : 'border-gray-100 bg-white' }}">
                    <div class="w-12 h-12 mx-auto mb-2 flex items-center justify-center rounded-full {{ $unlocked ? 'bg-orange-50' : 'bg-gray-100' }}">
                        <img src="{{ asset('medals/'.strtolower(str_replace(' ', '-', $rank['name'])).'.png') }}"
                             class="w-10 h-10 object-contain {{ $unlocked ? '' : 'grayscale opacity-40' }}"
                             onerror="this.style.display='none'" alt="{{ $rank['name'] }}">
                    </div>
                    <p class="text-xs font-bold leading-tight {{ $unlocked ? 'text-orange-600' : 'text-gray-400' }}">{{ $rank['name'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $rank['pts'] }} VP</p>
                    @if($unlocked)
                        <span class="mt-1.5 inline-flex items-center gap-1 text-xs font-semibold text-green-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            Unlocked
                        </span>
                    @else
                        <span class="mt-1.5 inline-flex items-center gap-0.5 text-xs text-gray-400">🔒 {{ $rank['pts'] }} VP</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="mb-5">
            <p class="vp-section-title">Hour Milestones</p>
            <div class="space-y-2">
                @foreach($hourMilestones as $m)
                @php
                    $done = $totalHours >= $m['target'];
                    $prog = $m['target'] > 0 ? min(round($totalHours / $m['target'] * 100), 100) : 0;
                @endphp
                <div class="vp-milestone {{ $done ? 'done' : '' }}">
                    <div class="vp-milestone-icon">
                        @if($done)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center gap-2">
                            <p class="text-sm font-semibold {{ $done ? 'text-green-700' : 'text-gray-700' }}">{{ $m['label'] }}</p>
                            <span class="text-xs font-bold flex-none {{ $done ? 'text-green-500' : 'text-gray-400' }}">+{{ number_format($m['vp']) }} VP</span>
                        </div>
                        @if(!$done)
                            <div class="vp-bar light mt-1.5"><div class="vp-bar-fill" style="width:{{ $prog }}%"></div></div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ round($totalHours, 1) }} / {{ $m['target'] }} hrs</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div>
            <p class="vp-section-title">Event Milestones</p>
            <div class="space-y-2">
                <div class="vp-milestone done">
                    <div class="vp-milestone-icon">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="flex-1 flex justify-between items-center gap-2">
                        <p class="text-sm font-semibold text-green-700">Account created</p>
                        <span class="text-xs font-bold text-green-500">+50 VP</span>
                    </div>
                </div>
                @php $firstDone = $totalOpportunities >= 1; @endphp
                <div class="vp-milestone {{ $firstDone ? 'done' : '' }}">
                    <div class="vp-milestone-icon">
                        @if($firstDone)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center gap-2">
                            <p class="text-sm font-semibold {{ $firstDone ? 'text-green-700' : 'text-gray-700' }}">First event</p>
                            <span class="text-xs font-bold {{ $firstDone ? 'text-green-500' : 'text-gray-400' }}">+50 VP</span>
                        </div>
                        @if(!$firstDone)<p class="text-xs text-gray-400 mt-0.5">{{ $totalOpportunities }} / 1</p>@endif
                    </div>
                </div>
                @foreach($oppMilestones as $m)
                @php
                    $done = $totalOpportunities >= $m['target'];
                    $prog = $m['target'] > 0 ? min(round($totalOpportunities / $m['target'] * 100), 100) : 0;
                @endphp
                <div class="vp-milestone {{ $done ? 'done' : '' }}">
                    <div class="vp-milestone-icon">
                        @if($done)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center gap-2">
                            <p class="text-sm font-semibold {{ $done ? 'text-green-700' : 'text-gray-700' }}">{{ $m['label'] }}</p>
                            <span class="text-xs font-bold flex-none {{ $done ? 'text-green-500' : 'text-gray-400' }}">+{{ number_format($m['vp']) }} VP</span>
                        </div>
                        @if(!$done)
                            <div class="vp-bar light mt-1.5"><div class="vp-bar-fill" style="width:{{ $prog }}%"></div></div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $totalOpportunities }} / {{ $m['target'] }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>{{-- end panel-badges --}}

</div>{{-- end main card --}}
</div>{{-- end vp-wrap --}}

<script>
function switchTab(tab) {
    ['personal','opportunities','badges'].forEach(function(t) {
        document.getElementById('panel-' + t).classList.add('hidden');
        document.getElementById('tab-'   + t).classList.remove('active');
    });
    document.getElementById('panel-' + tab).classList.remove('hidden');
    document.getElementById('tab-'   + tab).classList.add('active');
    document.querySelector('.vp-tabs').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function logTime(attendeeId, btn) {
    btn.disabled = true;
    var original = btn.innerHTML;
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>';
    fetch('/volunteer/time-log/' + attendeeId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
        },
        body: JSON.stringify({})
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.status === 'success') {
            window.location.reload();
        } else {
            alert(data.message || 'Something went wrong.');
            btn.disabled = false;
            btn.innerHTML = original;
        }
    })
    .catch(function() {
        alert('Network error. Please try again.');
        btn.disabled = false;
        btn.innerHTML = original;
    });
}

function forceDownload(link) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', link.href, true);
    xhr.responseType = 'blob';
    xhr.onload = function() {
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(xhr.response);
        a.download = link.getAttribute('data-filename');
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };
    xhr.send();
}
</script>

</x-filament-panels::page>
