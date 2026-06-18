@extends('custom.layouts.app')

@section('content')
<style>
/* ── Design tokens ── */
:root {
    --blue-900: #072b54;
    --blue-800: #0a3a6e;
    --blue-700: #0e4f99;
    --blue-600: #1565c4;
    --blue-500: #2a7de0;
    --blue-100: #d6e6f8;
    --blue-50:  #eef4fc;
    --orange-600: #d9650c;
    --orange-500: #f07a1e;
    --orange-400: #f79544;
    --ink:   #15181d;
    --slate: #454c58;
    --muted: #737a87;
    --faint: #9aa1ad;
    --line:      #e6e8ec;
    --line-soft: #eef0f3;
    --bg:     #ffffff;
    --bg-soft: #f6f7f9;
    --r-sm:  8px;
    --r-md:  12px;
    --r-lg:  16px;
    --r-xl:  22px;
    --r-pill: 999px;
    --sh-sm: 0 1px 2px rgba(16,32,56,.06), 0 1px 3px rgba(16,32,56,.05);
    --sh-md: 0 4px 14px rgba(16,32,56,.08), 0 2px 6px rgba(16,32,56,.05);
    --sh-lg: 0 18px 48px rgba(16,32,56,.14), 0 6px 16px rgba(16,32,56,.08);
    --maxw: 1200px;
}

/* ── Layout ── */
.ptn-wrap { max-width: var(--maxw); margin: 0 auto; padding: 0 28px; width: 100%; }

/* ── Hero ── */
.ptn-hero {
    background: var(--blue-700);
    position: relative;
    overflow: hidden;
    padding: 80px 0 100px;
}
.ptn-hero-inner {
    display: grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 48px;
    align-items: center;
}
.ptn-eyebrow {
    font-weight: 700;
    font-size: 12px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--orange-400);
    margin-bottom: 14px;
}
.ptn-hero h1 {
    font-size: clamp(36px, 5vw, 56px);
    font-weight: 800;
    color: #fff;
    line-height: 1.04;
    letter-spacing: -.025em;
    margin: 0;
}
.ptn-hero-desc {
    margin-top: 16px;
    font-size: clamp(15px, 1.3vw, 18px);
    line-height: 1.65;
    color: rgba(255,255,255,.85);
    max-width: 460px;
}
.ptn-hero-stats {
    display: flex;
    gap: 28px;
    margin-top: 32px;
    flex-wrap: wrap;
    align-items: center;
}
.ptn-stat-num {
    font-size: 34px;
    font-weight: 800;
    color: #fff;
    line-height: 1;
    letter-spacing: -.02em;
}
.ptn-stat-label {
    font-size: 13px;
    color: rgba(255,255,255,.7);
    font-weight: 600;
    margin-top: 4px;
}
.ptn-stat-div {
    width: 1px;
    height: 40px;
    background: rgba(255,255,255,.2);
}
.ptn-hero-img {
    border-radius: var(--r-xl);
    overflow: hidden;
    box-shadow: var(--sh-lg);
    height: 340px;
    background: rgba(255,255,255,.08);
    background-image: repeating-linear-gradient(135deg, rgba(255,255,255,.08) 0 14px, transparent 14px 28px);
    display: flex;
    align-items: center;
    justify-content: center;
}
.ptn-hero-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
/* decorative V */
.ptn-hero-deco {
    position: absolute;
    right: -40px;
    bottom: -60px;
    width: 320px;
    height: 320px;
    opacity: .06;
    pointer-events: none;
}

/* ── Search card ── */
.ptn-search-wrap { position: relative; z-index: 10; }
.ptn-search-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: var(--r-xl);
    box-shadow: var(--sh-lg);
    padding: 24px 28px;
    margin-top: -44px;
}
.ptn-search-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 10px;
}
.ptn-search-box {
    position: relative;
}
.ptn-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    color: var(--muted);
    pointer-events: none;
}
.ptn-search-input {
    width: 100%;
    height: 54px;
    padding: 0 48px 0 50px;
    font-family: inherit;
    font-size: 15px;
    color: var(--ink);
    background: var(--bg-soft);
    border: 1.5px solid var(--line);
    border-radius: var(--r-lg);
    transition: .14s;
}
.ptn-search-input:focus {
    outline: none;
    border-color: var(--blue-500);
    box-shadow: 0 0 0 3px var(--blue-50);
    background: #fff;
}
.ptn-search-input::placeholder { color: var(--faint); }
.ptn-search-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: var(--blue-700);
    color: #fff;
    border: none;
    border-radius: var(--r-md);
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    transition: .14s;
}
.ptn-search-btn:hover { background: var(--blue-800); }

/* ── Grid ── */
.ptn-section { padding: 40px 0 76px; background: var(--bg-soft); }
.ptn-grid-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 10px;
}
.ptn-grid-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--ink);
    letter-spacing: -.01em;
    margin: 0;
}
.ptn-grid-hint {
    font-size: 13.5px;
    color: var(--muted);
    font-weight: 600;
}
.ptn-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* ── Partner card ── */
.partner-card {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: var(--r-lg);
    padding: 22px 22px 18px;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    transition: .18s ease;
}
.partner-card:hover {
    box-shadow: var(--sh-lg);
    transform: translateY(-3px);
    border-color: var(--line-soft);
    text-decoration: none;
    color: inherit;
}
.partner-card-head {
    display: flex;
    align-items: center;
    gap: 14px;
}
.partner-logo {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    flex: none;
    overflow: hidden;
    background: var(--blue-50);
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--line);
}
.partner-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 4px;
}
.partner-monogram {
    font-size: 20px;
    font-weight: 800;
    color: var(--blue-700);
    letter-spacing: -.02em;
}
.partner-name {
    font-size: 17px;
    font-weight: 800;
    color: var(--ink);
    line-height: 1.2;
    margin: 0;
}
.partner-sector {
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    margin-top: 3px;
}
.partner-blurb {
    font-size: 13.5px;
    line-height: 1.6;
    color: var(--slate);
    margin-top: 14px;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.partner-card-foot {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid var(--line-soft);
}
.partner-card-link {
    font-size: 13.5px;
    font-weight: 700;
    color: var(--blue-700);
}
.partner-card-go {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    flex: none;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--blue-50);
    color: var(--blue-700);
    transition: .16s ease;
}
.partner-card:hover .partner-card-go {
    background: var(--blue-700);
    color: #fff;
}

/* ── Empty state ── */
.ptn-empty {
    background: #fff;
    border: 1.5px dashed var(--line);
    border-radius: var(--r-lg);
    padding: 64px 24px;
    text-align: center;
    color: var(--slate);
}
.ptn-empty-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: var(--blue-50);
    color: var(--blue-600);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.ptn-empty h3 { font-size: 19px; color: var(--ink); margin: 0 0 8px; }
.ptn-empty p  { font-size: 14.5px; margin: 0 0 20px; }
.ptn-clear-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 700;
    color: var(--blue-700);
    border: 1.5px solid var(--line);
    border-radius: var(--r-pill);
    padding: 10px 20px;
    background: transparent;
    cursor: pointer;
    text-decoration: none;
    transition: .14s;
}
.ptn-clear-btn:hover { border-color: var(--blue-500); background: var(--blue-50); }

/* ── Pagination ── */
.ptn-pagination { margin-top: 40px; }
.ptn-pagination nav { display: flex; justify-content: center; }

/* ── CTA ── */
.ptn-cta {
    background: var(--blue-900);
    position: relative;
    overflow: hidden;
}
.ptn-cta-inner {
    padding: 58px 0;
    display: grid;
    grid-template-columns: 1.4fr auto;
    gap: 32px;
    align-items: center;
}
.ptn-cta h2 {
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 800;
    color: #fff;
    line-height: 1.1;
    margin: 0 0 12px;
    max-width: 520px;
}
.ptn-cta p {
    font-size: 15.5px;
    line-height: 1.65;
    color: rgba(255,255,255,.8);
    max-width: 500px;
    margin: 0;
}
.ptn-cta-btns { display: flex; flex-direction: column; gap: 12px; }
.btn-cta-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: inherit;
    font-size: 15px;
    font-weight: 700;
    border-radius: var(--r-pill);
    padding: 14px 26px;
    background: var(--orange-500);
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 6px 16px rgba(240,122,30,.32);
    transition: .16s;
    white-space: nowrap;
}
.btn-cta-primary:hover { background: var(--orange-600); transform: translateY(-1px); color: #fff; }
.btn-cta-ghost {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: inherit;
    font-size: 15px;
    font-weight: 700;
    border-radius: var(--r-pill);
    padding: 13px 26px;
    background: transparent;
    color: #fff;
    border: 1.5px solid rgba(255,255,255,.35);
    cursor: pointer;
    text-decoration: none;
    transition: .16s;
    white-space: nowrap;
}
.btn-cta-ghost:hover { background: rgba(255,255,255,.1); border-color: #fff; color: #fff; }

/* ── Responsive ── */
@media (max-width: 980px) {
    .ptn-hero-inner { grid-template-columns: 1fr; gap: 32px; }
    .ptn-hero-img   { display: none; }
    .ptn-grid       { grid-template-columns: repeat(2, 1fr); }
    .ptn-cta-inner  { grid-template-columns: 1fr; gap: 24px; }
    .ptn-cta-btns   { flex-direction: row; flex-wrap: wrap; }
}
@media (max-width: 680px) {
    .ptn-wrap { padding: 0 18px; }
    .ptn-grid { grid-template-columns: 1fr; }
    .ptn-hero { padding: 56px 0 80px; }
}
</style>

{{-- ── HERO ── --}}
<section class="ptn-hero">
    <div class="ptn-wrap">
        <div class="ptn-hero-inner">
            <div>
                <div class="ptn-eyebrow">Our Network</div>
                <h1>Our Partners</h1>
                <p class="ptn-hero-desc">
                    Explore the Ayala Corporate Citizenship &amp; Volunteer Program. Pick a partner organization to discover volunteer opportunities they have open right now.
                </p>
                <div class="ptn-hero-stats">
                    <div>
                        <div class="ptn-stat-num">{{ $partners->total() }}</div>
                        <div class="ptn-stat-label">Partner organizations</div>
                    </div>
                    <div class="ptn-stat-div"></div>
                    <div>
                        <div class="ptn-stat-num">{{ $totalEvents }}</div>
                        <div class="ptn-stat-label">Open opportunities</div>
                    </div>
                </div>
            </div>
            <div class="ptn-hero-img">
                <img src="{{ asset('img/ayala-foundation-bg-2.jpg') }}" alt="Partners working together">
            </div>
        </div>
    </div>
    {{-- decorative V --}}
    <svg class="ptn-hero-deco" viewBox="0 0 40 40" aria-hidden="true">
        <path d="M5 7 L11 7 L22 32 L16 32 Z" fill="#fff"/>
        <path d="M35 7 L29 7 L18 32 L24 32 Z" fill="#fff"/>
    </svg>
</section>

{{-- ── SEARCH CARD ── --}}
<div class="ptn-wrap ptn-search-wrap">
    <div class="ptn-search-card">
        <div class="ptn-search-label">Search business partner unit</div>
        <form action="{{ route('ourpartners.view') }}" method="GET">
            <div class="ptn-search-box">
                <svg class="ptn-search-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input
                    class="ptn-search-input"
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by organization name or category"
                    autocomplete="off"
                />
                <button type="submit" class="ptn-search-btn">Search</button>
            </div>
        </form>
    </div>
</div>

{{-- ── GRID ── --}}
<section class="ptn-section">
    <div class="ptn-wrap">
        <div class="ptn-grid-header">
            <h2 class="ptn-grid-title">
                @if(request('search'))
                    {{ $partners->total() }} {{ Str::plural('partner', $partners->total()) }} found
                @else
                    All partners
                @endif
            </h2>
            @unless(request('search'))
                <span class="ptn-grid-hint">Tap a partner to view their page</span>
            @endunless
        </div>

        @if($partners->isEmpty())
            <div class="ptn-empty">
                <div class="ptn-empty-icon">
                    <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <h3>No partners match "{{ request('search') }}"</h3>
                <p>Try a different organization name or category.</p>
                <a href="{{ route('ourpartners.view') }}" class="ptn-clear-btn">Clear search</a>
            </div>
        @else
            <div class="ptn-grid">
                @foreach($partners as $partner)
                    @php
                        $logo = $partner->getMedia('bu_logo')->first()?->getUrl();
                        $words = collect(explode(' ', $partner->name))
                            ->filter(fn($w) => !in_array(strtolower($w), ['of','the','and']))
                            ->values();
                        $monogram = $words->count() === 1
                            ? strtoupper(substr($words[0], 0, 2))
                            : strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                        $sector = $partner->nickname ?? $partner->cluster?->name ?? $partner->company?->name ?? '';
                    @endphp
                    <a href="{{ route('businessunit.homepage.view', $partner->slug) }}" class="partner-card">
                        <div class="partner-card-head">
                            <div class="partner-logo">
                                @if($logo)
                                    <img src="{{ $logo }}" alt="{{ $partner->name }}">
                                @else
                                    <span class="partner-monogram">{{ $monogram }}</span>
                                @endif
                            </div>
                            <div>
                                <p class="partner-name">{{ $partner->name }}</p>
                                @if($sector)
                                    <div class="partner-sector">{{ $sector }}</div>
                                @endif
                            </div>
                        </div>

                        @if($partner->about)
                            <p class="partner-blurb">{{ $partner->about }}</p>
                        @elseif($partner->header_tagline)
                            <p class="partner-blurb">{{ $partner->header_tagline }}</p>
                        @endif

                        <div class="partner-card-foot">
                            <span class="partner-card-link">
                                {{ $partner->opportunity_count }}
                                {{ Str::plural('opportunity', $partner->opportunity_count) }}
                            </span>
                            <span class="partner-card-go">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="ptn-pagination">
                {{ $partners->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</section>

{{-- ── BECOME A PARTNER CTA ── --}}
<section class="ptn-cta">
    <div class="ptn-wrap">
        <div class="ptn-cta-inner">
            <div>
                <div class="ptn-eyebrow" style="color:var(--orange-400);margin-bottom:10px;">For organizations</div>
                <h2>Bring your team's purpose to life. Become a partner.</h2>
                <p>List your volunteer programs, mobilize your employees, and measure your social impact — all in one place.</p>
            </div>
            <div class="ptn-cta-btns">
                <a href="mailto:volunteerprogram@ayala.com.ph" class="btn-cta-primary">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 3a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6a3 3 0 0 1 3-3zM3 6l9 7 9-7"/>
                    </svg>
                    Become a partner
                </a>
                <a href="mailto:volunteerprogram@ayala.com.ph" class="btn-cta-ghost">
                    Talk to our team
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
