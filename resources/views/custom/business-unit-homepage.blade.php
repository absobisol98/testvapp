@extends('custom.layouts.app')

@section('title'){{ $business_unit->nickname ?? $business_unit->name }}@endsection

@section('content')
<style>
/* ── Design tokens (matches Partners Page Redesign) ── */
:root {
    --blue-900:#072b54; --blue-800:#0a3a6e; --blue-700:#0e4f99; --blue-600:#1565c4;
    --blue-500:#2a7de0; --blue-400:#4a93e8; --blue-100:#d6e6f8; --blue-50:#eef4fc;
    --orange-600:#d9650c; --orange-500:#f07a1e; --orange-400:#f79544; --orange-50:#fef2e7;
    --ink:#15181d; --slate:#454c58; --muted:#737a87; --faint:#9aa1ad;
    --line:#e6e8ec; --line-soft:#eef0f3; --bg:#ffffff; --bg-soft:#f6f7f9;
    --green-600:#1d8a52; --green-50:#e8f5ee;
    --r-sm:8px; --r-md:12px; --r-lg:16px; --r-xl:22px; --r-pill:999px;
    --sh-sm:0 1px 2px rgba(16,32,56,.06),0 1px 3px rgba(16,32,56,.05);
    --sh-md:0 4px 14px rgba(16,32,56,.08),0 2px 6px rgba(16,32,56,.05);
    --sh-lg:0 18px 48px rgba(16,32,56,.14),0 6px 16px rgba(16,32,56,.08);
    --maxw:1200px;
}
.pd-wrap { max-width: var(--maxw); margin: 0 auto; padding: 0 28px; width: 100%; }

/* ── Back link ── */
.pd-back {
    display: inline-flex; align-items: center; gap: 7px;
    color: var(--muted); font-size: 14px; font-weight: 600;
    text-decoration: none; margin-bottom: 24px; transition: .14s;
    background: none; border: none; cursor: pointer; padding: 0;
}
.pd-back:hover { color: var(--ink); }

/* ── Partner header ── */
.pd-hero { background: #fff; border-bottom: 1px solid var(--line); }
.pd-hero-inner { padding: 34px 0 40px; }
.pd-hero-row {
    display: flex; align-items: flex-start; flex-wrap: wrap; gap: 24px;
}
/* Wordmark / logo tile */
.pd-wordmark {
    width: 88px; height: 88px; border-radius: 20px; flex: none;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 800; letter-spacing: -.02em;
    overflow: hidden;
}
.pd-wordmark img { width: 100%; height: 100%; object-fit: contain; padding: 8px; }
.pd-hero-body { flex: 1; min-width: 260px; }
.pd-eyebrow {
    font-size: 13px; font-weight: 700; letter-spacing: .04em;
    text-transform: uppercase; color: var(--orange-600); margin-bottom: 8px;
}
.pd-name {
    font-size: clamp(28px, 3.6vw, 42px); font-weight: 800; color: var(--ink);
    line-height: 1.06; letter-spacing: -.02em; margin: 0 0 14px;
}
.pd-blurb {
    font-size: 16.5px; line-height: 1.6; color: var(--slate); margin: 0 0 18px; max-width: 660px;
}
.pd-causes { display: flex; flex-wrap: wrap; gap: 8px; }
.pd-cause-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; padding: 5px 11px; border-radius: var(--r-pill);
    background: var(--bg-soft); color: var(--slate); border: 1px solid var(--line);
}

/* ── Stats block ── */
.pd-stats { display: flex; gap: 28px; padding-top: 6px; flex: none; }
.pd-stat-num {
    font-size: 30px; font-weight: 800; line-height: 1; letter-spacing: -.02em; color: var(--ink);
}
.pd-stat-num.orange { color: var(--orange-600); }
.pd-stat-label { font-size: 13px; color: var(--muted); font-weight: 600; margin-top: 5px; }

/* ── Opportunities section ── */
.pd-ops { background: var(--bg-soft); padding: 40px 0 80px; }
.pd-ops-title {
    font-size: 22px; font-weight: 800; color: var(--ink);
    letter-spacing: -.01em; margin: 0 0 22px;
}
.pd-op-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }

/* ── Opportunity card ── */
.pd-card {
    background: #fff; border: 1px solid var(--line); border-radius: var(--r-lg);
    overflow: hidden; display: flex; flex-direction: column; transition: .18s ease;
    text-decoration: none; color: inherit;
}
.pd-card:hover { box-shadow: var(--sh-lg); transform: translateY(-3px); border-color: var(--line-soft); }
.pd-card-img { height: 172px; position: relative; overflow: hidden; background: var(--bg-soft); }
.pd-card-img img { width: 100%; height: 100%; object-fit: cover; }
.pd-card-img-ph {
    width: 100%; height: 100%;
    background-image: repeating-linear-gradient(135deg, rgba(14,79,153,.06) 0 12px, rgba(14,79,153,0) 12px 24px);
}
.pd-cat-badge {
    position: absolute; top: 12px; left: 12px;
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700; padding: 5px 11px; border-radius: var(--r-pill);
    background: var(--blue-700); color: #fff;
}
.pd-card-body {
    padding: 17px 18px 18px; display: flex; flex-direction: column; gap: 12px; flex: 1;
}
.pd-org-label { font-size: 12.5px; font-weight: 700; color: var(--orange-600); letter-spacing: .02em; }
.pd-card-title { font-size: 18px; line-height: 1.22; font-weight: 800; color: var(--ink); margin: 0; }
.pd-meta-row {
    display: flex; align-items: center; gap: 7px;
    color: var(--slate); font-size: 13.5px; font-weight: 500;
}
.pd-meta-row svg { width: 15px; height: 15px; color: var(--muted); flex: none; }
.pd-card-foot { margin-top: auto; display: flex; flex-direction: column; gap: 13px; }

/* ── Progress bar ── */
.pd-progress-head {
    display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 6px;
}
.pd-spots-left { font-size: 13px; font-weight: 700; color: var(--slate); }
.pd-spots-left.urgent { color: var(--orange-600); }
.pd-joined-count { font-size: 12.5px; color: var(--muted); font-weight: 600; }
.pd-progress-bar { height: 7px; background: var(--line); border-radius: var(--r-pill); overflow: hidden; }
.pd-progress-fill {
    height: 100%; background: var(--orange-500); border-radius: var(--r-pill);
    transition: width .9s cubic-bezier(.2,.7,.2,1);
}

/* ── Join button ── */
.pd-join-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    font-family: inherit; font-size: 13.5px; font-weight: 700;
    background: var(--orange-500); color: #fff; border: none; border-radius: var(--r-pill);
    padding: 10px 18px; cursor: pointer; text-decoration: none; transition: .14s; width: 100%;
}
.pd-join-btn:hover { background: var(--orange-600); color: #fff; }
.pd-join-btn.full { background: var(--line); color: var(--muted); cursor: not-allowed; }

/* ── Empty state ── */
.pd-empty {
    background: #fff; border: 1px dashed var(--line); border-radius: var(--r-lg);
    padding: 56px 24px; text-align: center; color: var(--slate); font-size: 15px;
}

/* ── Responsive ── */
@media (max-width: 980px) {
    .pd-op-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 680px) {
    .pd-wrap { padding: 0 18px; }
    .pd-op-grid { grid-template-columns: 1fr; }
    .pd-name { font-size: 28px; }
}
</style>

@php
    /* Per-opportunity totals */
    $totalSpotsOpen = 0;
    foreach ($opportunities as $op) {
        $cap = $op->slots->sum('total_slots');
        $fil = $op->attendees->count();
        $totalSpotsOpen += max(0, $cap - $fil);
    }

    /* Unique event-type names as "causes" */
    $causes = $opportunities->map(fn($o) => $o->event_type?->name)->filter()->unique()->values();

    /* Deterministic wordmark monogram */
    $words = collect(preg_split('/\s+/', trim($business_unit->name)))
        ->filter(fn($w) => !in_array(strtolower($w), ['of','the','and']));
    $mono = $words->count() === 1
        ? strtoupper(substr($words->first(), 0, 2))
        : strtoupper(substr($words->first(), 0, 1) . substr($words->get(1, 'x'), 0, 1));

    /* Deterministic hue from BU name */
    $hue = abs(crc32($business_unit->name)) % 360;
@endphp

{{-- ─── PARTNER HEADER (white) ─── --}}
<section class="pd-hero">
    <div class="pd-wrap">
        <div class="pd-hero-inner">

            <a href="{{ route('ourpartners.view') }}" class="pd-back">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M19 12H5M11 18l-6-6 6-6"/>
                </svg>
                All partners
            </a>

            <div class="pd-hero-row">

                {{-- Logo / Wordmark tile --}}
                <div class="pd-wordmark"
                     style="background:oklch(0.96 0.035 {{ $hue }});
                            color:oklch(0.48 0.14 {{ $hue }});
                            border:1px solid oklch(0.9 0.05 {{ $hue }});">
                    @if($logo)
                        <img src="{{ $logo }}" alt="{{ $business_unit->name }}">
                    @else
                        {{ $mono }}
                    @endif
                </div>

                {{-- Name + blurb + causes --}}
                <div class="pd-hero-body">
                    <div class="pd-eyebrow">
                        {{ $business_unit->cluster?->name ?? $business_unit->nickname ?? 'Partner' }}
                        &nbsp;·&nbsp; Partner since {{ $business_unit->created_at->year }}
                    </div>
                    <h1 class="pd-name">{{ $business_unit->name }}</h1>
                    <p class="pd-blurb">{{ $business_unit->header_description ?? $business_unit->about }}</p>
                    @if($causes->isNotEmpty())
                        <div class="pd-causes">
                            @foreach($causes as $cause)
                                <span class="pd-cause-badge">{{ $cause }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="pd-stats">
                    <div>
                        <div class="pd-stat-num">{{ $opportunities->count() }}</div>
                        <div class="pd-stat-label">Opportunities</div>
                    </div>
                    <div>
                        <div class="pd-stat-num orange">{{ $totalSpotsOpen }}</div>
                        <div class="pd-stat-label">Spots open</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ─── OPEN OPPORTUNITIES (gray) ─── --}}
<section class="pd-ops">
    <div class="pd-wrap">
        <h2 class="pd-ops-title">Open opportunities</h2>

        @if($opportunities->isEmpty())
            <div class="pd-empty">No opportunities open right now — check back soon.</div>
        @else
            <div class="pd-op-grid">
                @foreach($opportunities as $op)
                    @php
                        $capacity = $op->slots->sum('total_slots');
                        $filled   = $op->attendees->count();
                        $left     = max(0, $capacity - $filled);
                        $pct      = $capacity > 0 ? min(100, round($filled / $capacity * 100)) : 0;
                        $urgent   = $left > 0 && $left <= 8;
                        $full     = $capacity > 0 && $left <= 0;
                        $banner   = $op->getBanner();
                    @endphp
                    <a class="pd-card"
                       href="{{ route('filament.admin.resources.events.view', ['record' => $op->id]) }}">

                        {{-- Cover image --}}
                        <div class="pd-card-img">
                            @if($banner)
                                <img src="{{ $banner }}" alt="{{ $op->title }}">
                            @else
                                <div class="pd-card-img-ph"></div>
                            @endif
                            @if($op->event_type)
                                <span class="pd-cat-badge">{{ $op->event_type->name }}</span>
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="pd-card-body">
                            <div class="pd-org-label">{{ $business_unit->nickname ?? $business_unit->name }}</div>
                            <h3 class="pd-card-title">{{ $op->title }}</h3>

                            @if($op->description)
                                <p style="font-size:13.5px;line-height:1.55;color:var(--slate);margin:0;
                                          display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    {!! strip_tags($op->description) !!}
                                </p>
                            @endif

                            <div style="display:flex;flex-direction:column;gap:7px;">
                                <div class="pd-meta-row">
                                    <svg fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($op->start_date)->format('M d, Y') }}
                                    &nbsp;·&nbsp;
                                    {{ \Carbon\Carbon::parse($op->start_date)->format('g:i A') }}
                                </div>
                                @if($op->location)
                                    <div class="pd-meta-row">
                                        <svg fill="none" stroke="currentColor" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        {{ $op->location }}
                                    </div>
                                @endif
                            </div>

                            {{-- Spots progress + join --}}
                            <div class="pd-card-foot">
                                @if($capacity > 0)
                                    <div>
                                        <div class="pd-progress-head">
                                            <span class="pd-spots-left {{ $urgent ? 'urgent' : '' }}">
                                                @if($full) Fully booked
                                                @elseif($left === 1) 1 spot left
                                                @else {{ $left }} spots left
                                                @endif
                                            </span>
                                            <span class="pd-joined-count">{{ $filled }}/{{ $capacity }} joined</span>
                                        </div>
                                        <div class="pd-progress-bar">
                                            <div class="pd-progress-fill" style="width:{{ $pct }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                @if($full)
                                    <span class="pd-join-btn full">Fully booked</span>
                                @else
                                    <span class="pd-join-btn">
                                        Join
                                        <svg width="16" height="16" fill="none" stroke="currentColor"
                                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                                             viewBox="0 0 24 24">
                                            <path d="M5 12h14M13 6l6 6-6 6"/>
                                        </svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection
