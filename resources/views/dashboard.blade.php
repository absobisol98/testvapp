<x-filament-panels::page>
<style>
    .fi-main { margin:0!important;padding:0!important;max-width:100%!important; }
    .fi-page section { padding:0 0 32px 0!important; }
    .dash-card { background:#fff;border-radius:16px;box-shadow:0 1px 4px rgba(14,32,56,.07),0 4px 16px rgba(14,32,56,.06); }
    .opp-card:hover { box-shadow:0 6px 24px rgba(14,32,56,.13);transform:translateY(-2px); }
    .kebab-menu { position:relative; }
    .kebab-dropdown { display:none;position:absolute;top:calc(100% + 4px);right:0;background:#fff;border:1px solid #e5e7eb;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.13);min-width:160px;z-index:50; }
    .kebab-dropdown.open { display:block; }
    .kebab-item { display:flex;align-items:center;gap:9px;padding:10px 14px;font-size:13.5px;font-weight:600;color:#374151;text-decoration:none;background:none;border:none;width:100%;cursor:pointer;transition:background .12s; }
    .kebab-item:hover { background:#f9fafb; }
    .kebab-item.danger { color:#dc2626; }
    .kebab-item.danger:hover { background:#fff5f5; }
    .kebab-item.feature { color:#d97706; }
    .kebab-item.feature:hover { background:#fffbeb; }
</style>

@php
    $user       = auth()->user();
    $isAdmin    = $user->hasActiveRole('admin')
               || $user->hasActiveRole('Ayala Super Admin')
               || $user->hasActiveRole('author');
    $activeRole = $user->activeRole();
@endphp

<div class="flex flex-col gap-6 p-4 md:p-6">

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 1 — Welcome Header Card
    ═══════════════════════════════════════════════════════════ --}}
    <div class="dash-card overflow-hidden">
        <div class="relative flex flex-col md:flex-row items-stretch min-h-[180px]">

            {{-- Gradient background --}}
            <div class="absolute inset-0 pointer-events-none"
                 style="background:linear-gradient(130deg,#0433ff 0%,#1565c4 60%,#0e2d6b 100%);"></div>

            {{-- Decorative circles --}}
            <div class="absolute pointer-events-none"
                 style="top:-60px;right:-40px;width:280px;height:280px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
            <div class="absolute pointer-events-none"
                 style="bottom:-50px;left:40%;width:180px;height:180px;border-radius:50%;background:#ff7b00;opacity:.07;"></div>

            {{-- Left: welcome text --}}
            <div class="relative z-10 flex-1 p-7 text-white">
                <p style="font-size:12px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.55;margin-bottom:6px;">
                    {{ $isAdmin ? 'Admin Dashboard' : 'Volunteer Dashboard' }}
                </p>
                <p style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:28px;font-weight:800;letter-spacing:-.03em;margin-bottom:4px;">
                    Welcome, {{ $volunteer_name }}
                </p>
                <p style="font-size:15px;opacity:.7;font-weight:500;">
                    Your involvement is important to us!
                </p>
            </div>

            {{-- Right: stat boxes --}}
            <div class="relative z-10 flex items-stretch divide-x divide-white/10 border-t border-white/10 md:border-t-0 md:border-l">

                <div class="flex flex-col items-center justify-center px-8 py-6 text-white text-center flex-1 md:flex-none md:min-w-[160px]">
                    <span style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:36px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#ff7b00;">
                        {{ $total_volunteers }}
                    </span>
                    <span style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;opacity:.6;margin-top:5px;">
                        Total Volunteers
                    </span>
                </div>

                <div class="flex flex-col items-center justify-center px-8 py-6 text-white text-center flex-1 md:flex-none md:min-w-[160px]">
                    <span style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:36px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#ff7b00;">
                        {{ $total_volunteer_hours }}
                    </span>
                    <span style="font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;opacity:.6;margin-top:5px;">
                        Total Volunteer Hours
                    </span>
                </div>

            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════
         SECTION 2 — Opportunities Card
    ═══════════════════════════════════════════════════════════ --}}
    <div class="dash-card p-6">

        {{-- Section header --}}
        <div class="flex items-center justify-between mb-5">
            <p style="font-family:'Bricolage Grotesque',system-ui,sans-serif;font-size:20px;font-weight:800;letter-spacing:-.02em;color:#0d1f3c;">
                Opportunities
            </p>
            <a href="{{ route('filament.admin.resources.events.index') }}"
               style="font-size:13px;font-weight:700;color:#ff7b00;text-decoration:none;display:flex;align-items:center;gap:4px;">
                View All
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
            </a>
        </div>

        <div class="w-full h-px mb-5" style="background:#eef0f4;"></div>

        @if($opportunities->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center" style="color:#94a3b8;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-40"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                <p style="font-size:14px;font-weight:600;">No upcoming opportunities</p>
                <p style="font-size:12px;margin-top:4px;opacity:.7;">Check back soon for new events.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($opportunities as $opp)
                @php
                    $banner    = $opp->getMedia('event-banner-attachments')?->first()?->getUrl();
                    $firstSlot = $opp->slots?->first();
                    $totalSlots = $opp->slots->sum('total_slots');
                    $filled    = $opp->registrations?->whereNotIn('status_id', [3])->count() ?? 0;
                    $available = max(0, $totalSlots - $filled);
                    $isFull    = $totalSlots > 0 && $available === 0;
                    $startDate = \Carbon\Carbon::parse($opp->start_date);
                    $editUrl   = route('filament.admin.resources.events.edit',   ['record' => $opp->id]);
                    $viewUrl   = route('filament.admin.resources.events.view',   ['record' => $opp->id]);
                    $menuId    = 'km-' . $opp->id;
                @endphp

                <div class="opp-card flex flex-col bg-white rounded-xl overflow-hidden transition-all duration-200"
                     style="border:1px solid #eef0f4;box-shadow:0 1px 3px rgba(14,32,56,.06);">

                    {{-- Image --}}
                    <div class="relative flex-shrink-0" style="height:130px;overflow:hidden;">
                        <img src="{{ $banner ?? asset('img/ayala-foundation-bg.jpg') }}"
                             alt="{{ $opp->title }}"
                             class="w-full h-full object-cover">

                        {{-- Date badge --}}
                        <span class="absolute"
                              style="bottom:10px;left:10px;background:#ff7b00;color:#fff;font-size:11px;font-weight:700;
                                     padding:4px 10px;border-radius:20px;letter-spacing:.03em;">
                            {{ $startDate->format('M j, Y') }}
                        </span>

                        {{-- Admin kebab menu --}}
                        @if($isAdmin)
                        <div class="kebab-menu absolute" style="top:8px;right:8px;">
                            <button type="button"
                                    onclick="toggleKebab('{{ $menuId }}')"
                                    style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.92);
                                           border:none;display:flex;align-items:center;justify-content:center;cursor:pointer;
                                           box-shadow:0 1px 4px rgba(0,0,0,.15);">
                                <svg width="16" height="16" fill="#374151" viewBox="0 0 24 24">
                                    <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                </svg>
                            </button>

                            <div id="{{ $menuId }}" class="kebab-dropdown">
                                {{-- Edit --}}
                                <a href="{{ $editUrl }}" class="kebab-item">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </a>
                                {{-- Duplicate --}}
                                <button type="button" class="kebab-item"
                                        onclick="vappDuplicate('{{ $opp->id }}')">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                    Duplicate
                                </button>
                                {{-- Feature --}}
                                <button type="button" class="kebab-item feature"
                                        onclick="vappToggleFeatured('{{ $opp->id }}', {{ $opp->is_featured ? 'true' : 'false' }})">
                                    <svg width="14" height="14" fill="{{ $opp->is_featured ? '#d97706' : 'none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2l3.1 6.3 6.9.9-5 4.9 1.2 6.9-6.2-3.3-6.2 3.3 1.2-6.9-5-4.9 6.9-.9L12 2z"/></svg>
                                    {{ $opp->is_featured ? 'Unfeature' : 'Feature' }}
                                </button>
                                {{-- Delete --}}
                                <button type="button" class="kebab-item danger"
                                        onclick="vappDelete('{{ $opp->id }}')"
                                        style="border-top:1px solid #f3f4f6;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Body --}}
                    <div class="flex flex-col gap-2 p-4 flex-1" style="min-width:0;">

                        <p class="font-bold" style="font-size:14px;color:#0d1f3c;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                            {{ $opp->title }}
                        </p>

                        {{-- Meta: time --}}
                        @if($firstSlot && $firstSlot->start_time)
                        <div class="flex items-center gap-1.5" style="color:#6b7280;font-size:12px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            {{ \Carbon\Carbon::parse($firstSlot->start_time)->format('g:i A') }}
                            @if($firstSlot->end_time)
                                – {{ \Carbon\Carbon::parse($firstSlot->end_time)->format('g:i A') }}
                            @endif
                        </div>
                        @endif

                        {{-- Meta: slots --}}
                        @if($totalSlots > 0)
                        <div class="flex items-center gap-1.5" style="color:#6b7280;font-size:12px;">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span style="color:{{ $isFull ? '#ef4444' : '#16a34a' }};font-weight:600;">
                                {{ $isFull ? 'Full' : "{$available} slot" . ($available !== 1 ? 's' : '') . ' open' }}
                            </span>
                        </div>
                        @endif

                    </div>

                    {{-- Join button --}}
                    <div class="px-4 pb-4">
                        @if(!$isFull)
                            <a href="{{ $viewUrl }}"
                               class="block w-full text-center font-bold text-white"
                               style="padding:10px;border-radius:8px;background:#ff7b00;font-size:13.5px;
                                      text-decoration:none;transition:background .15s;"
                               onmouseover="this.style.background='#e06e00'"
                               onmouseout="this.style.background='#ff7b00'">
                                Join
                            </a>
                        @else
                            <span class="block w-full text-center font-bold"
                                  style="padding:10px;border-radius:8px;background:#f3f4f6;color:#9ca3af;font-size:13.5px;">
                                Slot Full
                            </span>
                        @endif
                    </div>

                </div>
                @endforeach
            </div>
        @endif

    </div>

</div>

{{-- Role-specific widgets below (existing behaviour preserved) --}}
<div class="px-4 pb-4 md:px-6 md:pb-6 flex flex-col gap-6">
    @if($activeRole === 'Volunteer')
        @livewire(\App\Filament\Widgets\AdsDashboardWidget::class)
        @livewire(\App\Filament\Widgets\UpcomingOpportunityWidget::class)
    @endif

    @if(in_array($activeRole, ['Ayala Super Admin', 'admin', 'author']))
        @livewire(\App\Filament\Widgets\AFIAdminOpportunitiesWidget::class)
        @livewire(\App\Filament\Widgets\BusinessUnitOrExternalPartersWidget::class)
        @livewire(\App\Filament\Widgets\VolunteersWidget::class)
    @endif

    @if($activeRole === 'External Partner')
        @livewire(\App\Filament\Widgets\PartnersOnGoingOpportunitiesWidget::class)
        @livewire(\App\Filament\Widgets\PartnersMyOpportunitiesWidget::class)
        @livewire(\App\Filament\Widgets\FacilitatorWidget::class)
    @endif

    @if($activeRole === 'Facilitator')
        @livewire(\App\Filament\Widgets\MyFacilitatedEventsWidget::class)
    @endif
</div>

<script>
function toggleKebab(id) {
    document.querySelectorAll('.kebab-dropdown.open').forEach(function(el) {
        if (el.id !== id) el.classList.remove('open');
    });
    document.getElementById(id)?.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.kebab-menu')) {
        document.querySelectorAll('.kebab-dropdown.open').forEach(function(el) {
            el.classList.remove('open');
        });
    }
});
</script>
</x-filament-panels::page>
