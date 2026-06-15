<x-filament-widgets::widget>
    <div class="w-full">

        {{-- ── Section header ── --}}
        <div class="flex items-center justify-between gap-4 mb-1 px-1">
            <p style="font-family:'Inter',system-ui,sans-serif;font-size:20px;font-weight:800;letter-spacing:-.02em;color:#072b54;">
                Upcoming Opportunities
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ route('filament.admin.resources.events.index') }}"
                   class="flex items-center gap-1 text-sm font-bold hover:underline"
                   style="color:#f26522;">
                    View All
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
                {{-- View toggle --}}
                <button id="btn-list" onclick="switchView('list')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors duration-150"
                        style="background:#0e4f99;color:#fff;" title="List view">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                </button>
                <button id="btn-calendar" onclick="switchView('calendar')"
                        class="w-8 h-8 flex items-center justify-center rounded-lg transition-colors duration-150"
                        style="background:transparent;color:#6e7a8a;" title="Calendar view">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                </button>
            </div>
        </div>

        <div class="w-full h-px mb-5" style="background:#e4e8ee;"></div>

        {{-- ── List view: 3-column card grid ── --}}
        <div id="view-list">
            @if($opportunities->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center" style="color:#6e7a8a;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-3 opacity-40" aria-hidden="true"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                <p class="font-semibold text-sm">No upcoming opportunities</p>
                <p class="text-xs mt-1 opacity-70">Check back soon for new events.</p>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($opportunities as $opportunity)
                @php
                    $mediaUrl    = $opportunity->getMedia('event-banner-attachments')?->first()?->getUrl();
                    $isRegistered = \App\Models\EventRegistration::where('volunteer_id', auth()->id())
                        ->where('event_id', $opportunity->id)
                        ->whereIn('status_id', [1, 2])
                        ->exists();
                    $firstSlot   = $opportunity->slots?->first();
                    $regStillOpen = $opportunity->registration_end_date
                        ? \Carbon\Carbon::now()->isBefore($opportunity->registration_end_date)
                        : true;
                    $filledSlots = $opportunity->attendees()->count();
                    $totalSlots  = $opportunity->slots->sum('slot') ?: 0;
                    $fillPct     = $totalSlots > 0 ? min(100, (int) round($filledSlots / $totalSlots * 100)) : 0;
                    $bgColors    = ['#c8e6d0','#d6e8f8','#f5dde8','#fde8d0','#e8e0f5'];
                    $cardBg      = $bgColors[$loop->index % count($bgColors)];
                @endphp

                <div class="bg-white rounded-xl overflow-hidden flex flex-col"
                     style="box-shadow:0 1px 3px rgba(14,32,56,.06),0 2px 8px rgba(14,32,56,.06);transition:box-shadow .18s,transform .18s;"
                     onmouseover="this.style.boxShadow='0 4px 20px rgba(14,32,56,.12)';this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.boxShadow='0 1px 3px rgba(14,32,56,.06),0 2px 8px rgba(14,32,56,.06)';this.style.transform='none'">

                    {{-- Card image / header --}}
                    <div class="relative flex items-end p-3"
                         style="height:96px;background:{{ $cardBg }};">
                        @if($mediaUrl)
                        <img src="{{ $mediaUrl }}" alt="{{ $opportunity->title }}"
                             class="absolute inset-0 w-full h-full object-cover">
                        @endif
                        {{-- Date badge --}}
                        <span class="relative z-10 inline-flex items-center gap-1 px-3 py-1 rounded-full text-white text-xs font-bold"
                              style="background:#f26522;letter-spacing:.02em;">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/></svg>
                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M j') }}
                        </span>
                    </div>

                    {{-- Card body --}}
                    <div class="flex flex-col gap-2 p-4 flex-1">
                        <p class="text-xs font-bold uppercase tracking-wider" style="color:#f26522;">
                            Ayala Foundation
                        </p>
                        <h3 style="font-family:'Inter',system-ui,sans-serif;font-size:13.5px;font-weight:700;line-height:1.3;color:#141820;">
                            {{ Str::limit($opportunity->title, 60) }}
                        </h3>
                        @if($opportunity->location)
                        <div class="flex items-center gap-1" style="font-size:12px;color:#6e7a8a;font-weight:500;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="2"/></svg>
                            {{ Str::limit($opportunity->location, 45) }}
                        </div>
                        @endif
                        <div class="flex items-center gap-1" style="font-size:12px;color:#6e7a8a;font-weight:500;">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }}
                            @if($opportunity->end_date)
                                – {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}
                            @endif
                        </div>

                        {{-- Slots progress bar --}}
                        @if($totalSlots > 0)
                        <div class="flex items-center gap-2 mt-1">
                            <div class="flex-1 rounded-full overflow-hidden" style="height:4px;background:#e4e8ee;">
                                <div style="height:100%;background:#f26522;border-radius:999px;width:{{ $fillPct }}%;"></div>
                            </div>
                            <span style="font-size:11px;font-weight:700;color:#6e7a8a;white-space:nowrap;">
                                {{ $filledSlots }}/{{ $totalSlots }}
                            </span>
                        </div>
                        @endif

                        {{-- Spacer --}}
                        <div class="flex-1"></div>

                        {{-- Primary CTA --}}
                        <div class="mt-3">
                            @if($isRegistered)
                            <div class="w-full flex items-center justify-center gap-2 rounded-full py-2.5 text-sm font-semibold"
                                 style="background:#e8f5ee;color:#1d8a52;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
                                Already Registered
                            </div>
                            @elseif($firstSlot && $regStillOpen)
                            <form action="{{ route('event.register-slot', ['event' => $opportunity->id, 'slot' => $firstSlot->id]) }}"
                                  method="POST">
                                @csrf
                                <input type="hidden" name="privacy_policy" value="1">
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 rounded-full py-2.5 text-sm font-bold transition-colors duration-150"
                                        style="background:#f26522;color:#fff;"
                                        onmouseover="this.style.background='#d95a14'"
                                        onmouseout="this.style.background='#f26522'">
                                    Volunteer for this
                                </button>
                            </form>
                            @else
                            <div class="w-full flex items-center justify-center rounded-full py-2.5 text-sm font-semibold"
                                 style="background:#f0f2f5;color:#9ca3af;">
                                Registration Closed
                            </div>
                            @endif
                        </div>

                        {{-- Secondary: View link --}}
                        <a href="{{ url('/admin/events/view/' . $opportunity->id) }}"
                           class="mt-2 block text-center text-xs font-semibold hover:underline"
                           style="color:#6e7a8a;">
                            View Details →
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- ── Announcement Banner ── --}}
            <div class="flex items-center gap-4 rounded-xl p-4 mt-6"
                 style="background:#072b54;color:#fff;">
                <div class="flex items-center justify-center flex-none rounded-lg"
                     style="width:40px;height:40px;background:#f26522;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p style="font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;opacity:.55;">Announcements</p>
                    <p style="font-size:14px;font-weight:600;margin-top:2px;">
                        Check out the latest volunteer opportunities and news from Ayala Foundation.
                    </p>
                </div>
                <a href="{{ route('filament.admin.resources.events.index') }}"
                   class="flex-none flex items-center gap-1 rounded-full text-sm font-bold px-4 py-2 transition-opacity"
                   style="background:#f26522;color:#fff;">
                    Browse Events
                </a>
            </div>

            {{-- ── Bottom Row: Rank Progress + Certificates ── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5">

                {{-- Rank Progress Card --}}
                @if($badgeInfo)
                @php
                    $cp  = $badgeInfo['points'];
                    $nr  = $badgeInfo['next_rank']['required'];
                    $pr  = $badgeInfo['current_rank']['required'];
                    $rng = max(1, $nr - $pr);
                    $pct = $cp >= $nr ? 100 : (int) round(($cp - $pr) / $rng * 100);
                    $allRanks = [
                        ['name' => 'Bronze',   'pts' => 1250],
                        ['name' => 'Silver',   'pts' => 2500],
                        ['name' => 'Gold',     'pts' => 5000],
                        ['name' => 'Platinum', 'pts' => 10000],
                    ];
                @endphp
                <div class="bg-white rounded-xl p-5" style="box-shadow:0 1px 3px rgba(14,32,56,.06);">
                    <div class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider rounded-full px-3 py-1 mb-3"
                         style="background:#fef0e7;color:#d95a14;letter-spacing:.07em;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z"/></svg>
                        {{ $badgeInfo['current_rank']['name'] }}
                    </div>
                    <p style="font-size:15px;font-weight:700;margin-bottom:2px;color:#141820;">Rank Progress</p>
                    <p style="font-size:12.5px;color:#44505e;margin-bottom:14px;line-height:1.5;">
                        {{ number_format($cp) }} points earned · {{ number_format(max(0, $nr - $cp)) }} to go
                    </p>
                    <div class="rounded-full overflow-hidden mb-2" style="height:10px;background:#e4e8ee;">
                        <div style="height:100%;background:linear-gradient(90deg,#f26522 0%,#f5a623 100%);border-radius:999px;width:{{ $pct }}%;transition:width 1.1s;"></div>
                    </div>
                    <div class="flex justify-between mb-4" style="font-size:11.5px;font-weight:700;">
                        <span style="color:#f26522;">{{ number_format($cp) }} pts</span>
                        <span style="color:#6e7a8a;">{{ number_format($nr) }} pts</span>
                    </div>
                    <div class="grid gap-2" style="grid-template-columns:repeat({{ count($allRanks) }},1fr);">
                        @foreach($allRanks as $tier)
                        @php $done = $cp >= $tier['pts']; @endphp
                        <div class="text-center rounded-lg py-2"
                             style="border:1.5px solid {{ $done ? '#f26522' : '#e4e8ee' }};background:{{ $done ? '#fef0e7' : 'transparent' }};">
                            <div style="font-size:11.5px;font-weight:700;color:{{ $done ? '#d95a14' : '#6e7a8a' }};">{{ $tier['name'] }}</div>
                            <div style="font-size:10px;opacity:.65;margin-top:2px;color:{{ $done ? '#d95a14' : '#6e7a8a' }};">{{ number_format($tier['pts']) }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Certificates Card --}}
                <div class="bg-white rounded-xl p-5" style="box-shadow:0 1px 3px rgba(14,32,56,.06);">
                    <div class="flex items-center justify-between mb-4">
                        <p style="font-size:15px;font-weight:700;color:#141820;">My Certificates</p>
                        <a href="{{ route('filament.admin.resources.events.index') }}"
                           class="text-xs font-bold flex items-center gap-1 hover:underline"
                           style="color:#f26522;">
                            View all
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>

                    @if($recentCertificates->isEmpty())
                    <div class="flex flex-col items-center justify-center py-8 text-center" style="color:#6e7a8a;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2 opacity-40" aria-hidden="true"><path d="M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12ZM8.2 13.9 7 22l5-3 5 3-1.2-8.1"/></svg>
                        <p class="text-sm font-semibold">No certificates yet</p>
                        <p class="text-xs mt-1 opacity-70">Attend events to earn certificates.</p>
                    </div>
                    @else
                    <div class="flex flex-col gap-3">
                        @foreach($recentCertificates as $cert)
                        <div class="flex items-center gap-3 rounded-lg p-3 border transition-colors duration-150 cursor-pointer"
                             style="border-color:#e4e8ee;"
                             onmouseover="this.style.borderColor='#f26522';this.style.background='#fef0e7'"
                             onmouseout="this.style.borderColor='#e4e8ee';this.style.background='transparent'">
                            <div class="flex items-center justify-center flex-none rounded-xl"
                                 style="width:40px;height:40px;background:linear-gradient(135deg,#072b54 0%,#1565c4 100%);">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12ZM8.2 13.9 7 22l5-3 5 3-1.2-8.1"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-sm truncate" style="color:#141820;">
                                    {{ $cert->event?->title ?? 'Certificate' }}
                                </p>
                                <p class="text-xs mt-0.5" style="color:#6e7a8a;">
                                    {{ $cert->issued_at?->format('M j, Y') ?? 'Issued' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1 flex-none text-xs font-bold" style="color:#f26522;">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/></svg>
                                Download
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>{{-- end bottom row --}}

        </div>{{-- end view-list --}}

        {{-- ── Calendar view ── --}}
        <div id="view-calendar" class="hidden">
            @livewire(\App\Filament\Widgets\CalendarWidget::class)
        </div>

    </div>

    <script>
        function switchView(type) {
            const list     = document.getElementById('view-list');
            const calendar = document.getElementById('view-calendar');
            const btnList  = document.getElementById('btn-list');
            const btnCal   = document.getElementById('btn-calendar');
            if (type === 'list') {
                list.classList.remove('hidden');
                calendar.classList.add('hidden');
                btnList.style.background = '#0e4f99';  btnList.style.color = '#fff';
                btnCal.style.background  = 'transparent'; btnCal.style.color  = '#6e7a8a';
            } else {
                list.classList.add('hidden');
                calendar.classList.remove('hidden');
                btnCal.style.background  = '#0e4f99';  btnCal.style.color  = '#fff';
                btnList.style.background = 'transparent'; btnList.style.color = '#6e7a8a';
            }
        }
    </script>
</x-filament-widgets::widget>
