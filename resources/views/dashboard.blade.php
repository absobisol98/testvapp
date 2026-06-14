<x-filament-panels::page>
<style>
    .fi-main { margin:0!important; padding:0!important; max-width:100%!important; }
    .fi-page section { padding:0 0 24px 0!important; }

    .d-card { background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(14,32,56,.07),0 4px 16px rgba(14,32,56,.05); }

    .opp-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:16px; }
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
         SECTION 1 — Welcome Header Card
         Shown to ALL roles
    ═══════════════════════════════════ --}}
    <div class="d-card overflow-hidden">
        <div class="relative flex flex-col md:flex-row items-stretch"
             style="min-height:170px; background:linear-gradient(130deg,#0433ff 0%,#1565c4 55%,#0e2d6b 100%);">

            {{-- decorative circles --}}
            <div class="absolute pointer-events-none"
                 style="top:-50px;right:-30px;width:260px;height:260px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
            <div class="absolute pointer-events-none"
                 style="bottom:-40px;right:35%;width:160px;height:160px;border-radius:50%;background:#ff7b00;opacity:.06;"></div>

            {{-- Left: greeting --}}
            <div class="relative z-10 flex-1 p-7 text-white">
                <p style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.5;margin-bottom:5px;">
                    {{ $activeRole }} Dashboard
                </p>
                <p style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:26px;font-weight:800;letter-spacing:-.03em;margin-bottom:4px;">
                    Welcome, {{ $volunteer_name }}
                </p>
                <p style="font-size:15px;opacity:.65;font-weight:500;">
                    Your involvement is important to us!
                </p>
            </div>

            {{-- Right: stats (Admin / Super Admin only) --}}
            @if($isAdmin)
            <div class="relative z-10 flex items-stretch border-t border-white/10 md:border-t-0 md:border-l border-white/10">
                <a href="{{ route('filament.admin.resources.volunteers.index') }}"
                   class="flex flex-col items-center justify-center px-8 py-6 text-white text-center flex-1 md:flex-none md:min-w-[148px] hover:bg-white/5 transition-colors">
                    <span style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:34px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#ff7b00;">
                        {{ $total_volunteers }}
                    </span>
                    <span style="font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;opacity:.6;margin-top:5px;">
                        Total Volunteers
                    </span>
                </a>
                <div class="flex flex-col items-center justify-center px-8 py-6 text-white text-center flex-1 md:flex-none md:min-w-[148px] border-l border-white/10">
                    <span style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:34px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#ff7b00;">
                        {{ $total_volunteer_hours }}
                    </span>
                    <span style="font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;opacity:.6;margin-top:5px;">
                        Total Volunteer Hours
                    </span>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- ═══════════════════════════════════
         SECTION 2 — Opportunities Card
         Shown to ALL roles
    ═══════════════════════════════════ --}}
    <div class="d-card p-6">

        <div class="flex items-center justify-between mb-4">
            <p style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:19px;font-weight:800;letter-spacing:-.02em;color:#0d1f3c;">
                Opportunities
            </p>
            <a href="{{ route('filament.admin.resources.events.index') }}"
               style="font-size:13px;font-weight:700;color:#ff7b00;text-decoration:none;display:flex;align-items:center;gap:3px;">
                View All
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
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
                $banner     = $opp->getMedia('event-banner-attachments')?->first()?->getUrl();
                $firstSlot  = $opp->slots?->first();
                $totalSlots = $opp->slots->sum('total_slots');
                $filled     = $opp->registrations?->whereNotIn('status_id', [3])->count() ?? 0;
                $available  = max(0, $totalSlots - $filled);
                $isFull     = $totalSlots > 0 && $available === 0;
                $startDate  = \Carbon\Carbon::parse($opp->start_date);
                $editUrl    = route('filament.admin.resources.events.edit', ['record' => $opp->id]);
                $viewUrl    = route('filament.admin.resources.events.view', ['record' => $opp->id]);
                $menuId     = 'km-' . $opp->id;
            @endphp

            <div class="opp-card">

                {{-- Image --}}
                <div class="relative flex-shrink-0" style="height:120px;">
                    <img src="{{ $banner ?? asset('img/ayala-foundation-bg.jpg') }}"
                         alt="{{ $opp->title }}"
                         class="w-full h-full object-cover">

                    {{-- Date badge --}}
                    <span class="absolute"
                          style="bottom:8px;left:8px;background:#ff7b00;color:#fff;font-size:10.5px;font-weight:700;
                                 padding:3px 9px;border-radius:20px;letter-spacing:.03em;white-space:nowrap;">
                        {{ $startDate->format('M j, Y') }}
                    </span>

                    {{-- Admin 3-dot menu — top-right of image --}}
                    @if($isAdmin)
                    <div class="kebab-wrap absolute" style="top:7px;right:7px;">
                        <button type="button"
                                onclick="toggleKm('{{ $menuId }}')"
                                style="width:30px;height:30px;border-radius:7px;background:rgba(255,255,255,.93);
                                       border:none;display:flex;align-items:center;justify-content:center;
                                       cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.15);">
                            <svg width="15" height="15" fill="#374151" viewBox="0 0 24 24">
                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                            </svg>
                        </button>

                        <div id="{{ $menuId }}" class="kebab-drop">
                            <a href="{{ $editUrl }}" class="km-item">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </a>
                            <button type="button" class="km-item" onclick="vappDuplicate('{{ $opp->id }}')">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                Duplicate
                            </button>
                            <button type="button" class="km-item star"
                                    onclick="vappToggleFeatured('{{ $opp->id }}', {{ $opp->is_featured ? 'true' : 'false' }})">
                                <svg width="13" height="13" fill="{{ $opp->is_featured ? '#d97706' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.1 6.3 6.9.9-5 4.9 1.2 6.9-6.2-3.3-6.2 3.3 1.2-6.9-5-4.9 6.9-.9L12 2z"/></svg>
                                {{ $opp->is_featured ? 'Unfeature' : 'Feature' }}
                            </button>
                            <button type="button" class="km-item danger" onclick="vappDelete('{{ $opp->id }}')">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                Delete
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Body --}}
                <div class="flex flex-col gap-1.5 p-4 flex-1" style="min-width:0;">
                    <p class="font-bold" style="font-size:13.5px;color:#0d1f3c;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                        {{ $opp->title }}
                    </p>

                    @if($firstSlot && $firstSlot->start_time)
                    <div class="flex items-center gap-1.5" style="color:#6b7280;font-size:12px;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        {{ \Carbon\Carbon::parse($firstSlot->start_time)->format('g:i A') }}@if($firstSlot->end_time) – {{ \Carbon\Carbon::parse($firstSlot->end_time)->format('g:i A') }}@endif
                    </div>
                    @endif

                    @if($totalSlots > 0)
                    <div class="flex items-center gap-1.5" style="color:#6b7280;font-size:12px;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        <span style="font-weight:600;color:{{ $isFull ? '#ef4444' : '#16a34a' }};">
                            {{ $isFull ? 'Slot full' : $available . ' slot' . ($available !== 1 ? 's' : '') . ' open' }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Join CTA --}}
                <div class="px-4 pb-4">
                    @if(!$isFull)
                        <a href="{{ $viewUrl }}"
                           class="block w-full text-center font-bold text-white"
                           style="padding:9px;border-radius:8px;background:#ff7b00;font-size:13px;text-decoration:none;transition:background .15s;"
                           onmouseover="this.style.background='#e06e00'"
                           onmouseout="this.style.background='#ff7b00'">
                            Join
                        </a>
                    @else
                        <span class="block w-full text-center font-semibold"
                              style="padding:9px;border-radius:8px;background:#f3f4f6;color:#9ca3af;font-size:13px;">
                            Slot Full
                        </span>
                    @endif
                </div>

            </div>
            @endforeach
            </div>
        @endif
    </div>

    {{-- ═══════════════════════════════════
         SECTION 3 — Role-specific widgets
         (existing widgets, unchanged logic)
    ═══════════════════════════════════ --}}
    @if($isVolunteer)
        @livewire(\App\Filament\Widgets\AdsDashboardWidget::class)
        @livewire(\App\Filament\Widgets\UpcomingOpportunityWidget::class)
    @endif

    @if($isAdmin)
        @livewire(\App\Filament\Widgets\AFIAdminOpportunitiesWidget::class)
        @livewire(\App\Filament\Widgets\BusinessUnitOrExternalPartersWidget::class)
        @livewire(\App\Filament\Widgets\VolunteersWidget::class)
    @endif

    @if($isPartner)
        @livewire(\App\Filament\Widgets\PartnersOnGoingOpportunitiesWidget::class)
        @livewire(\App\Filament\Widgets\PartnersMyOpportunitiesWidget::class)
        @livewire(\App\Filament\Widgets\FacilitatorWidget::class)
    @endif

    @if($isFacilitator)
        @livewire(\App\Filament\Widgets\MyFacilitatedEventsWidget::class)
    @endif

</div>

<script>
function toggleKm(id) {
    document.querySelectorAll('.kebab-drop.open').forEach(function(el) {
        if (el.id !== id) el.classList.remove('open');
    });
    var el = document.getElementById(id);
    if (el) el.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.kebab-wrap')) {
        document.querySelectorAll('.kebab-drop.open').forEach(function(el) { el.classList.remove('open'); });
    }
});
</script>
</x-filament-panels::page>
