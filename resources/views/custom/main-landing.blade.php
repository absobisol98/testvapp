@extends('custom.layouts.app')

@section('content')
<style>
/* ── Design tokens ──────────────────────────────────────────── */
:root {
  --blue-900: #072b54;
  --blue-800: #0a3a6e;
  --blue-700: #0e4f99;
  --blue-600: #1565c4;
  --blue-50:  #eef4fc;
  --or-600:   #d9650c;
  --or-500:   #f07a1e;
  --or-50:    #fef2e7;
  --green-600:#1d8a52;
  --green-50: #e8f5ee;
  --ink:      #15181d;
  --slate:    #454c58;
  --muted:    #737a87;
  --line:     #e6e8ec;
  --bg-soft:  #f6f7f9;
  --bg-tint:  #f3f6fb;

  /* TYPOGRAPHY */
  --font-b: 'NB International Pro', system-ui, sans-serif;
  --font-d: 'Bricolage Grotesque', system-ui, sans-serif;
}

/* ── Base typography ──────────────────────────────────────────── */
#landing-root {
  font-family: var(--font-b);
  color: var(--ink);
}

#landing-root h1,
#landing-root h2,
#landing-root h3,
#landing-root h4 {
  font-family: var(--font-d);
  letter-spacing: -0.02em;
  line-height: 1.08;
}

#landing-root a {
  text-decoration: none;
  color: inherit;
}

/* ── Animations ─────────────────────────────────────────────── */
.op-card {
  transition: transform .18s ease, box-shadow .18s ease;
}

.op-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 18px 48px rgba(16,32,56,.14),
              0 6px 16px rgba(16,32,56,.08);
}

@keyframes fadeUp {
  from { opacity:0; transform:translateY(16px); }
  to { opacity:1; transform:none; }
}

.fade-up {
  animation: fadeUp .55s cubic-bezier(.2,.7,.2,1) both;
}

@keyframes pop {
  from { opacity:0; transform:scale(.9); }
  to { opacity:1; transform:none; }
}

/* ── FONT LOAD (CRITICAL FIX) ─────────────────────────────────── */
@font-face {
  font-family: "NB International Pro";
  src: url("/fonts/nb-international/NBInternationalPro-Regular.woff2") format("woff2");
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "NB International Pro";
  src: url("/fonts/nb-international/NBInternationalPro-Medium.woff2") format("woff2");
  font-weight: 500;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "NB International Pro";
  src: url("/fonts/nb-international/NBInternationalPro-Bold.woff2") format("woff2");
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}
</style>


<div id="landing-root" class="w-full flex flex-col">

    {{-- ── HERO ────────────────────────────────────────────────────── --}}
    <section style="background: linear-gradient(180deg,#fff 0%,var(--bg-tint) 100%); overflow:hidden;padding-top:90px;">
        <div style="max-width:1200px; margin:0 auto; padding:0 28px;">
            <div style="display:grid; grid-template-columns:1.05fr 1fr; gap:56px; align-items:center; padding:70px 0 78px;"
                 class="hero-grid fade-up">
                {{-- Left --}}
                <div>
                    <div style="display:inline-flex; align-items:center; gap:7px; background:var(--or-50); color:var(--or-600); font-weight:700; font-size:13px; padding:5px 13px; border-radius:999px; margin-bottom:22px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3l14 9-14 9V3z"/></svg>
                        2026 International Year of Volunteers
                    </div>
                    <h1 style="font-size:clamp(38px,5vw,60px); font-family:'NB International Pro', sans-serif; line-height:1.02; letter-spacing:-.03em; margin:0 0 22px;">
                      Your involvement<br>is important to <span style="color:var(--blue-700);">us.</span>
                    </h1>
                    <p style="font-size:clamp(17px,1.4vw,19px); line-height:1.6; color:var(--slate); max-width:480px; margin-bottom:30px;">
                        Find a volunteer opportunity that fits your skills and schedule and join thousands across our partner network making a real difference.
                    </p>
                    <div style="display:flex; gap:13px; flex-wrap:wrap; margin-bottom:30px;">
                        <a href="{{ url('/admin/events') }}"
                           style="display:inline-flex; align-items:center; gap:9px; background:var(--or-500); color:#fff; font-weight:700; font-size:15px; padding:14px 26px; border-radius:999px; box-shadow:0 6px 16px rgba(240,122,30,.28); transition:.16s;"
                           onmouseover="this.style.background='var(--or-600)';this.style.transform='translateY(-1px)'"
                           onmouseout="this.style.background='var(--or-500)';this.style.transform=''">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                            Browse opportunities
                        </a>
                        <a href="{{ route('volunteer.form.view') }}"
                           style="display:inline-flex; align-items:center; gap:9px; background:transparent; color:var(--blue-700); font-weight:700; font-size:15px; padding:14px 26px; border-radius:999px; border:1.5px solid var(--line); transition:.16s;"
                           onmouseover="this.style.borderColor='var(--blue-700)';this.style.background='var(--blue-50)'"
                           onmouseout="this.style.borderColor='var(--line)';this.style.background='transparent'">
                            <svg width="17" height="17" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Become a volunteer
                        </a>
                    </div>
                    <div style="font-size:14.5px; color:var(--slate); font-weight:500;">
                        <strong style="color:var(--ink);">{{ number_format($volunteerCount) }}+ volunteers</strong> have joined this year
                    </div>
                </div>

                {{-- Right: featured image + floating cards --}}
                <div class="hero-visual" style="position:relative;">
                    @php $heroImg = $featuredOpportunity?->getMedia('event-banner-attachments')?->first()?->getUrl(); @endphp
                    <div style="height:440px; border-radius:22px; overflow:hidden; box-shadow:0 18px 48px rgba(16,32,56,.14); background:var(--bg-tint);">
                        @if($heroImg)
                            <img src="{{ $heroImg }}" alt="{{ $featuredOpportunity->title }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <div style="width:100%; height:100%; background:repeating-linear-gradient(135deg, rgba(14,79,153,.06) 0 12px, rgba(14,79,153,0) 12px 24px); display:flex; align-items:center; justify-content:center;">
                                <img src="{{ asset('img/logo-vapp.svg') }}" style="width:80px; opacity:.15;" alt="">
                            </div>
                        @endif
                    </div>
                    {{-- Floating: hours card --}}
                    <div style="position:absolute; left:-22px; bottom:36px; background:#fff; border-radius:16px; box-shadow:0 18px 48px rgba(16,32,56,.14); padding:16px 18px; display:flex; align-items:center; gap:14px; animation:pop .6s .3s both;">
                        <div style="width:46px; height:46px; border-radius:12px; background:var(--green-50); color:var(--green-600); display:flex; align-items:center; justify-content:center;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm3.5 14.5l-4.5-4.5V6h1.5v5.25l4 4-1 1.25z"/></svg>
                        </div>
                        <div>
                            <div style="font-family:var(--font-d); font-weight:800; font-size:22px; color:var(--ink); line-height:1;">{{ number_format(\App\Models\EventAttendee::count() * 4) }}</div>
                            <div style="font-size:12.5px; color:var(--muted); font-weight:600;">hours given back</div>
                        </div>
                    </div>
                    {{-- Floating: available now card --}}
                    <div style="position:absolute; right:-16px; top:30px; background:var(--blue-700); color:#fff; border-radius:12px; box-shadow:0 16px 40px rgba(14,79,153,.28); padding:12px 16px; animation:pop .6s .45s both;">
                        <div style="font-size:12px; opacity:.8; font-weight:600;">Available now</div>
                        <div style="font-weight:700; font-size:15px;">{{ $opportunities->count() }} ways to help</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── STATS BAND ──────────────────────────────────────────────── --}}
    <section style="background:var(--blue-800); position:relative;">
        <div style="max-width:1200px; margin:0 auto; padding:46px 28px;">
            <div class="stat-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:24px;">
                @foreach([
                    ['icon'=>'M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2|M23 21v-2a4 4 0 00-3-3.87|M16 3.13a4 4 0 010 7.75', 'val'=>number_format($volunteerCount), 'label'=>'Volunteers'],
                    ['icon'=>'M12 22a10 10 0 110-20 10 10 0 010 20zm0-15v5l3 2', 'val'=>number_format(\App\Models\EventAttendee::count() * 4), 'label'=>'Hours rendered'],
                    ['icon'=>'M12 2l1.09 3.26L16 7l-2.91 2.74L14 13l-2-1.27L10 13l.91-3.26L8 7l2.91-1.74z', 'val'=>$programCount, 'label'=>'Active programs'],
                    ['icon'=>'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'val'=>$opportunityCount, 'label'=>'Open opportunities'],
                ] as $s)
                <div style="display:flex; align-items:center; gap:16px;">
                    <div style="width:54px; height:54px; border-radius:14px; background:rgba(255,255,255,.1); color:#f07a1e; display:flex; align-items:center; justify-content:center; flex:none;">
                        <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            @foreach(explode('|', $s['icon']) as $p)
                                <path d="{{ $p }}"/>
                            @endforeach
                        </svg>
                    </div>
                    <div>
                        <div style="font-family:var(--font-d); font-weight:800; font-size:clamp(28px,3vw,38px); color:#fff; line-height:1;">{{ $s['val'] }}</div>
                        <div style="font-size:13.5px; color:rgba(255,255,255,.72); font-weight:600; margin-top:5px;">{{ $s['label'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── OPPORTUNITIES ───────────────────────────────────────────── --}}
<section style="background:#fff; padding:84px 0;">
    <div style="max-width:1200px; margin:0 auto; padding:0 28px;">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:40px; flex-wrap:wrap; gap:16px;">
            
            <div>
                <div style="font-family:var(--font-b); font-weight:700; font-size:13px; letter-spacing:.14em; text-transform:uppercase; color:var(--or-600); margin-bottom:10px;">
                    Opportunities
                </div>

                <h2 style="font-size:clamp(28px,3.5vw,40px); margin:0 0 10px;">
                    Ways to help this month
                </h2>

                <p style="color:var(--slate); font-size:16px; max-width:520px;">
                    Hand-picked opportunities across the partner network. New ones are added every week.
                </p>
            </div>

            <a href="{{ url('/admin/events') }}"
               style="display:inline-flex; align-items:center; gap:5px; color:var(--blue-700); font-weight:700; font-size:14px; border:1.5px solid var(--line); border-radius:999px; padding:10px 18px; transition:.15s; white-space:nowrap; align-self:flex-start; margin-top:8px;"
               onmouseover="this.style.borderColor='var(--blue-700)';this.style.background='var(--blue-50)'"
               onmouseout="this.style.borderColor='var(--line)';this.style.background='transparent'">
                See all opportunities
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </a>

        </div>

        <div class="cards-3" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
            @forelse($opportunities->take(3) as $opportunity)
                @php
                    $img = $opportunity->getMedia('event-banner-attachments')->first();
                    $slot = $opportunity->slots?->first();
                    $totalSlots = $slot?->total_slots ?? 0;
                    $takenSlots = $slot
                        ? $opportunity->registrations()
                            ->where('slot_type_id', $slot->id)
                            ->where('status_id','!=',3)
                            ->count()
                        : 0;

                    $availSlots = max(0, $totalSlots - $takenSlots);
                    $pct = $totalSlots > 0 ? ($takenSlots / $totalSlots * 100) : 0;
                @endphp

                <article class="op-card card"
                    style="background:#fff; border:1px solid var(--line); border-radius:16px; overflow:hidden; display:flex; flex-direction:column; box-shadow:0 4px 6px rgba(0,0,0,.07);">

                    {{-- IMAGE --}}
                    <div style="position:relative; height:200px; overflow:hidden; background:var(--bg-tint);">
                        @if($img)
                            <img src="{{ $img->getUrl() }}" alt="{{ $opportunity->title }}"
                                 style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <div style="width:100%; height:100%;
                                background:repeating-linear-gradient(135deg,rgba(14,79,153,.06) 0 12px,rgba(14,79,153,0) 12px 24px);">
                            </div>
                        @endif
                    </div>

                    {{-- BODY --}}
                    <div style="padding:16px 18px; display:flex; flex-direction:column; gap:10px; flex:1;">
                        <div style="font-size:11px; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--or-600);">
                            {{ $opportunity->program?->name ?? 'Ayala Foundation' }}
                        </div>

                        <h3 style="font-family:var(--font-d); font-size:17px; font-weight:700; margin:0;">
                            {{ $opportunity->title }}
                        </h3>

                        <div style="font-size:13px; color:var(--slate);">
                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M d, Y · g:i A') }}
                        </div>

                        <div style="font-size:13px; color:var(--slate);">
                            {{ $opportunity->location ?? 'TBA' }}
                        </div>
                    </div>

                    {{-- CTA --}}
                    <div style="padding:16px 18px 18px;">
                        <a href="{{ route('filament.admin.resources.events.view', ['record' => $opportunity->id]) }}"
                           style="display:flex; align-items:center; justify-content:center; gap:6px;
                           width:100%; padding:11px 16px;
                           background:var(--or-500); color:#fff;
                           font-weight:700; font-size:14px;
                           border-radius:8px; text-align:center; text-decoration:none;">
                            View Details
                        </a>
                    </div>

                </article>

            @empty
                <div style="grid-column:span 3; text-align:center; color:var(--muted); padding:48px;">
                    No opportunities available yet.
                </div>
            @endforelse
        </div>
    </div>
</section>

    {{-- ── HOW IT WORKS ────────────────────────────────────────────── --}}
    <section style="background:var(--bg-soft); border-top:1px solid var(--line); border-bottom:1px solid var(--line); padding:84px 0;">
        <div style="max-width:1200px; margin:0 auto; padding:0 28px;">
            <div style="text-align:center; margin-bottom:48px;">
                <div style="font-family:var(--font-b); font-weight:700; font-size:13px; letter-spacing:.14em; text-transform:uppercase; color:var(--or-600); margin-bottom:10px;">How it works</div>
                <h2 style="font-size:clamp(28px,3.5vw,40px); margin:0 0 12px;">Volunteering in three simple steps</h2>
                <p style="color:var(--slate); font-size:16px; max-width:560px; margin:0 auto;">We rebuilt the experience so getting from "I want to help" to "I'm signed up" takes minutes.</p>
            </div>
            <div class="cards-3" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
                @foreach([
                    ['icon'=>'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z', 'title'=>'Find a cause', 'body'=>'Browse and filter opportunities by location, date, and the causes you care about.', 'n'=>'1'],
                    ['icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Sign up in minutes', 'body'=>'Join with a short, guided form. No lengthy paperwork — just the essentials.', 'n'=>'2'],
                    ['icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title'=>'Show up & make impact', 'body'=>'Get clear details on what to expect. Your hours are logged automatically.', 'n'=>'3'],
                ] as $step)
                <div style="background:#fff; border:1px solid var(--line); border-radius:16px; padding:30px 26px; position:relative;">
                    <div style="position:absolute; top:24px; right:26px; font-family:var(--font-d); font-weight:800; font-size:48px; color:var(--blue-50); line-height:1; pointer-events:none;">{{ $step['n'] }}</div>
                    <div style="width:54px; height:54px; border-radius:14px; background:var(--blue-700); color:#fff; display:flex; align-items:center; justify-content:center; margin-bottom:20px;">
                        <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="{{ $step['icon'] }}"/>
                        </svg>
                    </div>
                    <h3 style="font-size:21px; margin:0 0 9px;">{{ $step['title'] }}</h3>
                    <p style="color:var(--slate); font-size:15.5px; line-height:1.6; margin:0;">{{ $step['body'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── PROGRAM + VOLUNTEER CARDS ───────────────────────────────── --}}
    <section style="background:#fff; padding:84px 0;">
        <div style="max-width:1200px; margin:0 auto; padding:0 28px;">
            <div class="two-up" style="display:grid; grid-template-columns:1fr 1fr; gap:24px;">
                {{-- Program card --}}
                <div style="background:var(--blue-700); border-radius:22px; padding:44px 40px; color:#fff; position:relative; overflow:hidden; min-height:320px;">
                    <div style="font-family:var(--font-b); font-weight:700; font-size:13px; letter-spacing:.14em; text-transform:uppercase; color:#f07a1e; margin-bottom:12px; position:relative; z-index:1;">Our program</div>
                    <h3 style="font-size:clamp(24px,2.6vw,32px); color:#fff; line-height:1.1; margin:0 0 16px; position:relative; z-index:1;">Corporate Citizenship &amp; Volunteerism</h3>
                    <p style="font-size:16px; line-height:1.65; color:rgba(255,255,255,.85); max-width:460px; margin:0 0 28px; position:relative; z-index:1;">We contribute to the nation's development goals by aligning our giving, focusing our efforts, and making real impact in the lives of people across our conglomerate, communities, and country.</p>
                    <a href="https://ayalafoundation.org" target="_blank" rel="noopener noreferrer"
                       style="display:inline-flex; align-items:center; gap:7px; background:#fff; color:var(--blue-700); font-weight:700; font-size:14px; padding:11px 20px; border-radius:999px; transition:.15s; position:relative; z-index:1;"
                       onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 14px rgba(16,32,56,.14)'"
                       onmouseout="this.style.transform='';this.style.boxShadow=''">
                        See all programs
                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                    </a>
                    {{-- Decorative hand --}}
                    <svg width="200" height="200" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.06)" stroke-width="1" style="position:absolute; right:-30px; bottom:-30px; pointer-events:none;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                {{-- Become volunteer card --}}
                <div style="position:relative; border-radius:22px; overflow:hidden; min-height:320px; background-image:url('{{ asset('img/ayala-foundation-bg-1.jpg') }}'); background-size:cover; background-position:center;">
                    <div style="position:absolute; inset:0; background:linear-gradient(180deg,rgba(7,43,84,.15),rgba(7,43,84,.82));"></div>
                    <div style="position:relative; padding:40px; height:100%; display:flex; flex-direction:column; justify-content:flex-end; color:#fff;">
                        <div style="font-family:var(--font-b); font-weight:700; font-size:13px; letter-spacing:.14em; text-transform:uppercase; color:#f07a1e; margin-bottom:10px;">Join us</div>
                        <h3 style="font-size:clamp(24px,2.6vw,32px); color:#fff; margin:0 0 12px;">Become a volunteer</h3>
                        <p style="font-size:15.5px; color:rgba(255,255,255,.9); max-width:380px; margin:0 0 24px; line-height:1.55;">Create your volunteer profile once, then join any opportunity with a single tap.</p>
                        <a href="{{ route('volunteer.form.view') }}"
                           style="display:inline-flex; align-items:center; gap:8px; background:var(--or-500); color:#fff; font-weight:700; font-size:14px; padding:12px 22px; border-radius:999px; box-shadow:0 6px 16px rgba(240,122,30,.28); transition:.15s; width:fit-content;"
                           onmouseover="this.style.background='var(--or-600)'" onmouseout="this.style.background='var(--or-500)'">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Become a volunteer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── PARTNERS ─────────────────────────────────────────────────── --}}
    <section style="background:var(--bg-soft); border-top:1px solid var(--line); padding:46px 0 56px;">
        <div style="max-width:1200px; margin:0 auto; padding:0 28px; text-align:center;">
            <p style="font-family:var(--font-b); font-size:13px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); margin-bottom:30px;">Powered by our partners</p>
            <div style="display:flex; flex-wrap:wrap; justify-content:center; align-items:center; gap:24px 52px;">
                @foreach(['Ayala','BPI','Globe','ACEN','AC Health','Ayala Land','Manila Water','AC Energy','BPI Foundation','GCash'] as $p)
                <span style="font-family:var(--font-d); font-weight:800; font-size:24px; color:#c8cdd6; letter-spacing:-.02em; transition:.15s; cursor:default;"
                      onmouseover="this.style.color='var(--blue-700)'" onmouseout="this.style.color='#c8cdd6'">{{ $p }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── STORIES / TESTIMONIALS ──────────────────────────────────── --}}
    <section style="background:#fff; padding:84px 0;" id="sec-stories">
        <div style="max-width:1200px; margin:0 auto; padding:0 28px; text-align:center;">
            <div style="font-family:var(--font-b); font-weight:700; font-size:13px; letter-spacing:.14em; text-transform:uppercase; color:var(--or-600); margin-bottom:10px;">Stories</div>
            <h2 style="font-size:clamp(28px,3.5vw,40px); margin:0 0 40px;">From our volunteers</h2>
            <div style="max-width:820px; margin:0 auto;">
                <div style="background:#fff; border:1px solid var(--line); border-radius:22px; padding:44px 48px; box-shadow:0 4px 14px rgba(16,32,56,.08);">
                    <div style="font-family:var(--font-d); font-size:72px; line-height:.6; color:var(--blue-50); height:34px; text-align:center;">"</div>
                    <p style="font-size:clamp(18px,2vw,22px); line-height:1.55; color:var(--ink); font-weight:500; margin:8px 0 28px;">Working alongside other volunteers under Brigada Ayala was not only fun, it was truly rewarding. I'm grateful I could use my skills to help create a safer learning environment for hundreds of children.</p>
                    <div style="display:flex; align-items:center; justify-content:center; gap:13px;">
                        <div style="width:48px; height:48px; border-radius:50%; background:var(--blue-700); color:#fff; display:flex; align-items:center; justify-content:center; font-family:var(--font-d); font-weight:700; font-size:16px;">JB</div>
                        <div style="text-align:left;">
                            <div style="font-weight:700; font-size:15.5px; color:var(--ink);">Jay Bosi</div>
                            <div style="font-size:13.5px; color:var(--muted);">Volunteer · Globe Telecom</div>
                        </div>
                    </div>
                </div>
                <div style="display:flex; justify-content:center; gap:10px; margin-top:24px;">
                    <span style="width:28px; height:9px; border-radius:999px; background:var(--or-500); display:inline-block;"></span>
                    <span style="width:9px; height:9px; border-radius:999px; background:var(--line); display:inline-block;"></span>
                    <span style="width:9px; height:9px; border-radius:999px; background:var(--line); display:inline-block;"></span>
                </div>
            </div>
        </div>
    </section>

    {{-- ── CTA BAND ─────────────────────────────────────────────────── --}}
    <section style="background:linear-gradient(120deg, var(--blue-800), var(--blue-700)); position:relative; overflow:hidden; padding:64px 28px; text-align:center;">
        <h2 style="font-size:clamp(28px,3.4vw,42px); color:#fff; max-width:680px; margin:0 auto 16px;">Ready to make an impact?</h2>
        <p style="color:rgba(255,255,255,.85); font-size:18px; max-width:520px; margin:0 auto 30px;">It takes two minutes to join your first opportunity.</p>
        <div style="display:flex; gap:13px; justify-content:center; flex-wrap:wrap;">
            <a href="{{ url('/admin/events') }}"
               style="display:inline-flex; align-items:center; gap:9px; background:var(--or-500); color:#fff; font-weight:700; font-size:16px; padding:16px 30px; border-radius:999px; box-shadow:0 6px 16px rgba(240,122,30,.28); transition:.16s;"
               onmouseover="this.style.background='var(--or-600)';this.style.transform='translateY(-1px)'"
               onmouseout="this.style.background='var(--or-500)';this.style.transform=''">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Browse opportunities
            </a>
            <a href="{{ route('volunteer.form.view') }}"
               style="display:inline-flex; align-items:center; gap:9px; background:transparent; color:#fff; font-weight:700; font-size:16px; padding:16px 30px; border-radius:999px; border:1.5px solid rgba(255,255,255,.4); transition:.16s;"
               onmouseover="this.style.background='rgba(255,255,255,.12)';this.style.borderColor='rgba(255,255,255,.8)'"
               onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,.4)'">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Become a volunteer
            </a>
        </div>
    </section>

</div>

<style>
@media(max-width:980px){
  .hero-grid{grid-template-columns:1fr!important;gap:36px!important;}
  .hero-visual{order:-1;}
  .stat-grid{grid-template-columns:1fr 1fr!important;gap:28px!important;}
  .cards-3{grid-template-columns:1fr 1fr!important;}
  .two-up{grid-template-columns:1fr!important;}
}
@media(max-width:640px){
  .cards-3{grid-template-columns:1fr!important;}
}
</style>
@endsection
