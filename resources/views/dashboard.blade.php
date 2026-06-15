<x-filament-panels::page>
<style>
    .fi-main { margin:0!important; padding:0!important; max-width:100%!important; }
    .fi-page section { padding:0 0 24px 0!important; }

    .d-card { background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(14,32,56,.07),0 4px 16px rgba(14,32,56,.05); }

    /* ── v2 Admin Hero ── */
    .adm-hero {
        display:flex; min-height:180px;
        background:#072b54; position:relative; overflow:hidden;
        border-radius:16px;
    }
    .adm-hero-l {
        flex:0 0 58%; padding:28px 36px;
        position:relative; z-index:2;
        display:flex; flex-direction:column; justify-content:center;
    }
    .adm-hero-r { flex:1; position:relative; overflow:hidden; }
    .adm-hero-r::before {
        content:'';
        position:absolute; left:-28px; top:0; bottom:0; width:56px;
        background:#072b54;
        transform:skewX(-3deg); z-index:1;
    }
    .adm-hero-photo {
        width:100%; height:100%;
        background:linear-gradient(160deg,#1a4a80 0%,#1d6ca2 48%,#207db6 100%);
        display:flex; align-items:center; justify-content:center;
        position:relative;
    }
    .adm-hero-photo::after {
        content:''; position:absolute; inset:0;
        background:
            radial-gradient(ellipse at 65% 45%,rgba(255,255,255,.07) 0%,transparent 55%),
            radial-gradient(ellipse at 30% 75%,rgba(255,255,255,.04) 0%,transparent 40%);
    }
    .adm-hero-gr { font-size:13px; font-weight:600; color:rgba(255,255,255,.55); margin-bottom:8px; letter-spacing:.01em; }
    .adm-hero-tl { font-size:28px; font-weight:800; color:#fff; line-height:1.15; letter-spacing:-.025em; }

    /* ── v2 Stat Cards ── */
    .adm-stats { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
    .adm-stat {
        padding:18px 20px;
        display:flex; align-items:center; gap:14px;
        background:#fff; border-radius:12px;
        box-shadow:0 2px 6px rgba(0,20,50,.07),0 6px 20px rgba(0,20,50,.07);
        border-left:4px solid #f26522;
        transition:box-shadow .18s, transform .15s;
        text-decoration:none; color:inherit;
    }
    .adm-stat:hover { box-shadow:0 10px 32px rgba(0,20,50,.14),0 3px 10px rgba(0,20,50,.07); transform:translateY(-1px); }
    .adm-stat-ico {
        width:44px; height:44px; border-radius:10px; flex-shrink:0;
        display:flex; align-items:center; justify-content:center;
    }
    .adm-stat-ico.or { background:#fff3eb; }
    .adm-stat-ico.bl { background:#e8f0fb; }
    .adm-stat-n { font-size:30px; font-weight:800; color:#f26522; line-height:1; letter-spacing:-.03em; }
    .adm-stat-l { font-size:10px; font-weight:700; color:#072b54; letter-spacing:.09em; text-transform:uppercase; margin-top:4px; }

    /* ── Opportunity section header ── */
    .opp-hd { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; flex-wrap:wrap; gap:10px; }
    .opp-hd-actions { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .btn-create-opp {
        display:inline-flex; align-items:center; gap:6px;
        background:#072b54; color:#fff; border-radius:8px; padding:9px 14px;
        font-size:12px; font-weight:700; letter-spacing:.04em; text-transform:uppercase;
        text-decoration:none; box-shadow:0 2px 8px rgba(7,43,84,.2); transition:background .13s;
        white-space:nowrap;
    }
    .btn-create-opp:hover { background:#0e4f99; }

    /* ── Opportunity grid ── */
    .opp-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }

    /* ── Mobile overrides ── */
    @media (max-width: 640px) {
        .adm-hero { flex-direction:column; min-height:auto; }
        .adm-hero-l { flex:none; padding:24px 20px 20px; }
        .adm-hero-r { display:none; }
        .adm-hero-tl { font-size:22px; }
        .adm-stats { grid-template-columns:1fr 1fr; gap:10px; }
        .adm-stat { padding:14px 14px; gap:10px; }
        .adm-stat-ico { width:36px; height:36px; border-radius:8px; }
        .adm-stat-n { font-size:24px; }
        .adm-stat-l { font-size:9px; }
        .opp-grid { grid-template-columns:1fr; }
        .opp-hd { flex-direction:column; align-items:flex-start; }
        .btn-create-opp { width:100%; justify-content:center; }
    }
    .opp-card { background:#fff; border-radius:12px; border:1px solid #eef0f4; box-shadow:0 1px 3px rgba(14,32,56,.05); display:flex; flex-direction:column; transition:box-shadow .18s, transform .18s; overflow:hidden; }
    .opp-card:hover { box-shadow:0 6px 24px rgba(14,32,56,.12); transform:translateY(-2px); }

    .kebab-wrap { position:relative; }
    .kebab-drop { display:none; position:absolute; top:calc(100% + 4px); right:0; background:#fff; border:1px solid #e5e7eb; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.13); min-width:158px; z-index:50; }
    .kebab-drop.open { display:block; }
    .km-item { display:flex; align-items:center; gap:9px; padding:10px 14px; font-size:13px; font-weight:600; color:#374151; text-decoration:none; background:none; border:none; width:100%; cursor:pointer; transition:background .1s; white-space:nowrap; }
    .km-item:first-child { border-radius:10px 10px 0 0; }
    .km-item:last-child { border-radius:0 0 10px 10px; }
    .km-item:hover { background:#f9fafb; }
    .km-item.danger { color:#dc2626; border-top:1px solid #f3f4f6; }
    .km-item.danger:hover { background:#fff5f5; }
    .km-item.star { color:#d97706; }
    .km-item.star:hover { background:#fffbeb; }
</style>

@php
    $user       = auth()->user();
    $activeRole = $user->activeRole();
    $isAdmin    = $user->isAdminRole();
    $isVolunteer   = $activeRole === 'Volunteer';
    $isFacilitator = $activeRole === 'Facilitator';
    $isPartner     = $activeRole === 'External Partner';
@endphp

<div class="flex flex-col gap-6 p-4 md:p-6">

    {{-- ═══════════════════════════════════
         SECTION 1 — Welcome Header
         Admin/Super Admin: v2 navy hero + floating stat cards
         Volunteer/Partner: existing hero widget (unchanged)
    ═══════════════════════════════════ --}}
    @if($isAdmin)

    {{-- v2 Hero Banner --}}
    <div class="adm-hero">
        {{-- Left: greeting --}}
        <div class="adm-hero-l">
            <div class="adm-hero-gr">Welcome, {{ $volunteer_name }}</div>
            <div class="adm-hero-tl">
                Your involvement<br>is important to us!
            </div>
        </div>
        {{-- Right: illustrated panel --}}
        <div class="adm-hero-r">
            <div class="adm-hero-photo">
                {{-- Volunteer silhouette illustration (matches v2 design) --}}
                <svg style="position:absolute;inset:0;width:100%;height:100%;"
                     viewBox="0 0 320 180" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                    <circle cx="180" cy="90" r="110" fill="rgba(255,255,255,.03)"/>
                    <circle cx="180" cy="90" r="78"  fill="rgba(255,255,255,.03)"/>
                    <circle cx="180" cy="90" r="46"  fill="rgba(255,255,255,.04)"/>
                    {{-- Person 1 --}}
                    <circle cx="128" cy="62" r="18"  fill="rgba(255,255,255,.11)"/>
                    <path d="M100 164 Q100 104 128 100 Q156 104 156 164Z" fill="rgba(255,255,255,.09)"/>
                    {{-- Person 2 (center, largest) --}}
                    <circle cx="180" cy="56" r="22"  fill="rgba(255,255,255,.14)"/>
                    <path d="M148 164 Q148 98 180 94 Q212 98 212 164Z" fill="rgba(255,255,255,.11)"/>
                    {{-- Person 3 --}}
                    <circle cx="234" cy="64" r="17"  fill="rgba(255,255,255,.10)"/>
                    <path d="M208 164 Q208 106 234 102 Q260 106 260 164Z" fill="rgba(255,255,255,.08)"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- v2 Floating Stat Cards --}}
    <div class="adm-stats">
        <a href="{{ route('filament.admin.resources.volunteers.index') }}" class="adm-stat">
            <div class="adm-stat-ico or">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#f26522" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <div class="adm-stat-n">{{ $total_volunteers }}</div>
                <div class="adm-stat-l">Total Volunteers</div>
            </div>
        </a>
        <div class="adm-stat" style="border-left-color:#0e4f99;">
            <div class="adm-stat-ico bl">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1565c4" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7v5l3 2"/>
                </svg>
            </div>
            <div>
                <div class="adm-stat-n" style="color:#0e4f99;">{{ $total_volunteer_hours }}</div>
                <div class="adm-stat-l">Total Volunteer Hours</div>
            </div>
        </div>
    </div>

    @else

    {{-- Non-admin roles: existing hero widget handles the welcome banner --}}
    <div class="d-card overflow-hidden">
        <div class="relative flex flex-col md:flex-row items-stretch"
             style="min-height:170px; background:linear-gradient(130deg,#072b54 0%,#0e4f99 60%,#1565c4 100%);">
            <div class="absolute pointer-events-none"
                 style="top:-50px;right:-30px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
            <div class="absolute pointer-events-none"
                 style="bottom:-40px;right:35%;width:160px;height:160px;border-radius:50%;background:#f26522;opacity:.06;"></div>
            <div class="relative z-10 flex-1 p-7 text-white">
                <p style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.5;margin-bottom:5px;">
                    {{ $activeRole }} Dashboard
                </p>
                <p style="font-family:'Inter',system-ui,sans-serif;font-size:26px;font-weight:800;letter-spacing:-.03em;margin-bottom:4px;">
                    Welcome, {{ $volunteer_name }}
                </p>
                <p style="font-size:15px;opacity:.65;font-weight:500;">
                    Your involvement is important to us!
                </p>
            </div>
        </div>
    </div>

    @endif

    {{-- ═══════════════════════════════════
         SECTION 2 — Opportunities Card
         Shown to ALL roles
    ═══════════════════════════════════ --}}
    <div class="d-card p-6">

        <div class="opp-hd">
            <p style="font-family:'Inter',system-ui,sans-serif;font-size:19px;font-weight:800;letter-spacing:-.02em;color:#0d1f3c;margin:0;">
                Opportunities
            </p>
            <div class="opp-hd-actions">
                @if($isAdmin)
                <a href="{{ route('filament.admin.resources.events.create') }}" class="btn-create-opp">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    Create New Opportunity
                </a>
                @endif
                <a href="{{ route('filament.admin.resources.events.index') }}"
                   style="font-size:13px;font-weight:700;color:#f26522;text-decoration:none;display:flex;align-items:center;gap:3px;white-space:nowrap;">
                    View All
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            </div>
        </div>
        <div class="w-full h-px mb-5" style="background:#eef0f4;"></div>

        @if($opportunities->isEmpty())
            <div class="flex flex-col items-center justify-center py-14 text-center" style="color:#94a3b8;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-40"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                <p style="font-size:14px;font-weight:600;">No upcoming opportunities</p>
                <p style="font-size:12px;margin-top:4px;opacity:.7;">Check back soon for new events.</p>
            </div>
        @else
            <div class="opp-grid">
            @foreach($opportunities as $opp)
            @php
                // ── Same design as event-thumbnail.blade.php (commit 5b56fc1) ──
                $oppBanner   = $opp->getMedia('event-banner-attachments')?->first()?->getUrl();
                $oppStart    = \Carbon\Carbon::parse($opp->start_date);
                $oppEnd      = $opp->end_date ? \Carbon\Carbon::parse($opp->end_date) : null;
                $oppFinished = $oppEnd && now()->isAfter($oppEnd);
                $oppOngoing  = !$oppFinished && now()->isAfter($oppStart);
                $oppDays     = (int) now()->startOfDay()->diffInDays($oppStart->startOfDay());
                $oppTimeBadge = match(true) {
                    $oppFinished           => null,
                    $oppOngoing            => null,
                    $oppStart->isToday()   => 'Today',
                    $oppStart->isTomorrow()=> 'Tomorrow',
                    default                => 'Starting in ' . $oppDays . ' day' . ($oppDays > 1 ? 's' : ''),
                };
                $oppFormat      = $opp->event_format ?? 'onsite';
                $hasVirtSlot    = $opp->slots->contains(fn($s) => $s->slot_format === 'virtual');
                $hasOnStSlot    = $opp->slots->contains(fn($s) => $s->slot_format === 'onsite');
                $oppIsHybrid    = ($hasVirtSlot && $hasOnStSlot)
                               || ($hasVirtSlot && $oppFormat === 'onsite')
                               || ($hasOnStSlot && $oppFormat === 'virtual');
                $oppLocType     = match(true) {
                    $oppIsHybrid             => 'Hybrid',
                    $oppFormat === 'virtual' => 'Online',
                    default                  => 'Onsite',
                };
                $oppSameDay  = $oppEnd && $oppStart->format('Y-m-d') === $oppEnd->format('Y-m-d');
                $oppDateStr  = $oppStart->format('M d, Y');
                if ($oppEnd && !$oppSameDay) { $oppDateStr .= ' – ' . $oppEnd->format('M d, Y'); }
                $oppFirstSlot = $opp->slots->first();
                $oppTimeStr   = ($oppFirstSlot && $oppFirstSlot->start_time && $oppFirstSlot->end_time)
                    ? \Carbon\Carbon::parse($oppFirstSlot->start_time)->format('g:i A')
                      . ' – '
                      . \Carbon\Carbon::parse($oppFirstSlot->end_time)->format('g:i A')
                    : null;
                $oppCategory = $opp->program?->name ?? 'Ayala Foundation';
                $oppLocation = $opp->location ?? 'Location TBA';
                $oppViewUrl  = route('filament.admin.resources.events.view', ['record' => $opp->id]);
                $oppEditUrl  = route('filament.admin.resources.events.edit', ['record' => $opp->id]);
                $oppCanEdit        = $isAdmin && \App\Filament\Resources\EventResource::canEdit($opp);
                $oppCanDelete      = $isAdmin && \App\Filament\Resources\EventResource::canDelete($opp);
                $oppCanSetFeatured = $isAdmin && $user->can('set_featured_event');
                $oppCanExport      = $isAdmin && $user->can('export', $opp);
            @endphp

            {{-- Event card — exact visual design from event-thumbnail.blade.php --}}
            <div class="bg-white rounded-2xl overflow-hidden flex flex-col transition-shadow duration-200"
                 style="box-shadow:0 2px 8px rgba(0,20,50,.08), 0 6px 24px rgba(0,20,50,.07);"
                 onmouseover="this.style.boxShadow='0 8px 28px rgba(0,20,50,.14)'"
                 onmouseout="this.style.boxShadow='0 2px 8px rgba(0,20,50,.08), 0 6px 24px rgba(0,20,50,.07)'">

                {{-- Image --}}
                <div class="relative h-44 overflow-hidden bg-gray-200 flex-shrink-0">
                    @if($oppBanner)
                        <img src="{{ $oppBanner }}" alt="{{ $opp->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center"
                             style="background:linear-gradient(135deg,#e2e8f0 0%,#cbd5e1 100%);">
                            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Time badge --}}
                    @if($oppTimeBadge)
                    <div class="absolute top-2.5 left-2.5">
                        <span class="inline-flex items-center text-gray-800 text-[11px] font-semibold px-2.5 py-1 rounded-full shadow-sm leading-none"
                              style="background:rgba(255,255,255,.9);backdrop-filter:blur(6px);">
                            {{ $oppTimeBadge }}
                        </span>
                    </div>
                    @endif

                    {{-- Location type badge --}}
                    <div class="absolute bottom-2.5 left-2.5">
                        <span class="inline-flex items-center gap-1 text-white text-[11px] font-medium px-2.5 py-1 rounded-full leading-none"
                              style="background:rgba(0,0,0,.5);backdrop-filter:blur(4px);">
                            @if($oppLocType === 'Online')
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
                            @elseif($oppLocType === 'Hybrid')
                                <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4"/></svg>
                            @else
                                <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                            @endif
                            {{ $oppLocType }}
                        </span>
                    </div>
                </div>

                {{-- Body --}}
                <div class="flex flex-col flex-1 px-4 pt-4 pb-3 gap-2">
                    <p class="text-[11px] font-bold tracking-widest uppercase leading-none" style="color:#f26522;">
                        {{ $oppCategory }}
                    </p>
                    <h3 class="text-[14px] font-bold leading-snug text-gray-900 line-clamp-3">
                        {{ $opp->title }}
                    </h3>
                    <div class="flex flex-col gap-1.5 mt-0.5">
                        <div class="flex items-center gap-2 text-gray-500 text-[12px] leading-none">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span>{!! $oppDateStr . ($oppTimeStr ? ' &nbsp;·&nbsp; ' . $oppTimeStr : '') !!}</span>
                        </div>
                        <div class="flex items-start gap-2 min-w-0 text-gray-500 text-[12px] leading-snug">
                            <svg class="w-3.5 h-3.5 flex-shrink-0 mt-px text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="truncate min-w-0">{{ $oppLocation }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-4 pb-4" style="display:flex; align-items:center; gap:8px;">
                    <a href="{{ $oppViewUrl }}"
                       class="bg-[#f26522] hover:bg-[#d4541a] text-white text-[13px] font-semibold rounded-xl transition-colors"
                       style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:12px 16px; white-space:nowrap; text-decoration:none;">
                        View Details
                        <svg style="width:16px;height:16px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @if($oppCanEdit || $oppCanDelete || $oppCanSetFeatured)
                    <div x-data="{ open: false }" style="position:relative; flex-shrink:0;">
                        <button @click.stop="open = !open" type="button"
                                class="w-10 h-10 flex-shrink-0 flex items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             style="position:absolute;bottom:calc(100% + 6px);right:0;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.13);min-width:180px;z-index:9999;"
                             @click.stop>
                            @if($oppCanEdit)
                            <a href="{{ $oppEditUrl }}"
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;font-weight:600;color:#f26522;text-decoration:none;border-bottom:1px solid #f3f4f6;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            @endif
                            @if($oppCanSetFeatured)
                            <button type="button"
                                    onclick="vappToggleFeatured('{{ $opp->id }}', {{ $opp->is_featured ? 'true' : 'false' }})"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;font-weight:600;color:#d97706;background:none;border:none;width:100%;cursor:pointer;border-bottom:1px solid #f3f4f6;">
                                <svg width="13" height="13" fill="{{ $opp->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                {{ $opp->is_featured ? 'Remove Featured' : 'Make it Featured' }}
                            </button>
                            @endif
                            @if($oppCanDelete)
                            <button type="button"
                                    onclick="vappDelete('{{ $opp->id }}')"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;font-weight:600;color:#dc2626;background:none;border:none;width:100%;cursor:pointer;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                Delete
                            </button>
                            @endif
                            @if($oppCanExport)
                            <button type="button"
                                    onclick="vappExport('{{ $opp->id }}')"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;font-weight:600;color:#3b82f6;background:none;border:none;width:100%;cursor:pointer;border-top:1px solid #f3f4f6;">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Export
                            </button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

            </div>
            @endforeach
            </div>
        @endif
    </div>

</div>

<script>
if (!window._vappDashHelpersLoaded) {
    window._vappDashHelpersLoaded = true;

    window.vappDelete = function(id) {
        if (!confirm('Delete this event? This cannot be undone.')) return;
        fetch('/admin/events/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        }).then(r => {
            if (r.ok || r.status === 204) { window.location.reload(); }
            else { alert('Delete failed — check your permissions.'); }
        }).catch(() => alert('Network error. Please try again.'));
    };

    window.vappToggleFeatured = function(id, isFeatured) {
        if (!confirm(isFeatured ? 'Remove from featured?' : 'Mark as featured?')) return;
        fetch('/admin/events/' + id + '/toggle-featured', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        }).then(r => { if (r.ok) window.location.reload(); else alert('Failed — check permissions.'); })
          .catch(() => alert('Network error. Please try again.'));
    };

    window.vappExport = function(id) {
        window.location.href = '/admin/events/' + id + '/export-registrants';
    };
}
</script>
</x-filament-panels::page>
