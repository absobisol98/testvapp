@extends('custom.layouts.app')

@section('content')
<div id="mainLandingPage" class="w-full flex flex-col items-center justify-center">

    {{-- ── Hero Section ──────────────────────────────────────────────── --}}
    <section class="w-full bg-white px-6 md:px-16 py-16 md:py-24">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            {{-- Left copy --}}
            <div class="flex flex-col gap-6">
                <div class="inline-flex items-center gap-2 text-[#ff7b00] text-sm font-semibold w-fit">
                    <span class="w-2 h-2 rounded-full bg-[#ff7b00] animate-pulse"></span>
                    Brigada 2026 is now open
                </div>
                <h1 class="text-4xl md:text-6xl font-bold leading-tight text-gray-900">
                    Your time can<br>change a<br><span class="text-[#0433ff]">community.</span>
                </h1>
                <p class="text-gray-500 text-lg max-w-md">
                    Find a volunteer opportunity that fits your skills and schedule — and join thousands across our partner network making a real difference.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ url('/admin/events') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-[#ff7b00] text-white font-semibold hover:bg-[#e06e00] transition text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Browse opportunities
                    </a>
                    <a href="{{ route('volunteer.form.view') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full border-2 border-[#0433ff] text-[#0433ff] font-semibold hover:bg-[#0433ff] hover:text-white transition text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Become a volunteer
                    </a>
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <div class="flex -space-x-2">
                        @foreach(['0433ff','ff7b00','0228cc','e06e00'] as $c)
                        <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-white text-xs font-bold"
                             style="background:#{{ $c }}">V</div>
                        @endforeach
                    </div>
                    <span><strong>{{ number_format($volunteerCount) }}+ volunteers</strong> have joined this year</span>
                </div>
            </div>

            {{-- Right: featured card --}}
            <div class="relative">
                @php $heroImg = $featuredOpportunity?->getMedia('event-banner-attachments')?->first()?->getUrl(); @endphp
                <div class="rounded-2xl overflow-hidden shadow-xl bg-gray-100 aspect-video">
                    @if($heroImg)
                        <img src="{{ $heroImg }}" alt="{{ $featuredOpportunity->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-[#0433ff]/10 to-[#ff7b00]/10 flex items-center justify-center">
                            <img src="{{ asset('img/logo-vapp.svg') }}" class="w-24 opacity-20" alt="VAPP">
                        </div>
                    @endif
                </div>
                <div class="absolute top-4 right-4 bg-white rounded-xl shadow-lg px-4 py-3 text-sm max-w-[180px]">
                    <p class="text-xs text-[#ff7b00] font-semibold mb-1">This weekend</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $opportunities->count() }}</p>
                    <p class="text-xs text-gray-500">ways to help</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Stats Bar ───────────────────────────────────────────────────── --}}
    <div class="w-full bg-[#0c1f5e] text-white py-8 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="flex flex-col items-center gap-1">
                <svg class="w-5 h-5 mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/>
                </svg>
                <p class="text-3xl font-bold">{{ number_format($volunteerCount) }}</p>
                <p class="text-sm opacity-70">Volunteers</p>
            </div>
            <div class="flex flex-col items-center gap-1">
                <svg class="w-5 h-5 mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-3xl font-bold">{{ number_format(\App\Models\EventAttendee::count() * 4) }}</p>
                <p class="text-sm opacity-70">Hours rendered</p>
            </div>
            <div class="flex flex-col items-center gap-1">
                <svg class="w-5 h-5 mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <p class="text-3xl font-bold">{{ $programCount }}</p>
                <p class="text-sm opacity-70">Active programs</p>
            </div>
            <div class="flex flex-col items-center gap-1">
                <svg class="w-5 h-5 mb-1 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-3xl font-bold">{{ $opportunityCount }}</p>
                <p class="text-sm opacity-70">Open opportunities</p>
            </div>
        </div>
        <p class="text-center text-xs mt-4 opacity-40">Updated live — numbers grow as volunteers like you sign up.</p>
    </div>

    {{-- ── Opportunities: Ways to help this month ──────────────────────── --}}
    <section class="w-full bg-white py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-start justify-between mb-10">
                <div>
                    <p class="text-xs font-bold tracking-widest text-[#ff7b00] uppercase mb-2">Opportunities</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Ways to help this month</h2>
                    <p class="text-gray-500 mt-2 max-w-lg">Hand-picked opportunities across the partner network. New ones are added every week.</p>
                </div>
                <a href="{{ url('/admin/events') }}"
                   class="hidden md:inline-flex items-center gap-1 text-[#0433ff] font-medium hover:underline text-sm mt-2 whitespace-nowrap">
                    See all opportunities
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($opportunities->take(3) as $opportunity)
                    @php
                        $img   = $opportunity->getMedia('event-banner-attachments')->first();
                        $slot  = $opportunity->slots?->first();
                        $totalSlots = $slot?->total_slots ?? 0;
                        $takenSlots = $slot ? $opportunity->registrations()
                            ->where('slot_type_id', $slot->id)
                            ->where('status_id', '!=', 3)->count() : 0;
                        $availSlots = max(0, $totalSlots - $takenSlots);
                        $pct = $totalSlots > 0 ? ($takenSlots / $totalSlots * 100) : 0;
                        $isRegistered = auth()->check()
                            ? \App\Models\EventRegistration::where('volunteer_id', auth()->id())
                                ->where('event_id', $opportunity->id)
                                ->whereIn('status_id', [1, 2])->exists()
                            : false;
                        $orgName = $opportunity->program?->name ?? 'Ayala Foundation';
                    @endphp
                    <div class="rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col hover:shadow-md transition">
                        {{-- Image --}}
                        <div class="h-44 bg-gray-100 overflow-hidden relative">
                            @if($img)
                                <img src="{{ $img->getUrl() }}" alt="{{ $opportunity->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-[#0433ff]/10 to-[#ff7b00]/10 flex items-center justify-center">
                                    <img src="{{ asset('img/logo-vapp.svg') }}" class="w-12 opacity-20" alt="">
                                </div>
                            @endif
                        </div>
                        {{-- Body --}}
                        <div class="p-4 flex flex-col gap-2 flex-1">
                            <p class="text-xs font-semibold text-[#ff7b00] uppercase tracking-wide">{{ $orgName }}</p>
                            <p class="font-bold text-gray-900 text-base leading-tight">{{ $opportunity->title }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M d, Y · g:i A') }}
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $opportunity->location ?? 'TBA' }}
                            </div>
                            @if($slot)
                                <div class="flex items-center justify-between text-xs text-gray-400 mt-1">
                                    <span>{{ $availSlots }} slots left</span>
                                    <span>{{ $totalSlots }} total</span>
                                </div>
                                <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#ff7b00] rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                            @endif
                        </div>
                        {{-- CTA --}}
                        <div class="px-4 pb-4">
                            @if($isRegistered)
                                <div class="w-full py-2 rounded-full bg-green-50 text-green-700 font-semibold text-sm text-center flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Registered
                                </div>
                            @elseif($slot && $availSlots > 0)
                                @auth
                                    <form method="POST" action="{{ url('/event/'.$opportunity->id.'/register-slot/'.$slot->id) }}">
                                        @csrf
                                        <input type="hidden" name="privacy_policy" value="1">
                                        <button type="submit"
                                                class="w-full py-2 rounded-full bg-[#ff7b00] hover:bg-[#e06e00] text-white font-semibold text-sm transition flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Join
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ url('/admin/login') }}"
                                       class="block w-full py-2 rounded-full bg-[#ff7b00] hover:bg-[#e06e00] text-white font-semibold text-sm transition text-center">
                                        Join
                                    </a>
                                @endauth
                            @else
                                <div class="w-full py-2 rounded-full border border-gray-200 text-gray-400 font-semibold text-sm text-center">
                                    Full
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-400 py-12">No opportunities available yet.</div>
                @endforelse
            </div>

            <div class="mt-6 text-center md:hidden">
                <a href="{{ url('/admin/events') }}" class="inline-flex items-center gap-1 text-[#0433ff] font-medium text-sm">
                    See all opportunities
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ── How It Works ─────────────────────────────────────────────────── --}}
    <section class="w-full bg-gray-50 py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <p class="text-xs font-bold tracking-widest text-[#ff7b00] uppercase mb-2">How It Works</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Volunteering in three simple steps</h2>
                <p class="text-gray-500 mt-2">We rebuilt the experience so going from "I want to help" to "I'm signed up" takes minutes.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'title' => 'Find a cause', 'body' => 'Browse and filter opportunities by location, date, and the causes you care about.'],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Sign up in minutes', 'body' => 'Join with a click, guided forms. No lengthy paperwork – just the essentials.'],
                    ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Show up & make impact', 'body' => 'Get clear details on what to expect. Your hours are logged automatically.'],
                ] as $step)
                <div class="flex flex-col items-start gap-4 p-6 bg-white rounded-2xl shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-[#0433ff]/10 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#0433ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $step['icon'] }}"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-lg mb-1">{{ $step['title'] }}</p>
                        <p class="text-gray-500 text-sm">{{ $step['body'] }}</p>
                    </div>
                    <svg class="w-5 h-5 text-[#0433ff] mt-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Program + Volunteer Cards ────────────────────────────────────── --}}
    <section class="w-full bg-white py-16 px-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Program Card --}}
            <div class="rounded-2xl p-8 md:p-12 flex flex-col justify-between gap-8 bg-[#0c1f5e] text-white relative overflow-hidden min-h-[320px]">
                <div class="relative z-10">
                    <p class="text-xs font-bold tracking-widest text-[#ff7b00] uppercase mb-4">Our Program</p>
                    <h3 class="text-2xl md:text-3xl font-bold leading-tight mb-4">Corporate Citizenship &amp; Volunteerism</h3>
                    <p class="text-white/70 text-sm leading-relaxed">We contribute to the nation's development goals by aligning our giving, focusing our efforts, and making real impact in the lives of people across our conglomerate, communities, and country.</p>
                </div>
                <div class="relative z-10">
                    <a href="https://ayalafoundation.org" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border-2 border-white/40 text-white font-semibold text-sm hover:bg-white hover:text-[#0c1f5e] transition">
                        See all programs
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            {{-- Become a Volunteer Card --}}
            <div class="rounded-2xl flex flex-col justify-between gap-8 text-white relative overflow-hidden min-h-[320px] bg-cover bg-center"
                 style="background-image: url('{{ asset('img/ayala-foundation-bg-1.jpg') }}')">
                <div class="absolute inset-0 bg-[#0c1f5e]/60 rounded-2xl"></div>
                <div class="relative z-10 p-8 md:p-12 flex flex-col justify-between h-full gap-8">
                    <div>
                        <p class="text-xs font-bold tracking-widest text-[#ff7b00] uppercase mb-4">Join Us</p>
                        <h3 class="text-2xl md:text-3xl font-bold leading-tight mb-4">Become a volunteer</h3>
                        <p class="text-white/70 text-sm">Create your volunteer profile once, then join any opportunity with a single tap.</p>
                    </div>
                    <div>
                        <a href="{{ route('volunteer.form.view') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#ff7b00] hover:bg-[#e06e00] text-white font-semibold text-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Become a volunteer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Partners ─────────────────────────────────────────────────────── --}}
    <section class="w-full bg-gray-50 py-12 px-6">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-xs font-bold tracking-widest text-gray-400 uppercase mb-8">Powered by our partners</p>
            <div class="flex flex-wrap items-center justify-center gap-x-10 gap-y-4">
                @foreach(['Ayala', 'BPI', 'Globe', 'ACEN', 'AC Health', 'Ayala Land', 'Manila Water', 'AC Energy', 'BPI Foundation', 'GCash'] as $partner)
                    <span class="text-gray-400 font-semibold text-base hover:text-gray-600 transition cursor-default">{{ $partner }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Stories ──────────────────────────────────────────────────────── --}}
    <section class="w-full bg-white py-16 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-10">
                <p class="text-xs font-bold tracking-widest text-[#ff7b00] uppercase mb-2">Stories</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">From our volunteers</h2>
            </div>
            <div class="max-w-2xl mx-auto">
                <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-8 md:p-10 text-center">
                    <svg class="w-8 h-8 text-gray-200 mx-auto mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>
                    <p class="text-gray-700 text-lg leading-relaxed mb-8">
                        "Working alongside other volunteers under Brigada Ayala was not only fun, it was truly rewarding. I'm grateful I could use my skills to help create a safer learning environment for hundreds of children."
                    </p>
                    <div class="flex items-center justify-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#0433ff] flex items-center justify-center text-white font-bold text-sm">JB</div>
                        <div class="text-left">
                            <p class="font-semibold text-gray-900 text-sm">Jay Bosi</p>
                            <p class="text-xs text-gray-500">Volunteer · Globe Telecom</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-center gap-2 mt-6">
                        <span class="w-6 h-1.5 bg-[#ff7b00] rounded-full"></span>
                        <span class="w-2 h-2 bg-gray-200 rounded-full"></span>
                        <span class="w-2 h-2 bg-gray-200 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA Section ──────────────────────────────────────────────────── --}}
    <div class="w-full py-24 px-8 text-center text-white"
         style="background: linear-gradient(135deg, #0c1f5e 0%, #0433ff 100%);">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to make this weekend count?</h2>
        <p class="text-lg md:text-xl mb-10 text-white/70">It takes two minutes to join your first opportunity.</p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/admin/events') }}"
               class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-[#ff7b00] hover:bg-[#e06e00] text-white font-semibold text-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Browse opportunities
            </a>
            <a href="{{ route('volunteer.form.view') }}"
               class="inline-flex items-center gap-2 px-8 py-3 rounded-full border-2 border-white text-white font-semibold text-lg hover:bg-white hover:text-[#0c1f5e] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Become a volunteer
            </a>
        </div>
    </div>

</div>
@endsection
