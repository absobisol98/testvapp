@extends('custom.layouts.app')

@section('title'){{ $business_unit->nickname }}@endsection

@section('content')
<style>
/* ── Design tokens (shared with Our Partners page) ── */
:root {
    --blue-900: #072b54; --blue-800: #0a3a6e; --blue-700: #0e4f99;
    --blue-600: #1565c4; --blue-500: #2a7de0; --blue-100: #d6e6f8; --blue-50: #eef4fc;
    --orange-600: #d9650c; --orange-500: #f07a1e; --orange-400: #f79544; --orange-50: #fef2e7;
    --ink: #15181d; --slate: #454c58; --muted: #737a87; --faint: #9aa1ad;
    --line: #e6e8ec; --line-soft: #eef0f3; --bg: #ffffff; --bg-soft: #f6f7f9;
    --green-600: #1d8a52; --green-50: #e8f5ee;
    --r-sm: 8px; --r-md: 12px; --r-lg: 16px; --r-xl: 22px; --r-pill: 999px;
    --sh-sm: 0 1px 2px rgba(16,32,56,.06), 0 1px 3px rgba(16,32,56,.05);
    --sh-md: 0 4px 14px rgba(16,32,56,.08), 0 2px 6px rgba(16,32,56,.05);
    --sh-lg: 0 18px 48px rgba(16,32,56,.14), 0 6px 16px rgba(16,32,56,.08);
    --maxw: 1200px;
}
.bu-wrap { max-width: var(--maxw); margin: 0 auto; padding: 0 28px; width: 100%; }

/* ── Hero ── */
.bu-hero {
    position: relative; overflow: hidden; min-height: 520px;
    display: flex; align-items: flex-end;
    background: var(--blue-900) center/cover no-repeat;
}
.bu-hero-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(7,43,84,.92) 0%, rgba(7,43,84,.55) 50%, rgba(7,43,84,.25) 100%);
}
.bu-hero-content {
    position: relative; z-index: 2; width: 100%;
    padding: 60px 0 52px;
}
.bu-hero-top {
    display: flex; align-items: flex-end; gap: 28px; flex-wrap: wrap;
}
.bu-logo-wrap {
    width: 96px; height: 96px; border-radius: var(--r-xl);
    background: rgba(255,255,255,.12); backdrop-filter: blur(6px);
    border: 2px solid rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; flex: none;
}
.bu-logo-wrap img { width: 100%; height: 100%; object-fit: contain; padding: 8px; }
.bu-hero-text { flex: 1; min-width: 0; }
.bu-hero-eyebrow {
    font-size: 11px; font-weight: 700; letter-spacing: .14em;
    text-transform: uppercase; color: var(--orange-400); margin-bottom: 10px;
}
.bu-hero-title {
    font-size: clamp(28px, 4vw, 48px); font-weight: 800; color: #fff;
    line-height: 1.06; letter-spacing: -.02em; margin: 0 0 10px;
}
.bu-hero-desc {
    font-size: 16px; line-height: 1.65; color: rgba(255,255,255,.82);
    max-width: 620px; margin: 0;
}
.bu-hero-stats {
    display: flex; gap: 28px; margin-top: 32px; flex-wrap: wrap; align-items: center;
}
.bu-stat-num { font-size: 36px; font-weight: 800; color: #fff; line-height: 1; letter-spacing: -.02em; }
.bu-stat-label { font-size: 12.5px; color: rgba(255,255,255,.65); font-weight: 600; margin-top: 4px; }
.bu-stat-div { width: 1px; height: 42px; background: rgba(255,255,255,.2); }
.bu-hero-cta {
    display: inline-flex; align-items: center; gap: 9px;
    margin-top: 32px; font-family: inherit; font-size: 15px; font-weight: 700;
    background: var(--orange-500); color: #fff; border: none; border-radius: var(--r-pill);
    padding: 13px 26px; cursor: pointer; text-decoration: none;
    box-shadow: 0 6px 18px rgba(240,122,30,.34); transition: .16s;
}
.bu-hero-cta:hover { background: var(--orange-600); transform: translateY(-1px); color: #fff; }

/* ── Back link ── */
.bu-back {
    display: inline-flex; align-items: center; gap: 7px;
    color: rgba(255,255,255,.7); font-size: 13.5px; font-weight: 600;
    text-decoration: none; margin-bottom: 22px; transition: .14s;
}
.bu-back:hover { color: #fff; }

/* ── Section commons ── */
.bu-section { padding: 56px 0; }
.bu-section-title {
    font-size: 22px; font-weight: 800; color: var(--ink);
    letter-spacing: -.01em; margin: 0 0 22px;
}
.bu-divider { height: 1px; background: var(--line); margin: 0 0 24px; }

/* ── Gallery + Upcoming ── */
.bu-spotlight {
    background: var(--bg-soft); padding: 48px 0;
}
.bu-spotlight-inner {
    display: grid; grid-template-columns: 1.4fr 1fr; gap: 28px; align-items: start;
}
.bu-gallery-swiper {
    border-radius: var(--r-lg); overflow: hidden; position: relative;
}
.bu-gallery-slide { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.bu-gallery-img {
    height: 220px; border-radius: var(--r-md); overflow: hidden;
    background: var(--blue-50); position: relative;
}
.bu-gallery-img img { width: 100%; height: 100%; object-fit: cover; }
.bu-swiper-nav {
    position: absolute; top: 50%; transform: translateY(-50%);
    display: flex; justify-content: space-between; width: 100%; padding: 0 10px;
    z-index: 10; pointer-events: none;
}
.bu-nav-btn {
    width: 34px; height: 34px; border-radius: 50%; background: #fff;
    box-shadow: var(--sh-sm); border: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; pointer-events: all; transition: .14s;
}
.bu-nav-btn:hover { box-shadow: var(--sh-md); }
.bu-nav-btn svg { width: 16px; height: 16px; color: var(--ink); }

/* ── Upcoming card ── */
.bu-upcoming-card {
    background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg);
    overflow: hidden;
}
.bu-upcoming-banner {
    height: 160px; background: var(--blue-50) center/cover no-repeat;
}
.bu-upcoming-body { padding: 20px; }
.bu-upcoming-label {
    font-size: 10.5px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
    color: var(--orange-600); margin-bottom: 10px;
}
.bu-upcoming-title {
    font-size: 18px; font-weight: 800; color: var(--ink);
    line-height: 1.25; margin: 0 0 12px; letter-spacing: -.01em;
}
.bu-meta { display: flex; align-items: flex-start; gap: 7px; color: var(--slate); font-size: 13px; font-weight: 500; margin-bottom: 7px; }
.bu-meta svg { width: 14px; height: 14px; color: var(--muted); flex: none; margin-top: 1px; }
.bu-upcoming-actions { display: flex; gap: 10px; margin-top: 18px; }
.bu-btn-primary {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-family: inherit; font-size: 14px; font-weight: 700; border-radius: var(--r-pill);
    padding: 11px 18px; background: var(--orange-500); color: #fff; border: none;
    cursor: pointer; text-decoration: none; transition: .14s;
}
.bu-btn-primary:hover { background: var(--orange-600); color: #fff; }
.bu-btn-ghost {
    flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 7px;
    font-family: inherit; font-size: 14px; font-weight: 700; border-radius: var(--r-pill);
    padding: 10px 18px; background: transparent; color: var(--blue-700);
    border: 1.5px solid var(--line); cursor: pointer; text-decoration: none; transition: .14s;
}
.bu-btn-ghost:hover { border-color: var(--blue-500); background: var(--blue-50); }

/* ── Opportunity cards ── */
.bu-op-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
.bu-op-card {
    background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg);
    overflow: hidden; display: flex; flex-direction: column; transition: .18s;
}
.bu-op-card:hover { box-shadow: var(--sh-lg); transform: translateY(-3px); border-color: var(--line-soft); }
.bu-op-banner { height: 140px; background: var(--bg-soft) center/cover no-repeat; }
.bu-op-body { padding: 16px 18px 18px; flex: 1; display: flex; flex-direction: column; }
.bu-op-title { font-size: 15px; font-weight: 800; color: var(--ink); margin: 0 0 8px; line-height: 1.3; }
.bu-op-foot { margin-top: auto; padding-top: 14px; border-top: 1px solid var(--line-soft); display: flex; align-items: center; justify-content: space-between; gap: 10px; }
.bu-op-date { font-size: 12.5px; font-weight: 600; color: var(--muted); }
.bu-op-join {
    display: inline-flex; align-items: center; gap: 6px;
    font-family: inherit; font-size: 12.5px; font-weight: 700;
    background: var(--blue-700); color: #fff; border: none; border-radius: var(--r-pill);
    padding: 7px 14px; cursor: pointer; text-decoration: none; transition: .14s; white-space: nowrap;
}
.bu-op-join:hover { background: var(--blue-800); color: #fff; }
.bu-op-join.done { background: var(--line); color: var(--muted); cursor: not-allowed; }

/* ── About section ── */
.bu-about { background: #fff; padding: 56px 0; }
.bu-about-inner { display: grid; grid-template-columns: 1fr 1.5fr; gap: 56px; align-items: start; }
.bu-about-label { font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--orange-600); margin-bottom: 12px; }
.bu-about-name { font-size: clamp(26px, 3vw, 38px); font-weight: 800; color: var(--ink); line-height: 1.08; margin: 0 0 18px; }
.bu-about-text { font-size: 15.5px; line-height: 1.75; color: var(--slate); margin: 0 0 20px; }
.bu-socials { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 6px; }
.bu-social-link {
    width: 38px; height: 38px; border-radius: 50%;
    background: var(--bg-soft); border: 1px solid var(--line);
    display: flex; align-items: center; justify-content: center;
    color: var(--slate); text-decoration: none; transition: .14s;
}
.bu-social-link:hover { background: var(--blue-50); border-color: var(--blue-300); color: var(--blue-700); }
.bu-website-link { font-size: 14px; font-weight: 600; color: var(--blue-600); word-break: break-all; text-decoration: none; }
.bu-website-link:hover { text-decoration: underline; }

/* ── Featured events carousel ── */
.bu-featured { background: var(--blue-900); padding: 56px 0; overflow: hidden; }
.bu-featured-slide {
    display: grid; grid-template-columns: 1fr 1fr;
    min-height: 420px; border-radius: var(--r-xl); overflow: hidden;
}
.bu-featured-img { background: var(--blue-800) center/cover no-repeat; }
.bu-featured-body {
    background: #fff; padding: 48px 44px;
    display: flex; flex-direction: column; justify-content: center;
}
.bu-featured-title { font-size: clamp(22px, 2.5vw, 32px); font-weight: 800; color: var(--ink); line-height: 1.12; margin: 0 0 16px; }
.bu-featured-desc { font-size: 15px; line-height: 1.7; color: var(--slate); margin: 0 0 24px; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden; }
.bu-featured-btn {
    display: inline-flex; align-items: center; gap: 8px;
    font-family: inherit; font-size: 14px; font-weight: 700;
    color: var(--blue-700); border: 1.5px solid var(--line);
    border-radius: var(--r-pill); padding: 11px 22px;
    background: transparent; cursor: pointer; text-decoration: none; transition: .14s;
}
.bu-featured-btn:hover { border-color: var(--blue-500); background: var(--blue-50); }

/* ── No-content placeholders ── */
.bu-empty {
    background: var(--bg-soft); border: 1.5px dashed var(--line);
    border-radius: var(--r-lg); padding: 48px 24px; text-align: center;
    color: var(--muted); font-size: 14.5px;
}

/* ── Modal ── */
.bu-modal-backdrop {
    position: fixed; inset: 0; z-index: 200;
    background: rgba(7,43,84,.7); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center; padding: 24px;
}
.bu-modal {
    background: #fff; border-radius: var(--r-xl); overflow: hidden;
    max-width: 760px; width: 100%; max-height: 90vh; overflow-y: auto;
    box-shadow: var(--sh-lg);
    transform: scale(.96); opacity: 0;
    transition: transform .22s ease, opacity .22s ease;
}
.bu-modal.open { transform: scale(1); opacity: 1; }
.bu-modal-banner { height: 280px; background: var(--blue-50) center/cover no-repeat; position: relative; }
.bu-modal-banner-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(7,43,84,.8), transparent 55%);
    display: flex; align-items: flex-end; padding: 20px;
}
.bu-modal-body { padding: 28px 32px 32px; }
.bu-modal-type {
    display: inline-flex; align-items: center; padding: 5px 12px;
    background: var(--orange-50); color: var(--orange-600);
    border-radius: var(--r-pill); font-size: 12px; font-weight: 700;
    letter-spacing: .04em; text-transform: uppercase; margin-bottom: 14px;
}
.bu-modal-title { font-size: clamp(20px, 2.5vw, 28px); font-weight: 800; color: var(--ink); margin: 0 0 8px; }
.bu-modal-location { font-size: 14.5px; color: var(--slate); margin: 0 0 20px; }
.bu-modal-desc { font-size: 15px; line-height: 1.75; color: var(--slate); margin: 0 0 24px; }
.bu-modal-shifts { background: var(--bg-soft); border-radius: var(--r-md); padding: 16px 20px; margin-bottom: 24px; }
.bu-modal-shift-row { font-size: 13.5px; color: var(--slate); margin-bottom: 6px; }
.bu-modal-shift-row:last-child { margin-bottom: 0; }
.bu-modal-actions { display: flex; gap: 12px; }
.bu-modal-close {
    position: absolute; top: 16px; right: 16px; z-index: 10;
    width: 36px; height: 36px; border-radius: 50%;
    background: rgba(255,255,255,.9); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: var(--sh-sm); transition: .14s;
}
.bu-modal-close:hover { background: #fff; }

/* ── Responsive ── */
@media (max-width: 1024px) {
    .bu-spotlight-inner { grid-template-columns: 1fr; }
    .bu-about-inner { grid-template-columns: 1fr; gap: 32px; }
    .bu-featured-slide { grid-template-columns: 1fr; min-height: auto; }
    .bu-featured-img { height: 240px; }
    .bu-op-grid { grid-template-columns: 1fr; }
}
@media (max-width: 680px) {
    .bu-wrap { padding: 0 18px; }
    .bu-gallery-slide { grid-template-columns: 1fr; }
    .bu-gallery-img { height: 180px; }
    .bu-featured-body { padding: 28px 24px; }
    .bu-modal-body { padding: 20px; }
}
</style>

{{-- ── HERO ── --}}
<section class="bu-hero" style="background-image: url('{{ $eventCover }}')">
    <div class="bu-hero-overlay"></div>
    <div class="bu-wrap">
        <div class="bu-hero-content">
            <a href="{{ route('ourpartners.view') }}" class="bu-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                All partners
            </a>
            <div class="bu-hero-top">
                @if($logo)
                    <div class="bu-logo-wrap"><img src="{{ $logo }}" alt="{{ $business_unit->name }}"></div>
                @endif
                <div class="bu-hero-text">
                    <div class="bu-hero-eyebrow">{{ $business_unit->nickname ?? $business_unit->cluster?->name }}</div>
                    <h1 class="bu-hero-title">{{ $business_unit->header_tagline ?? $business_unit->name }}</h1>
                    <p class="bu-hero-desc">{{ $business_unit->header_description }}</p>
                </div>
            </div>

            <div class="bu-hero-stats">
                <div>
                    <div class="bu-stat-num">{{ $opportunities->count() }}</div>
                    <div class="bu-stat-label">Opportunities</div>
                </div>
                <div class="bu-stat-div"></div>
                <div>
                    <div class="bu-stat-num">{{ $total_volunteers }}</div>
                    <div class="bu-stat-label">Volunteers served</div>
                </div>
            </div>

            <a href="#opportunities" class="bu-hero-cta">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M7 17l10-10M17 7H7v10"/></svg>
                See opportunities
            </a>
        </div>
    </div>
</section>

{{-- ── GALLERY + UPCOMING ── --}}
<section class="bu-spotlight" id="opportunities">
    <div class="bu-wrap">
        <div class="bu-spotlight-inner">

            {{-- Gallery carousel --}}
            <div>
                <h2 class="bu-section-title">Event Gallery</h2>
                @if(count($galleries) > 0)
                    <div class="bu-gallery-swiper" style="position:relative;">
                        <div class="swiper program-swiper-container2" style="border-radius:var(--r-lg);overflow:hidden;">
                            <div class="swiper-wrapper">
                                @foreach($galleries as $chunk)
                                    <div class="swiper-slide">
                                        <div class="bu-gallery-slide">
                                            @foreach($chunk as $img)
                                                <div class="bu-gallery-img">
                                                    <img src="{{ $img }}" alt="Gallery image">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="bu-swiper-nav">
                            <button class="bu-nav-btn program-button-36-prev2">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                            </button>
                            <button class="bu-nav-btn program-button-36-next2">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="bu-empty">No gallery images uploaded yet.</div>
                @endif
            </div>

            {{-- Upcoming opportunity --}}
            <div>
                <h2 class="bu-section-title">Upcoming Opportunity</h2>
                @if($upcoming)
                    <div class="bu-upcoming-card">
                        @if($upcoming_banner)
                            <div class="bu-upcoming-banner" style="background-image:url('{{ $upcoming_banner }}')"></div>
                        @endif
                        <div class="bu-upcoming-body">
                            <div class="bu-upcoming-label">Next up</div>
                            <p class="bu-upcoming-title">{{ $upcoming->title }}</p>
                            @if($upcoming->location)
                                <div class="bu-meta">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
                                    {{ $upcoming->location }}
                                </div>
                            @endif
                            <div class="bu-meta">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                {{ \Carbon\Carbon::parse($upcoming->start_date)->format('M d, Y') }}
                                &nbsp;·&nbsp;
                                {{ \Carbon\Carbon::parse($upcoming->start_date)->format('g:i A') }}
                            </div>
                            @if($upcoming->slots->isNotEmpty())
                                <div class="bu-meta" style="flex-direction:column; align-items:flex-start;">
                                    @foreach($upcoming->slots as $i => $slot)
                                        <span style="font-size:12.5px;color:var(--muted);">
                                            Batch {{ $i+1 }}: {{ $slot->shift_name }}
                                            ({{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }})
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="bu-upcoming-actions">
                                <a href="{{ route('filament.admin.resources.events.view', ['record' => $upcoming->id]) }}" class="bu-btn-primary">Join</a>
                                <button onclick="openModal()" class="bu-btn-ghost">View details</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bu-empty">No upcoming opportunities right now — check back soon.</div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── ALL OPPORTUNITIES ── --}}
<section class="bu-section" style="background:var(--bg);">
    <div class="bu-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:22px;">
            <h2 class="bu-section-title" style="margin:0;">All Opportunities</h2>
            <span style="font-size:13.5px;color:var(--muted);font-weight:600;">{{ $opportunities->count() }} total</span>
        </div>
        <div class="bu-divider"></div>

        @if($opportunities->isEmpty())
            <div class="bu-empty">No opportunities listed for this partner yet.</div>
        @else
            <div class="bu-op-grid">
                @foreach($opportunities as $op)
                    @php $done = \Carbon\Carbon::parse($op->start_date)->lt(now()); @endphp
                    <div class="bu-op-card">
                        <div class="bu-op-banner" style="background-image:url('{{ $op->getBanner() }}')"></div>
                        <div class="bu-op-body">
                            <p class="bu-op-title">{{ $op->title }}</p>
                            @if($op->location)
                                <div class="bu-meta" style="margin-bottom:0;">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z"/></svg>
                                    {{ $op->location }}
                                </div>
                            @endif
                            <div class="bu-op-foot">
                                <span class="bu-op-date">
                                    {{ \Carbon\Carbon::parse($op->start_date)->format('M d, Y') }}
                                </span>
                                @if($done)
                                    <span class="bu-op-join done">Completed</span>
                                @else
                                    <a href="{{ route('filament.admin.resources.events.view', ['record' => $op->id]) }}" class="bu-op-join">
                                        Join
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- ── ABOUT ── --}}
<section class="bu-about">
    <div class="bu-wrap">
        <div class="bu-about-inner">
            <div>
                <div class="bu-about-label">About</div>
                <h2 class="bu-about-name">{{ $business_unit->nickname ?? $business_unit->name }}</h2>
                <p class="bu-about-text">{!! nl2br(e($business_unit->about ?? 'No description available.')) !!}</p>

                @if($website)
                    <a href="{{ $website->link }}" target="_blank" class="bu-website-link">{{ $website->link }}</a>
                @endif

                @if($socials->where('social', '!=', 'website')->isNotEmpty())
                    <div class="bu-socials" style="margin-top:18px;">
                        @foreach($socials->where('social', '!=', 'website') as $social)
                            <a href="{{ $social->link }}" target="_blank" class="bu-social-link" title="{{ ucfirst($social->social) }}">
                                @include('custom.icons.landing-page-icons', ['icon' => $social->social])
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Partner info card --}}
            <div style="background:var(--bg-soft);border:1px solid var(--line);border-radius:var(--r-xl);padding:28px 28px 32px;">
                @if($logo)
                    <img src="{{ $logo }}" alt="{{ $business_unit->name }}" style="height:64px;object-fit:contain;margin-bottom:20px;">
                @endif
                <p style="font-size:20px;font-weight:800;color:var(--ink);margin:0 0 6px;">{{ $business_unit->name }}</p>
                @if($business_unit->cluster)
                    <p style="font-size:13px;font-weight:600;color:var(--muted);margin:0 0 18px;">{{ $business_unit->cluster->name }}</p>
                @endif
                <div style="height:1px;background:var(--line);margin:0 0 20px;"></div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                    <div>
                        <div style="font-size:26px;font-weight:800;color:var(--ink);line-height:1;">{{ $opportunities->count() }}</div>
                        <div style="font-size:12px;color:var(--muted);font-weight:600;margin-top:4px;">Opportunities</div>
                    </div>
                    <div>
                        <div style="font-size:26px;font-weight:800;color:var(--orange-600);line-height:1;">{{ $total_volunteers }}</div>
                        <div style="font-size:12px;color:var(--muted);font-weight:600;margin-top:4px;">Volunteers served</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FEATURED EVENTS ── --}}
@if($featuredEvents->isNotEmpty())
<section class="bu-featured">
    <div class="bu-wrap">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
            <div>
                <div style="font-size:11px;font-weight:700;letter-spacing:.14em;text-transform:uppercase;color:var(--orange-400);margin-bottom:8px;">Highlights</div>
                <h2 style="font-size:22px;font-weight:800;color:#fff;margin:0;">Featured Events</h2>
            </div>
            <div style="display:flex;gap:10px;">
                <button class="bu-nav-btn program-button-36-prev" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);">
                    <svg fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="bu-nav-btn program-button-36-next" style="background:rgba(255,255,255,.12);border-color:rgba(255,255,255,.2);">
                    <svg fill="none" stroke="white" stroke-width="2.5" viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
        <div class="swiper program-swiper-container" style="border-radius:var(--r-xl);overflow:hidden;">
            <div class="swiper-wrapper">
                @foreach($featuredEvents as $featured)
                    <div class="swiper-slide">
                        <div class="bu-featured-slide">
                            <div class="bu-featured-img" style="background-image:url('{{ $featured->getBanner() }}')"></div>
                            <div class="bu-featured-body">
                                <div style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--orange-600);margin-bottom:12px;">Featured</div>
                                <p class="bu-featured-title">{{ $featured->title }}</p>
                                <p class="bu-featured-desc">{!! strip_tags($featured->description ?? '') !!}</p>
                                @if(auth()->check())
                                    <a href="{{ route('filament.admin.resources.events.view', ['record' => $featured->id]) }}" class="bu-featured-btn">
                                        View event
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    </a>
                                @else
                                    <a href="{{ route('volunteer.form.view') }}" class="bu-featured-btn">Sign up to join</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ── UPCOMING DETAILS MODAL ── --}}
@if($upcoming)
<div id="featuredImageModal" class="bu-modal-backdrop" style="display:none;" onclick="closeModal(event)">
    <div class="bu-modal" id="buModalContent">
        <div class="bu-modal-banner" style="background-image:url('{{ $upcoming_banner }}');position:relative;">
            <div class="bu-modal-banner-overlay">
                @if($logo)<img src="{{ $logo }}" alt="" style="height:40px;object-fit:contain;">@endif
            </div>
            <button class="bu-modal-close" id="closeModal" onclick="closeModal(event)">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="bu-modal-body">
            @if($upcoming->event_type)
                <span class="bu-modal-type">{{ $upcoming->event_type->name }}</span>
            @endif
            <p class="bu-modal-title">{{ $upcoming->title }}</p>
            @if($upcoming->location)
                <p class="bu-modal-location">{{ $upcoming->location }}</p>
            @endif
            <p class="bu-modal-desc">{!! nl2br(e($upcoming->description ?? '')) !!}</p>
            <div class="bu-modal-shifts">
                <div style="font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:12px;">Schedule</div>
                <div class="bu-modal-shift-row">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($upcoming->start_date)->format('F d, Y') }}
                    &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse($upcoming->start_date)->format('g:i A') }}
                </div>
                @foreach($upcoming->slots as $i => $slot)
                    <div class="bu-modal-shift-row">
                        <strong>Batch {{ $i+1 }} — {{ $slot->shift_name }}:</strong>
                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                    </div>
                @endforeach
            </div>
            <div class="bu-modal-actions">
                <a href="{{ route('filament.admin.resources.events.view', ['record' => $upcoming->id]) }}" class="bu-btn-primary" style="flex:none;padding:13px 28px;">
                    Join this opportunity
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function openModal() {
    const backdrop = document.getElementById('featuredImageModal');
    const modal = document.getElementById('buModalContent');
    backdrop.style.display = 'flex';
    setTimeout(() => modal.classList.add('open'), 10);
}
function closeModal(e) {
    const backdrop = document.getElementById('featuredImageModal');
    const modal = document.getElementById('buModalContent');
    if (e.target === backdrop || e.target.closest('#closeModal')) {
        modal.classList.remove('open');
        setTimeout(() => backdrop.style.display = 'none', 220);
    }
}

const programSwiper = new Swiper('.program-swiper-container', {
    loop: true, slidesPerView: 1,
    navigation: { nextEl: '.program-button-36-next', prevEl: '.program-button-36-prev' },
});
const programSwiper2 = new Swiper('.program-swiper-container2', {
    loop: true, autoplay: { delay: 3500 }, slidesPerView: 1,
    navigation: { nextEl: '.program-button-36-next2', prevEl: '.program-button-36-prev2' },
});
</script>
@endsection
