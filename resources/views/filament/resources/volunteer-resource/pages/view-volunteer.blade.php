<x-filament-panels::page>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<style>
    .fi-main { margin:0!important; padding:0!important; max-width:100%!important; }
    .fi-page section { padding:0 0 48px 0!important; }
    .fi-header { display:none; }
    .profile-tab-btn { transition: all .2s; }
    .profile-tab-btn.active { color:#ff7b00; border-bottom:3px solid #ff7b00; font-weight:600; }
    .profile-tab-btn:not(.active) { color:#6B7280; border-bottom:3px solid transparent; }
    .info-label { font-size:.75rem; font-weight:500; color:#9CA3AF; text-transform:uppercase; letter-spacing:.05em; }
    .info-value { font-size:1rem; color:#111827; margin-top:.2rem; }
</style>

@php
    $primaryBlue = '#0433ff';
    $accentOrange = '#ff7b00';
@endphp

{{-- ── Hero Banner ─────────────────────────────────────────────────────── --}}
<div class="w-full" style="background: linear-gradient(135deg, #003d75 0%, #005096 60%, #1A67B1 100%); min-height: 180px; position: relative;">
    {{-- subtle pattern overlay --}}
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 1px 1px, white 1px, transparent 0); background-size: 28px 28px;"></div>
    <div class="relative px-8 pt-10 pb-20 flex items-end gap-6">
        <div class="w-28 h-28 rounded-full border-4 border-white shadow-xl overflow-hidden flex-shrink-0">
            <img class="w-full h-full object-cover"
                 src="{{ \Filament\Facades\Filament::getUserAvatarUrl($user) }}"
                 alt="{{ $user->name }}">
        </div>
        <div class="pb-2 text-white">
            <div class="flex items-center gap-2 flex-wrap mb-1">
                @if($user->volunteer_id)
                    <span class="text-xs font-bold tracking-widest px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-sm">
                        {{ $user->volunteer_id }}
                    </span>
                @endif
                <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full" style="background:{{ $accentOrange }};">
                    {{ $badges['current_rank']['name'] ?? 'Volunteer' }}
                </span>
                @if($canEdit)
                    <a href="{{ $editUrl }}"
                       class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-white/20 hover:bg-white/30 transition flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                        </svg>
                        Edit Profile
                    </a>
                @endif
            </div>
            <h1 class="text-2xl font-bold capitalize">{{ $user->name }}</h1>
            <p class="text-white/70 text-sm">Member since {{ $user->created_at->format('F Y') }}</p>
        </div>
    </div>
</div>

{{-- ── Stat Cards (overlap hero) ───────────────────────────────────────── --}}
<div class="px-8 -mt-10 mb-6 relative z-10">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $statItems = [
            ['value' => number_format($totalHours, 1), 'label' => 'Total Hours',      'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['value' => $totalOpportunities,            'label' => 'Events Attended', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
            ['value' => $badges['points'],              'label' => 'Volunteer Points', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ['value' => $currentStreak,                 'label' => 'Current Streak',  'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>'],
        ];
        @endphp
        @foreach($statItems as $stat)
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-4 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:{{ $primaryBlue }}10">
                <svg class="w-5 h-5" fill="none" stroke="{{ $primaryBlue }}" viewBox="0 0 24 24">{!! $stat['svg'] !!}</svg>
            </div>
            <div>
                <p class="text-xl font-bold" style="color:{{ $primaryBlue }}">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-400">{{ $stat['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ── Tab Bar ──────────────────────────────────────────────────────────── --}}
<div class="px-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Tab navigation --}}
        <div class="border-b border-gray-100 flex overflow-x-auto">
            @foreach([
                ['id' => 'personal',      'label' => 'My Volunteer Profile', 'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>'],
                ['id' => 'opportunities', 'label' => 'My Opportunities',     'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                ['id' => 'badges',        'label' => 'Badges & Rank',        'svg' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>'],
            ] as $tab)
            <button
                id="tab-{{ $tab['id'] }}"
                onclick="switchTab('{{ $tab['id'] }}')"
                class="profile-tab-btn flex-shrink-0 flex items-center gap-2 px-6 py-4 text-sm whitespace-nowrap {{ $tab['id'] === 'personal' ? 'active' : '' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $tab['svg'] !!}</svg>
                {{ $tab['label'] }}
            </button>
            @endforeach
        </div>

        {{-- ── OPPORTUNITIES TAB ────────────────────────────────────────── --}}
        <div id="panel-opportunities" class="p-6 hidden">
            @if($allEvents->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <svg class="w-14 h-14 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    <p class="text-base font-medium">No opportunities attended yet</p>
                    <p class="text-sm mt-1">Registered events will appear here once attendance is confirmed.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($allEvents as $opportunity)
                    @php
                        $att_details = $opportunity->attendees->where('attendee_id', $user->id)->first();
                        $isFinished  = \Carbon\Carbon::parse($opportunity->end_date)->isPast();
                    @endphp
                    <div class="flex flex-col md:flex-row gap-4 p-4 rounded-xl border border-gray-100 hover:border-gray-200 hover:shadow-sm transition">
                        {{-- Event image --}}
                        <div class="w-full md:w-32 h-24 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                            <img class="w-full h-full object-cover"
                                 src="{{ $opportunity->getMedia('event-banner-attachments')?->first()?->getUrl() ?? asset('img/ayala-foundation-bg.jpg') }}"
                                 alt="{{ $opportunity->title }}">
                        </div>

                        {{-- Event info --}}
                        <div class="flex-grow">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $opportunity->title }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M d, Y') }}
                                        @if($opportunity->location) · {{ $opportunity->location }} @endif
                                    </p>
                                </div>
                                @if($isFinished)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">Completed</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700">Upcoming</span>
                                @endif
                            </div>

                            {{-- Slots --}}
                            @foreach($opportunity->slots as $slot)
                            @php
                                $slotAtt = $opportunity->attendees()
                                    ->where('attendee_id', $user->id)
                                    ->where('slot_type_id', $slot->id)
                                    ->first();
                            @endphp
                            @if($slotAtt)
                            <div class="mt-2 flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2 text-sm flex-wrap gap-2">
                                <div>
                                    <span class="font-medium text-gray-700">{{ $slot->shift_name }}</span>
                                    <span class="text-gray-400 mx-1">·</span>
                                    <span class="text-gray-500 text-xs">
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                        – {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                    </span>
                                    @if($slotAtt->time_in && $slotAtt->time_out)
                                    @php
                                        $diff = \Carbon\Carbon::parse($slotAtt->time_out)->diff(\Carbon\Carbon::parse($slotAtt->time_in));
                                        $hrs  = $diff->h + ($diff->days * 24);
                                    @endphp
                                    <span class="ml-2 text-green-600 font-semibold">{{ $hrs }}h {{ $diff->i }}m</span>
                                    @endif
                                </div>
                                @if($slotAtt->is_approve)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">Approved</span>
                                @elseif($slotAtt->is_rejected)
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-medium">Rejected</span>
                                @else
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-yellow-100 text-yellow-700 font-medium">Pending</span>
                                @endif
                            </div>
                            @endif
                            @endforeach
                        </div>

                        {{-- Actions --}}
                        @if($att_details)
                        <div class="flex md:flex-col gap-2 flex-shrink-0 justify-end">
                            <a href="{{ secure_asset(\Illuminate\Support\Facades\Storage::url($att_details->id . '-qr-code.png')) }}"
                               onclick="event.preventDefault(); forceDownload(this)"
                               data-filename="qr-code.png"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white rounded-lg hover:opacity-90 transition"
                               style="background:{{ $primaryBlue }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                QR Code
                            </a>
                            @if($isFinished)
                            <a href="{{ route('volunteer.certificate', ['attendee_id' => $user->id, 'event_id' => $opportunity->id]) }}"
                               target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-white rounded-lg hover:opacity-90 transition"
                               style="background:{{ $accentOrange }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                Certificate
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── PERSONAL INFO TAB ────────────────────────────────────────── --}}
        <div id="panel-personal" class="p-6">

            {{-- Name & Contact --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full inline-block" style="background:{{ $accentOrange }}"></span>
                    Personal Details
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-5 p-5 bg-gray-50 rounded-xl">
                    @foreach([
                        ['label' => 'First Name',   'value' => $user->firstname],
                        ['label' => 'Middle Name',  'value' => $user->middle_name ?? '—'],
                        ['label' => 'Last Name',    'value' => $user->lastname],
                        ['label' => 'Email',        'value' => $user->email],
                        ['label' => 'Birthday',     'value' => $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('F d, Y') : '—'],
                        ['label' => 'Age Range',    'value' => $user->formatted_age_range ?? '—'],
                    ] as $field)
                    <div>
                        <p class="info-label">{{ $field['label'] }}</p>
                        <p class="info-value">{{ $field['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Company / Affiliation --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full inline-block" style="background:{{ $accentOrange }}"></span>
                    Company / Affiliation
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 p-5 bg-gray-50 rounded-xl">
                    <div>
                        <p class="info-label">Affiliation Type</p>
                        <p class="info-value">
                            {{ $user->affiliate_type_id == 1 ? 'Ayala Employee' : ($user->affiliate_type_id == 2 ? 'External' : '—') }}
                        </p>
                    </div>
                    @if($user->affiliate_type_id == 1)
                    <div>
                        <p class="info-label">Cluster</p>
                        <p class="info-value">{{ $user->cluster?->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="info-label">Company</p>
                        <p class="info-value">{{ $user->company?->name ?? '—' }}</p>
                    </div>
                    @else
                    <div>
                        <p class="info-label">Company Name</p>
                        <p class="info-value">{{ $user->external_company_name ?? '—' }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="info-label">Address</p>
                        <p class="info-value">{{ $user->company_address ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="info-label">Contact Number</p>
                        <p class="info-value">{{ $user->company_contact_number ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Emergency Contact --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full inline-block" style="background:{{ $accentOrange }}"></span>
                    Emergency Contact
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5 p-5 bg-gray-50 rounded-xl">
                    @foreach([
                        ['label' => 'Contact Person',   'value' => $user->emergency_contact_name ?? '—'],
                        ['label' => 'Relationship',     'value' => $user->emergency_contact_relationship ?? '—'],
                        ['label' => 'Contact Number',   'value' => $user->emergency_contact_number ?? '—'],
                    ] as $field)
                    <div>
                        <p class="info-label">{{ $field['label'] }}</p>
                        <p class="info-value">{{ $field['value'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Skills --}}
            @if(!empty($user->skills))
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full inline-block" style="background:{{ $accentOrange }}"></span>
                    Skills
                </h3>
                <div class="p-5 bg-gray-50 rounded-xl flex flex-wrap gap-2">
                    @foreach((array)$user->skills as $skill)
                    <span class="px-3 py-1 text-sm font-medium text-white rounded-full" style="background:{{ $primaryBlue }}">
                        {{ $skill }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Activity Summary --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <span class="w-1 h-5 rounded-full inline-block" style="background:{{ $accentOrange }}"></span>
                    Activity Summary
                </h3>
                <div class="grid grid-cols-3 gap-4">
                    @foreach([
                        ['label' => 'Total Hours',          'value' => number_format($totalHours, 1)],
                        ['label' => 'Events Attended',      'value' => $totalOpportunities],
                        ['label' => 'Participation Rate',   'value' => $participationFrequency],
                    ] as $s)
                    <div class="p-4 bg-gray-50 rounded-xl text-center">
                        <p class="text-xl font-bold" style="color:{{ $primaryBlue }}">{{ $s['value'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $s['label'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── BADGES TAB ───────────────────────────────────────────────── --}}
        <div id="panel-badges" class="p-6 hidden">
            @php
                $currentRank = $badges['current_rank'];
                $nextRank    = $badges['next_rank'];
                $points      = $badges['points'];
                $pct = ($nextRank && $nextRank['required'] > $currentRank['required'])
                    ? min(round(($points - $currentRank['required']) / ($nextRank['required'] - $currentRank['required']) * 100), 100)
                    : 100;

                // Build completed + available challenges lists
                $completedChallenges = [];
                $availableChallenges = [];

                // Account created
                $completedChallenges[] = ['label' => 'Account created', 'vp' => 50];

                // First opportunity
                if ($totalOpportunities >= 1) {
                    $completedChallenges[] = ['label' => 'First opportunity attended', 'vp' => 50];
                } else {
                    $availableChallenges[] = ['label' => 'Attend your first opportunity', 'vp' => 50,
                        'current' => $totalOpportunities, 'target' => 1, 'unit' => ''];
                }

                // Hour milestones
                foreach ([4=>50, 8=>50, 12=>50, 16=>50, 20=>50, 100=>250, 250=>1500, 500=>2500, 1000=>5000] as $hrs => $vp) {
                    if ($totalHours >= $hrs) {
                        $completedChallenges[] = ['label' => 'Complete '.$hrs.' volunteer hours', 'vp' => $vp];
                    } else {
                        $availableChallenges[] = ['label' => 'Complete '.$hrs.' volunteer hours', 'vp' => $vp,
                            'current' => round($totalHours, 1), 'target' => $hrs, 'unit' => 'hrs'];
                        break;
                    }
                }

                // Opportunity milestones
                foreach ([10=>50, 25=>250, 50=>1500, 100=>2500] as $opp => $vp) {
                    if ($totalOpportunities >= $opp) {
                        $completedChallenges[] = ['label' => 'Attend '.$opp.' opportunities', 'vp' => $vp];
                    } else {
                        $availableChallenges[] = ['label' => 'Attend '.$opp.' opportunities', 'vp' => $vp,
                            'current' => $totalOpportunities, 'target' => $opp, 'unit' => ''];
                        break;
                    }
                }

                $rankLadder = [
                    ['name' => 'Bronze Volunteer',   'required' => 1250,  'pts' => '1,250'],
                    ['name' => 'Silver Volunteer',   'required' => 2500,  'pts' => '2,500'],
                    ['name' => 'Gold Volunteer',     'required' => 5000,  'pts' => '5,000'],
                    ['name' => 'Platinum Volunteer', 'required' => 10000, 'pts' => '10,000'],
                ];
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-[300px_1fr] gap-6">

                {{-- ── LEFT: Progress column ──────────────────────────────── --}}
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-bold text-gray-800">Progress</h3>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    {{-- Rank tier list --}}
                    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden divide-y divide-gray-50">
                        @foreach($rankLadder as $rank)
                        @php $unlocked = $points >= $rank['required']; @endphp
                        <div class="flex items-center gap-3 p-3.5 {{ $unlocked ? '' : '' }}">
                            {{-- Medal icon --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 {{ $unlocked ? 'ring-2 ring-orange-300' : 'bg-gray-50' }}">
                                <img src="{{ asset('medals/'.strtolower(str_replace(' ', '-', $rank['name'])).'.png') }}"
                                     alt="{{ $rank['name'] }}"
                                     class="w-8 h-8 {{ $unlocked ? '' : 'grayscale opacity-40' }}">
                            </div>
                            {{-- Name + VP --}}
                            <div class="flex-grow min-w-0">
                                <p class="text-sm font-semibold truncate {{ $unlocked ? 'text-orange-600' : 'text-gray-500' }}">
                                    {{ $rank['name'] }}
                                </p>
                                <p class="text-xs text-gray-400">{{ $rank['pts'] }} VP</p>
                            </div>
                            {{-- Lock or unlock indicator --}}
                            @if($unlocked)
                                <svg class="w-4 h-4 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Challenges completed count --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="{{ $accentOrange }}" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Challenges completed</span>
                        </div>
                        <span class="text-xl font-bold" style="color:{{ $accentOrange }}">{{ count($completedChallenges) }}</span>
                    </div>
                </div>

                {{-- ── RIGHT: Achievements column ──────────────────────────── --}}
                <div class="space-y-6">

                    {{-- Next badge + progress bar --}}
                    <div class="bg-white rounded-xl border border-gray-100 p-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Achievements</p>
                        @if($nextRank)
                        <p class="text-base font-bold text-gray-800 mb-3">
                            Next badge: <span style="color:{{ $accentOrange }}">{{ $nextRank['name'] }}</span>
                        </p>
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="#FBBF24" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">{{ number_format($points) }} / {{ number_format($nextRank['required']) }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full transition-all" style="width:{{ $pct }}%; background:{{ $accentOrange }};"></div>
                        </div>
                        @else
                        <p class="text-base font-bold" style="color:{{ $accentOrange }}">Maximum rank achieved!</p>
                        @endif
                    </div>

                    {{-- Available challenges --}}
                    @if(count($availableChallenges) > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Available challenges</h4>
                        <div class="space-y-2">
                            @foreach($availableChallenges as $ch)
                            @php $prog = $ch['target'] > 0 ? min(round($ch['current']/$ch['target']*100), 100) : 0; @endphp
                            <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:border-orange-100 transition">
                                <div class="w-14 h-14 flex flex-col items-center justify-center rounded-xl flex-shrink-0 text-white font-bold"
                                     style="background:{{ $accentOrange }}">
                                    <span class="text-sm leading-none">+{{ $ch['vp'] }}</span>
                                    <span class="text-xs leading-none mt-0.5">VP</span>
                                </div>
                                <div class="flex-grow min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $ch['label'] }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        {{ $ch['current'] }}{{ $ch['unit'] ? ' '.$ch['unit'] : '' }} / {{ $ch['target'] }}{{ $ch['unit'] ? ' '.$ch['unit'] : '' }} completed
                                    </p>
                                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full transition-all" style="width:{{ $prog }}%; background:{{ $accentOrange }};"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Completed challenges --}}
                    @if(count($completedChallenges) > 0)
                    <div>
                        <h4 class="text-sm font-semibold text-gray-600 mb-3">Completed challenges</h4>
                        <div class="space-y-2">
                            @foreach($completedChallenges as $ch)
                            <div class="flex items-center gap-4 p-4 bg-white rounded-xl border border-green-100">
                                <div class="w-14 h-14 flex flex-col items-center justify-center rounded-xl flex-shrink-0 text-white font-bold bg-green-500">
                                    <span class="text-sm leading-none">+{{ $ch['vp'] }}</span>
                                    <span class="text-xs leading-none mt-0.5">VP</span>
                                </div>
                                <div class="flex-grow">
                                    <p class="text-sm font-semibold text-gray-800">{{ $ch['label'] }}</p>
                                </div>
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>{{-- end right column --}}
            </div>{{-- end grid --}}
        </div>

    </div>{{-- end main card --}}
</div>

<script>
function switchTab(tab) {
    ['personal','opportunities','badges'].forEach(function(t) {
        document.getElementById('panel-' + t).classList.add('hidden');
        document.getElementById('tab-' + t).classList.remove('active');
    });
    document.getElementById('panel-' + tab).classList.remove('hidden');
    document.getElementById('tab-' + tab).classList.add('active');
}

function forceDownload(link) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", link.href, true);
    xhr.responseType = "blob";
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
