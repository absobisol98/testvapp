@extends('custom.layouts.app')

@section('title', 'Home')

@section('content')

{{-- Design tokens + Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

<style>
:root {
    --blue-900: #072b54;
    --blue-800: #0a3a6e;
    --blue-700: #0e4f99;
    --blue-600: #1565c4;
    --blue-500: #2a7de0;
    --blue-400: #4a93e8;
    --blue-100: #d6e6f8;
    --blue-50:  #eef4fc;
    --orange-600: #d9650c;
    --orange-500: #f07a1e;
    --orange-400: #f79544;
    --orange-50:  #fef2e7;
    --green-600: #1d8a52;
    --green-50:  #eaf7f0;
    --ink:   #0d1b2e;
    --slate: #4a5568;
    --muted: #8896a4;
    --faint: #c4cdd6;
    --line:  #e8ecf0;
    --line-soft: #f0f3f6;
    --bg-soft: #f7f9fb;
    --bg-tint: #f0f4f8;
    --font-display: "Bricolage Grotesque", system-ui, sans-serif;
    --font-body:    "Public Sans", system-ui, sans-serif;
    --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 22px; --r-pill: 999px;
    --sh-sm: 0 1px 4px rgba(13,27,46,.07);
    --sh-md: 0 4px 16px rgba(13,27,46,.09);
    --sh-lg: 0 8px 32px rgba(13,27,46,.12);
    --sh-blue: 0 8px 24px rgba(14,79,153,.35);
    --maxw: 1180px;
}

/* Base */
.vapp-page { font-family: var(--font-body); color: var(--ink); }
.vapp-page h1,.vapp-page h2,.vapp-page h3,.vapp-page h4 {
    font-family: var(--font-display); margin: 0; line-height: 1.08; letter-spacing: -.015em; font-weight: 700;
}
.vapp-wrap { max-width: var(--maxw); margin: 0 auto; padding: 0 28px; }
.vapp-section { padding: 80px 0; }
.vapp-eyebrow {
    font-family: var(--font-body); font-weight: 700; font-size: 12px;
    letter-spacing: .14em; text-transform: uppercase; color: var(--orange-600);
    display: inline-flex; align-items: center; gap: 6px;
}
.vapp-eyebrow.on-dark { color: var(--orange-400); }

/* Buttons */
.vapp-btn {
    display: inline-flex; align-items: center; justify-content: center; gap: 9px;
    font-family: var(--font-body); font-weight: 700; font-size: 15px;
    padding: 12px 22px; border-radius: var(--r-md); border: none; cursor: pointer;
    text-decoration: none; transition: all .18s; white-space: nowrap; line-height: 1;
}
.vapp-btn svg { width: 18px; height: 18px; }
.vapp-btn-primary { background: var(--orange-500); color: #fff; box-shadow: 0 6px 16px rgba(240,122,30,.28); }
.vapp-btn-primary:hover { background: var(--orange-600); transform: translateY(-1px); box-shadow: 0 10px 22px rgba(240,122,30,.34); color: #fff; }
.vapp-btn-ghost { background: transparent; color: var(--blue-700); border: 1.5px solid var(--line); }
.vapp-btn-ghost:hover { border-color: var(--blue-500); background: var(--blue-50); color: var(--blue-700); }
.vapp-btn-ghost.on-dark { color: #fff; border-color: rgba(255,255,255,.4); }
.vapp-btn-ghost.on-dark:hover { background: rgba(255,255,255,.12); border-color: #fff; }
.vapp-btn-white { background: #fff; color: var(--blue-800); }
.vapp-btn-white:hover { transform: translateY(-1px); box-shadow: var(--sh-md); color: var(--blue-800); }
.vapp-btn-lg { padding: 15px 30px; font-size: 16px; }
.vapp-btn-sm { padding: 9px 16px; font-size: 13.5px; }
.vapp-btn-block { width: 100%; }

/* Cards */
.vapp-card {
    background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg);
    overflow: hidden; display: flex; flex-direction: column;
    transition: box-shadow .2s, transform .2s;
}
.vapp-card:hover { box-shadow: var(--sh-lg); transform: translateY(-3px); }

/* Progress bar */
.vapp-progress { height: 7px; background: var(--line); border-radius: var(--r-pill); overflow: hidden; }
.vapp-progress-fill { display: block; height: 100%; background: var(--orange-500); border-radius: var(--r-pill); }

/* Badge/chip */
.vapp-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-family: var(--font-body); font-weight: 700; font-size: 12px;
    padding: 5px 10px; border-radius: var(--r-pill);
}

/* Meta row */
.vapp-meta { display: flex; align-items: center; gap: 7px; color: var(--slate); font-size: 13.5px; font-weight: 500; }
.vapp-meta svg { width: 15px; height: 15px; color: var(--muted); flex: none; }

/* Image placeholder */
.vapp-img-ph {
    background: var(--bg-tint); position: relative; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
.vapp-img-ph::after {
    content: ''; position: absolute; inset: 0;
    background: repeating-linear-gradient(45deg, transparent, transparent 8px, rgba(14,79,153,.04) 8px, rgba(14,79,153,.04) 16px);
}

/* Animations */
@keyframes vapp-fadeUp { from { opacity:0; transform:translateY(14px); } to { opacity:1; transform:none; } }
.vapp-fade-up { animation: vapp-fadeUp .5s cubic-bezier(.2,.7,.2,1) both; }
@keyframes vapp-pop { from { opacity:0; transform:scale(.9); } to { opacity:1; transform:none; } }
.vapp-pop { animation: vapp-pop .6s cubic-bezier(.2,.7,.2,1) both; }

/* Section head */
.vapp-section-head { margin-bottom: 44px; }

/* Responsive */
@media (max-width: 980px) {
    .vapp-hero-grid { grid-template-columns: 1fr !important; gap: 36px !important; }
    .vapp-hero-visual { order: -1; }
    .vapp-stat-grid { grid-template-columns: 1fr 1fr !important; }
    .vapp-cards-3 { grid-template-columns: 1fr 1fr !important; }
    .vapp-two-up { grid-template-columns: 1fr !important; }
    .vapp-foot-grid { grid-template-columns: 1fr 1fr !important; gap: 32px !important; }
}
@media (max-width: 640px) {
    .vapp-cards-3 { grid-template-columns: 1fr !important; }
    .vapp-stat-grid { grid-template-columns: 1fr 1fr !important; gap: 18px !important; }
    .vapp-wrap { padding: 0 18px; }
    .vapp-section { padding: 56px 0; }
    .vapp-foot-grid { grid-template-columns: 1fr !important; }
}
</style>

<div class="vapp-page">

{{-- ===================== HERO ===================== --}}
<section style="background: linear-gradient(180deg,#fff 0%,var(--bg-tint) 100%); overflow: hidden; padding-top: 110px;">
    <div class="vapp-wrap">
        <div class="vapp-hero-grid" style="display:grid; grid-template-columns:1.05fr 1fr; gap:56px; align-items:center; padding:60px 0 70px;">

            {{-- Left: Text --}}
            <div class="vapp-fade-up">
                <div class="vapp-badge" style="background:var(--orange-50); color:var(--orange-600); margin-bottom:22px; font-size:13px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/></svg>
                    Brigada 2026 is now open
                </div>
                <h1 style="font-size:clamp(36px,5vw,58px); line-height:1.02; letter-spacing:-.03em;">
                    Your time can<br>change a <span style="color:var(--blue-700);">community.</span>
                </h1>
                <p style="margin-top:20px; font-size:clamp(16px,1.4vw,18px); line-height:1.65; color:var(--slate); max-width:480px;">
                    Find a volunteer opportunity that fits your skills and schedule — and join thousands across our partner network making a real difference.
                </p>
                <div style="display:flex; gap:12px; margin-top:28px; flex-wrap:wrap;">
                    @guest
                        <a href="{{ route('volunteer.form.view') }}" class="vapp-btn vapp-btn-primary vapp-btn-lg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0"/></svg>
                            Become a Volunteer
                        </a>
                        <a href="#sec-opportunities" class="vapp-btn vapp-btn-ghost vapp-btn-lg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM21 21l-4.3-4.3"/></svg>
                            Browse Opportunities
                        </a>
                    @endguest
                    @auth
                        <a href="/admin/events" class="vapp-btn vapp-btn-primary vapp-btn-lg">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM21 21l-4.3-4.3"/></svg>
                            Browse Opportunities
                        </a>
                        <a href="/admin" class="vapp-btn vapp-btn-ghost vapp-btn-lg">
                            Dashboard
                        </a>
                    @endauth
                </div>
                {{-- Social proof --}}
                <div style="display:flex; align-items:center; gap:14px; margin-top:28px;">
                    <div style="display:flex;">
                        @php $avatarColors = ['#0e4f99','#1565c4','#f07a1e','#1d8a52']; $avatarLabels = ['MR','JB','CT','AL']; @endphp
                        @foreach($avatarColors as $i => $color)
                        <div style="width:34px;height:34px;border-radius:50%;background:{{ $color }};border:2.5px solid #fff;margin-left:{{ $i ? '-10px' : '0' }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:12px;z-index:{{ 4-$i }};">
                            {{ $avatarLabels[$i] }}
                        </div>
                        @endforeach
                    </div>
                    <div style="font-size:14px; color:var(--slate); font-weight:500;">
                        <strong style="color:var(--ink);">{{ number_format($statsVolunteers) }}+ volunteers</strong> have joined this year
                    </div>
                </div>
            </div>

            {{-- Right: Visual --}}
            <div class="vapp-hero-visual" style="position:relative;">
                @php
                    $heroBanner = $featuredOpportunity?->getFirstMediaUrl('event-banner-attachments');
                @endphp
                @if($heroBanner)
                    <img src="{{ $heroBanner }}" alt="Volunteers" style="width:100%;height:440px;object-fit:cover;border-radius:var(--r-xl);box-shadow:var(--sh-lg);">
                @else
                    <div class="vapp-img-ph" style="height:440px;border-radius:var(--r-xl);box-shadow:var(--sh-lg);">
                        <span style="font-size:13px;color:var(--blue-700);background:rgba(255,255,255,.86);padding:8px 14px;border-radius:var(--r-pill);z-index:1;">Volunteers smiling together at an outreach event</span>
                    </div>
                @endif

                {{-- Floating: hours card --}}
                <div class="vapp-pop" style="position:absolute;left:-20px;bottom:36px;background:#fff;border-radius:var(--r-lg);box-shadow:var(--sh-lg);padding:14px 18px;display:flex;align-items:center;gap:14px;animation-delay:.3s;">
                    <div style="width:44px;height:44px;border-radius:12px;background:var(--green-50);color:var(--green-600);display:flex;align-items:center;justify-content:center;flex:none;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;"><path d="M11 20A7 7 0 0 1 4 13c0-6 7-10 16-10 0 9-4 16-9 17ZM4 21c2-4 5-7 9-9"/></svg>
                    </div>
                    <div>
                        <div style="font-family:var(--font-display);font-weight:800;font-size:22px;color:var(--ink);line-height:1;">{{ number_format($statsHours) }}</div>
                        <div style="font-size:12px;color:var(--muted);font-weight:600;">hours given back</div>
                    </div>
                </div>

                {{-- Floating: open opportunities --}}
                <div class="vapp-pop" style="position:absolute;right:-14px;top:30px;background:var(--blue-700);color:#fff;border-radius:var(--r-md);box-shadow:var(--sh-blue);padding:12px 16px;animation-delay:.45s;">
                    <div style="font-size:11px;opacity:.8;font-weight:600;">This weekend</div>
                    <div style="font-weight:700;font-size:15px;">{{ $statsOpen }} ways to help</div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===================== LIVE STATS BAND ===================== --}}
<section style="background:var(--blue-800);">
    <div class="vapp-wrap" style="padding:44px 28px;">
        <div class="vapp-stat-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px;">
            @php
                $statItems = [
                    ['label'=>'Volunteers','value'=>$statsVolunteers,'icon'=>'users'],
                    ['label'=>'Hours rendered','value'=>$statsHours,'icon'=>'clock'],
                    ['label'=>'Active programs','value'=>$statsPrograms,'icon'=>'grid'],
                    ['label'=>'Open opportunities','value'=>$statsOpen,'icon'=>'hand'],
                ];
                $statIcons = [
                    'users' => 'M16 20v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2|M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 20v-2a4 4 0 0 0-3-3.87M16 3.13A4 4 0 0 1 16 11',
                    'clock' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18ZM12 7v5l3 2',
                    'grid'  => 'M3 3h7v7H3zM14 3h7v7h-7zM14 14h7v7h-7zM3 14h7v7H3z',
                    'hand'  => 'M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0',
                ];
            @endphp
            @foreach($statItems as $stat)
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,.1);color:var(--orange-400);display:flex;align-items:center;justify-content:center;flex:none;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px;">
                        @foreach(explode('|', $statIcons[$stat['icon']]) as $d)
                            <path d="{{ $d }}"/>
                        @endforeach
                    </svg>
                </div>
                <div>
                    <div class="vapp-stat-num" style="font-family:var(--font-display);font-weight:800;font-size:clamp(26px,3vw,36px);color:#fff;line-height:1;" data-target="{{ $stat['value'] }}">0</div>
                    <div style="font-size:13px;color:rgba(255,255,255,.72);font-weight:600;margin-top:4px;">{{ $stat['label'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
        <div style="margin-top:22px;font-size:12px;color:rgba(255,255,255,.55);display:flex;align-items:center;gap:8px;">
            <span style="width:7px;height:7px;border-radius:50%;background:var(--green-600);box-shadow:0 0 0 3px rgba(29,138,82,.3);display:inline-block;"></span>
            Updated live — numbers grow as volunteers like you sign up
        </div>
    </div>
</section>

{{-- ===================== FEATURED OPPORTUNITIES ===================== --}}
<section class="vapp-section" id="sec-opportunities">
    <div class="vapp-wrap">
        {{-- Section head --}}
        <div class="vapp-section-head" style="display:flex;justify-content:space-between;align-items:flex-end;gap:24px;flex-wrap:wrap;">
            <div>
                <div class="vapp-eyebrow" style="margin-bottom:10px;">Opportunities</div>
                <h2 style="font-size:clamp(26px,3.4vw,38px);">Ways to help this month</h2>
                <p style="margin-top:12px;font-size:16px;line-height:1.6;color:var(--slate);">Hand-picked opportunities across the partner network. New ones are added every week.</p>
            </div>
            @guest
                <a href="{{ route('volunteer.form.view') }}" class="vapp-btn vapp-btn-ghost" style="white-space:nowrap;">
                    See all opportunities
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            @endguest
            @auth
                <a href="/admin/events" class="vapp-btn vapp-btn-ghost" style="white-space:nowrap;">
                    See all opportunities
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
            @endauth
        </div>

        @if($opportunities->count() > 0)
        <div class="vapp-cards-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @foreach($opportunities->take(3) as $opportunity)
            @php
                $opMedia = $opportunity->getFirstMediaUrl('event-banner-attachments');
                $totalSlots = $opportunity->slots->count();
                $spotsTotal = 40;
                $spotsFilled = rand(5, 35);
                $spotsLeft = max(0, $spotsTotal - $spotsFilled);
                $pct = min(100, round($spotsFilled / $spotsTotal * 100));
                $urgent = $spotsLeft <= 8;
            @endphp
            <article class="vapp-card">
                {{-- Image --}}
                <div style="position:relative;">
                    @if($opMedia)
                        <img src="{{ $opMedia }}" alt="{{ $opportunity->title }}" style="width:100%;height:172px;object-fit:cover;">
                    @else
                        <div class="vapp-img-ph" style="height:172px;">
                            <span style="font-size:11px;color:var(--blue-700);background:rgba(255,255,255,.86);padding:5px 10px;border-radius:var(--r-pill);z-index:1;">{{ $opportunity->title }}</span>
                        </div>
                    @endif
                    {{-- Program badge --}}
                    @if($opportunity->program)
                    <span class="vapp-badge" style="position:absolute;top:12px;left:12px;background:var(--blue-700);color:#fff;">
                        {{ $opportunity->program->name }}
                    </span>
                    @endif
                </div>
                {{-- Content --}}
                <div style="padding:16px 18px 18px;display:flex;flex-direction:column;gap:11px;flex:1;">
                    <h3 style="font-size:17px;line-height:1.25;font-weight:700;">{{ $opportunity->title }}</h3>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <div class="vapp-meta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M j, Y') }} · {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }}
                        </div>
                        <div class="vapp-meta">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><path d="M12 10.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z"/></svg>
                            {{ $opportunity->location ?? 'Location TBA' }}
                        </div>
                    </div>
                    <div style="margin-top:auto;display:flex;flex-direction:column;gap:12px;">
                        {{-- Spots bar --}}
                        <div>
                            <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:5px;">
                                <span style="font-size:12.5px;font-weight:700;color:{{ $urgent ? 'var(--orange-600)' : 'var(--slate)' }};">
                                    {{ $spotsLeft > 0 ? $spotsLeft . ' spot' . ($spotsLeft === 1 ? '' : 's') . ' left' : 'Fully booked' }}
                                </span>
                                <span style="font-size:12px;color:var(--muted);font-weight:600;">{{ $spotsFilled }}/{{ $spotsTotal }} joined</span>
                            </div>
                            <div class="vapp-progress">
                                <span class="vapp-progress-fill" style="width:{{ $pct }}%;"></span>
                            </div>
                        </div>
                        {{-- CTA --}}
                        @guest
                            <a href="{{ route('volunteer.form.view') }}" class="vapp-btn vapp-btn-primary vapp-btn-sm vapp-btn-block">
                                Join
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        @endguest
                        @auth
                            <a href="{{ url('/admin/events/view/' . $opportunity->id) }}" class="vapp-btn vapp-btn-primary vapp-btn-sm vapp-btn-block">
                                View Details
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:15px;height:15px;"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                            </a>
                        @endauth
                    </div>
                </div>
            </article>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:60px 0;color:var(--muted);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width:48px;height:48px;margin:0 auto 16px;display:block;opacity:.4;"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
            <p style="font-size:16px;">No opportunities available at the moment. Check back soon!</p>
        </div>
        @endif
    </div>
</section>

{{-- ===================== HOW IT WORKS ===================== --}}
<section class="vapp-section" style="background:var(--bg-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line);">
    <div class="vapp-wrap">
        <div class="vapp-section-head" style="text-align:center;max-width:600px;margin:0 auto 44px;">
            <div class="vapp-eyebrow" style="margin-bottom:10px;">How it works</div>
            <h2 style="font-size:clamp(26px,3.4vw,38px);">Volunteering in three simple steps</h2>
            <p style="margin-top:12px;font-size:16px;line-height:1.6;color:var(--slate);">We rebuilt the experience so getting from "I want to help" to "I'm signed up" takes minutes.</p>
        </div>
        @php
            $steps = [
                ['icon'=>'M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM21 21l-4.3-4.3','title'=>'Find a cause','body'=>'Browse and filter opportunities by location, date, and the causes you care about.'],
                ['icon'=>'M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0','title'=>'Sign up in minutes','body'=>'Join with a short, guided form. No lengthy paperwork — just the essentials.'],
                ['icon'=>'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12ZM8.2 13.9 7 22l5-3 5 3-1.2-8.1','title'=>'Show up & make impact','body'=>'Get clear details on what to expect. Your hours are logged automatically.'],
            ];
        @endphp
        <div class="vapp-cards-3" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;">
            @foreach($steps as $i => $step)
            <div style="background:#fff;border:1px solid var(--line);border-radius:var(--r-lg);padding:28px 24px;position:relative;">
                <div style="position:absolute;top:22px;right:22px;font-family:var(--font-display);font-weight:800;font-size:48px;color:var(--blue-50);line-height:1;user-select:none;">{{ $i+1 }}</div>
                <div style="width:52px;height:52px;border-radius:14px;background:var(--blue-700);color:#fff;display:flex;align-items:center;justify-content:center;margin-bottom:18px;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px;"><path d="{{ $step['icon'] }}"/></svg>
                </div>
                <h3 style="font-size:20px;margin-bottom:8px;">{{ $step['title'] }}</h3>
                <p style="color:var(--slate);font-size:15px;line-height:1.65;">{{ $step['body'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== PROGRAM + BECOME A VOLUNTEER ===================== --}}
<section class="vapp-section">
    <div class="vapp-wrap">
        <div class="vapp-two-up" style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

            {{-- Our Program --}}
            <div style="background:var(--blue-700);border-radius:var(--r-xl);padding:44px 40px;color:#fff;position:relative;overflow:hidden;">
                <div class="vapp-eyebrow on-dark" style="margin-bottom:12px;">Our program</div>
                <h3 style="font-size:clamp(22px,2.6vw,30px);color:#fff;line-height:1.1;">Corporate Citizenship &amp; Volunteerism</h3>
                <p style="margin-top:14px;font-size:15.5px;line-height:1.65;color:rgba(255,255,255,.85);max-width:420px;">
                    We contribute to the nation's development goals by aligning our giving, focusing our efforts, and making real impact in the lives of people across our conglomerate, communities, and country.
                </p>
                <div style="margin-top:26px;">
                    <a href="https://ayalafoundation.org" target="_blank" rel="noopener noreferrer" class="vapp-btn vapp-btn-white">
                        See all programs
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                </div>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;right:-30px;bottom:-30px;width:200px;height:200px;color:rgba(255,255,255,.06);"><path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0"/></svg>
            </div>

            {{-- Become a Volunteer --}}
            @php $volunteerBg = asset('img/ayala-foundation-bg-1.jpg'); @endphp
            <div style="position:relative;border-radius:var(--r-xl);overflow:hidden;min-height:320px;background:url('{{ $volunteerBg }}') center/cover no-repeat;">
                <div style="position:absolute;inset:0;background:linear-gradient(180deg,rgba(7,43,84,.15),rgba(7,43,84,.82));"></div>
                <div style="position:relative;padding:40px;height:100%;display:flex;flex-direction:column;justify-content:flex-end;color:#fff;box-sizing:border-box;min-height:320px;">
                    <div class="vapp-eyebrow on-dark" style="margin-bottom:10px;">Join us</div>
                    <h3 style="font-size:clamp(22px,2.6vw,30px);color:#fff;">Become a volunteer</h3>
                    <p style="margin-top:10px;font-size:15px;color:rgba(255,255,255,.9);max-width:340px;margin-bottom:22px;">
                        Create your volunteer profile once, then join any opportunity with a single tap.
                    </p>
                    <div>
                        <a href="{{ route('volunteer.form.view') }}" class="vapp-btn vapp-btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:17px;height:17px;"><path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0"/></svg>
                            Become a volunteer
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ===================== PARTNERS ===================== --}}
<section style="padding:44px 0 52px;border-top:1px solid var(--line);background:var(--bg-soft);" id="sec-partners">
    <div class="vapp-wrap">
        <p style="text-align:center;font-size:12px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:28px;">
            Powered by our partners
        </p>
        @php
            $partnerNames = ['Ayala', 'BPI', 'Globe', 'ACEN', 'AC Health', 'Ayala Land', 'Manila Water', 'AC Energy', 'BPI Foundation', 'GCash'];
        @endphp
        <div style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:20px 44px;">
            @foreach($partnerNames as $partner)
            <span class="vapp-partner-name" style="font-family:var(--font-display);font-weight:800;font-size:22px;color:var(--faint);letter-spacing:-.02em;cursor:default;transition:.15s;">
                {{ $partner }}
            </span>
            @endforeach
        </div>
    </div>
</section>

{{-- ===================== STORIES / TESTIMONIALS ===================== --}}
<section class="vapp-section" id="sec-stories">
    <div class="vapp-wrap">
        <div class="vapp-section-head" style="text-align:center;max-width:600px;margin:0 auto 44px;">
            <div class="vapp-eyebrow" style="margin-bottom:10px;">Stories</div>
            <h2 style="font-size:clamp(26px,3.4vw,38px);">From our volunteers</h2>
        </div>

        @php
            $testimonials = [
                ['quote'=>'Working alongside other volunteers under Brigada Ayala was not only fun, it was truly rewarding. I\'m grateful I could use my skills to help create a safer learning environment for hundreds of children.','name'=>'Jay Bosi','role'=>'Volunteer · Globe Telecom','init'=>'JB'],
                ['quote'=>'Signing up used to take forever. Now I found a coastal cleanup near me and joined in two minutes. I\'ve logged 32 hours this year and counting.','name'=>'Maria Reyes','role'=>'Volunteer · BPI','init'=>'MR'],
                ['quote'=>'As a first-time volunteer I was nervous, but the opportunity page told me exactly what to expect and what to bring. I showed up confident and ready to help.','name'=>'Carlo Tan','role'=>'Volunteer · Ayala Land','init'=>'CT'],
            ];
        @endphp

        <div style="max-width:780px;margin:0 auto;">
            <div id="vapp-testimonial" style="background:#fff;border:1px solid var(--line);border-radius:var(--r-xl);padding:44px 48px;box-shadow:var(--sh-md);text-align:center;position:relative;">
                <div style="font-family:var(--font-display);font-size:72px;line-height:.6;color:var(--blue-100);height:34px;">&ldquo;</div>
                <p id="vapp-t-quote" style="font-size:clamp(17px,2vw,21px);line-height:1.55;color:var(--ink);font-weight:500;margin:8px 0 26px;">{{ $testimonials[0]['quote'] }}</p>
                <div style="display:flex;align-items:center;justify-content:center;gap:12px;">
                    <div id="vapp-t-avatar" style="width:46px;height:46px;border-radius:50%;background:var(--blue-700);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;">{{ $testimonials[0]['init'] }}</div>
                    <div style="text-align:left;">
                        <div id="vapp-t-name" style="font-weight:700;font-size:15px;color:var(--ink);">{{ $testimonials[0]['name'] }}</div>
                        <div id="vapp-t-role" style="font-size:13px;color:var(--muted);">{{ $testimonials[0]['role'] }}</div>
                    </div>
                </div>
            </div>
            <div style="display:flex;justify-content:center;gap:10px;margin-top:22px;" id="vapp-t-dots">
                @foreach($testimonials as $ti => $t)
                <button onclick="vappSetTestimonial({{ $ti }})" data-idx="{{ $ti }}"
                    style="width:{{ $ti===0?'28px':'9px' }};height:9px;border-radius:999px;background:{{ $ti===0?'var(--orange-500)':'var(--line)' }};border:none;cursor:pointer;transition:.2s;padding:0;"
                    aria-label="Testimonial {{ $ti+1 }}"></button>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ===================== CTA BAND ===================== --}}
<section style="background:linear-gradient(120deg,var(--blue-800),var(--blue-700));position:relative;overflow:hidden;">
    <div class="vapp-wrap" style="padding:64px 28px;text-align:center;position:relative;">
        <h2 style="font-size:clamp(26px,3.4vw,40px);color:#fff;max-width:640px;margin:0 auto;">Ready to make this weekend count?</h2>
        <p style="color:rgba(255,255,255,.85);font-size:17px;margin:14px auto 0;max-width:480px;">
            It takes two minutes to join your first opportunity.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;margin-top:28px;flex-wrap:wrap;">
            @guest
                <a href="{{ route('volunteer.form.view') }}" class="vapp-btn vapp-btn-primary vapp-btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 11V6a2 2 0 0 0-4 0v5M14 10V4a2 2 0 0 0-4 0v7M10 10.5V6a2 2 0 0 0-4 0v8a8 8 0 0 0 8 8h0a8 8 0 0 0 8-8v-3a2 2 0 0 0-4 0"/></svg>
                    Become a Volunteer
                </a>
                <a href="#sec-opportunities" class="vapp-btn vapp-btn-ghost vapp-btn-lg on-dark">
                    Browse Opportunities
                </a>
            @endguest
            @auth
                <a href="/admin/events" class="vapp-btn vapp-btn-primary vapp-btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM21 21l-4.3-4.3"/></svg>
                    Browse Opportunities
                </a>
            @endauth
        </div>
    </div>
</section>

</div>{{-- end .vapp-page --}}

{{-- Testimonial data for JS --}}
<script>
var VAPP_TESTIMONIALS = @json($testimonials ?? []);
var vappTIdx = 0;

function vappSetTestimonial(idx) {
    if (!VAPP_TESTIMONIALS[idx]) return;
    vappTIdx = idx;
    var t = VAPP_TESTIMONIALS[idx];
    document.getElementById('vapp-t-quote').textContent = t.quote;
    document.getElementById('vapp-t-avatar').textContent = t.init;
    document.getElementById('vapp-t-name').textContent = t.name;
    document.getElementById('vapp-t-role').textContent = t.role;
    var dots = document.querySelectorAll('#vapp-t-dots button');
    dots.forEach(function(d, i) {
        d.style.width = i === idx ? '28px' : '9px';
        d.style.background = i === idx ? 'var(--orange-500)' : 'var(--line)';
    });
}

// Auto-rotate testimonials every 5s
setInterval(function() {
    vappSetTestimonial((vappTIdx + 1) % VAPP_TESTIMONIALS.length);
}, 5000);

// Count-up animation for stats band
(function() {
    var els = document.querySelectorAll('.vapp-stat-num');
    var animated = false;
    function runCountUp() {
        if (animated) return;
        animated = true;
        els.forEach(function(el) {
            var target = parseInt(el.getAttribute('data-target')) || 0;
            var start = Date.now();
            var duration = 1100;
            var timer = setInterval(function() {
                var t = Math.min(1, (Date.now() - start) / duration);
                var eased = 1 - Math.pow(1 - t, 3);
                el.textContent = Math.round(eased * target).toLocaleString();
                if (t >= 1) clearInterval(timer);
            }, 32);
        });
    }
    // Trigger when the stats band enters view
    if ('IntersectionObserver' in window) {
        var band = document.querySelector('.vapp-stat-grid');
        if (band) {
            var io = new IntersectionObserver(function(entries) {
                if (entries[0].isIntersecting) { runCountUp(); io.disconnect(); }
            }, { threshold: 0.1 });
            io.observe(band);
        }
    } else {
        setTimeout(runCountUp, 800);
    }
})();

// Partner name hover
document.querySelectorAll('.vapp-partner-name').forEach(function(el) {
    el.addEventListener('mouseenter', function() { el.style.color = 'var(--blue-700)'; });
    el.addEventListener('mouseleave', function() { el.style.color = 'var(--faint)'; });
});
</script>

@endsection
