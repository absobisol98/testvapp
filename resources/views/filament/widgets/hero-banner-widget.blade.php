<x-filament-widgets::widget>
    <div class="w-full">

        {{-- ══════════════ VOLUNTEER DASHBOARD ══════════════ --}}
        @if($activeRole === 'Volunteer')

        {{-- Welcome Card --}}
        <div class="relative overflow-hidden rounded-2xl p-7 text-white mb-5"
             style="background: linear-gradient(130deg, #072b54 0%, #0e4f99 100%);">
            {{-- Decorative circles --}}
            <div class="absolute pointer-events-none"
                 style="top:-60px;right:-60px;width:240px;height:240px;border-radius:50%;background:rgba(255,255,255,.04);"></div>
            <div class="absolute pointer-events-none"
                 style="bottom:-40px;right:140px;width:140px;height:140px;border-radius:50%;background:#f26522;opacity:.07;"></div>

            <div class="relative z-10 flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                    <p style="font-size:11px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;opacity:.5;margin-bottom:4px;">
                        {{ $greeting }}, Volunteer
                    </p>
                    <p style="font-size:26px;font-weight:800;letter-spacing:-.04em;margin-bottom:8px;font-family:'Inter',system-ui,sans-serif;">
                        {{ auth()->user()->name }}
                    </p>
                    <div class="flex gap-4 flex-wrap">
                        @if($badgeInfo)
                        <span class="flex items-center gap-1" style="font-size:13px;font-weight:500;opacity:.75;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z"/></svg>
                            {{ $badgeInfo['current_rank']['name'] }}
                        </span>
                        @endif
                        <span class="flex items-center gap-1" style="font-size:13px;font-weight:500;opacity:.65;">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2l3.1 6.3 6.9.9-5 4.9 1.2 6.9-6.2-3.3-6.2 3.3 1.2-6.9-5-4.9 6.9-.9L12 2Z"/></svg>
                            Member since {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>

                {{-- VP Points box --}}
                @if($badgeInfo)
                <div class="text-center flex-shrink-0"
                     style="background:rgba(255,255,255,.11);border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 18px;backdrop-filter:blur(6px);">
                    <div style="font-family:'Inter',system-ui,sans-serif;font-size:28px;font-weight:800;line-height:1;">
                        {{ number_format($badgeInfo['points']) }}
                    </div>
                    <div style="font-size:10px;opacity:.55;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-top:2px;">VP Points</div>
                </div>
                @endif
            </div>

            {{-- Progress bar toward next rank --}}
            @if($badgeInfo && $badgeInfo['current_rank']['name'] !== $badgeInfo['next_rank']['name'])
            @php
                $currentPts  = $badgeInfo['points'];
                $nextRequired = $badgeInfo['next_rank']['required'];
                $prevRequired = $badgeInfo['current_rank']['required'];
                $ptsNeeded    = max(0, $nextRequired - $currentPts);
                $range        = max(1, $nextRequired - $prevRequired);
                $pct          = min(100, (int) round(($currentPts - $prevRequired) / $range * 100));
            @endphp
            <div class="relative z-10" style="margin-top:18px;">
                <div class="flex justify-between" style="font-size:12px;font-weight:700;margin-bottom:7px;">
                    <span style="opacity:.6;">
                        {{ $badgeInfo['next_rank']['name'] }} — {{ number_format($currentPts) }} / {{ number_format($nextRequired) }} pts
                    </span>
                    <span style="color:#f5a623;">{{ number_format($ptsNeeded) }} pts away!</span>
                </div>
                <div style="height:8px;background:rgba(255,255,255,.14);border-radius:999px;">
                    <div style="height:100%;background:linear-gradient(90deg,#f26522 0%,#f5a623 100%);border-radius:999px;width:{{ $pct }}%;transition:width 1.1s;"></div>
                </div>
            </div>
            @endif
        </div>

        {{-- 4 Stat Cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

            {{-- Total Hours --}}
            <a href="{{ route('filament.admin.resources.events.index') }}"
               class="block bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200"
               style="border-top:3px solid #f26522;">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg mb-3"
                     style="background:#fef0e7;color:#f26522;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                    </svg>
                </div>
                <div style="font-family:'Inter',system-ui,sans-serif;font-size:32px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#f26522;">{{ $myHoursRendered }}</div>
                <div style="font-size:12px;font-weight:700;color:#6e7a8a;margin-top:5px;">Total Hours</div>
                <div style="font-size:11.5px;color:#6e7a8a;margin-top:3px;">Lifetime</div>
            </a>

            {{-- Hours This Month --}}
            <div class="bg-white rounded-xl p-5 shadow-sm" style="border-top:3px solid #1565c4;">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg mb-3"
                     style="background:#eef2ff;color:#1565c4;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M18 6l-2.5 2.5M8.5 15.5 6 18"/>
                    </svg>
                </div>
                <div style="font-family:'Inter',system-ui,sans-serif;font-size:32px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#1565c4;">{{ $myHoursThisMonth }}</div>
                <div style="font-size:12px;font-weight:700;color:#6e7a8a;margin-top:5px;">Hours This Month</div>
                <div style="font-size:11.5px;color:#6e7a8a;margin-top:3px;">{{ now()->format('F Y') }}</div>
            </div>

            {{-- Events Attended --}}
            <div class="bg-white rounded-xl p-5 shadow-sm" style="border-top:3px solid #1d8a52;">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg mb-3"
                     style="background:#e8f5ee;color:#1d8a52;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M8 2v4M16 2v4M3 9h18M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z"/>
                    </svg>
                </div>
                <div style="font-family:'Inter',system-ui,sans-serif;font-size:32px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#1d8a52;">{{ $myEventsAttended }}</div>
                <div style="font-size:12px;font-weight:700;color:#6e7a8a;margin-top:5px;">Events Attended</div>
                <div style="font-size:11.5px;color:#6e7a8a;margin-top:3px;">All time</div>
            </div>

            {{-- Certificates --}}
            <div class="bg-white rounded-xl p-5 shadow-sm" style="border-top:3px solid #7c3aed;">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg mb-3"
                     style="background:#f5f3ff;color:#7c3aed;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12ZM8.2 13.9 7 22l5-3 5 3-1.2-8.1"/>
                    </svg>
                </div>
                <div style="font-family:'Inter',system-ui,sans-serif;font-size:32px;font-weight:800;letter-spacing:-.04em;line-height:1;color:#7c3aed;">{{ $myCertificates }}</div>
                <div style="font-size:12px;font-weight:700;color:#6e7a8a;margin-top:5px;">Certificates</div>
                <div style="font-size:11.5px;color:#6e7a8a;margin-top:3px;">Earned</div>
            </div>

        </div>
        {{-- end volunteer --}}

        {{-- ══════════════ ADMIN / SUPER-ADMIN ══════════════ --}}
        @elseif(in_array($activeRole, ['admin', 'super_admin', 'Ayala Super Admin', 'author']))
        <div class="w-full flex items-center justify-center relative min-h-[280px]">
            <div class="w-full h-full flex flex-col items-center justify-center gap-6 p-8 z-[1]">
                <div class="w-full text-white flex flex-col items-center sm:items-start">
                    <p class="text-xl md:text-2xl font-bold mb-2">Welcome {{ auth()->user()->name }}</p>
                    <p class="text-2xl md:text-4xl font-bold">Your involvement <br> is important to us!</p>
                </div>
                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center">
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.volunteers.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalVolunteers }}</p>
                            <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $totalVolunteerHours }}</p>
                        <p class="text-sm font-bold text-[#03498D]">TOTAL VOLUNTEERS HOURS</p>
                    </div>
                </div>
            </div>
            <div class="w-full h-full grid grid-cols-5 absolute z-0 bg-gradient-to-tr from-[#03498D] to-[#03498D]">
                <div class="col-span-3 flex items-center justify-center relative overflow-hidden">
                    <div class="w-full h-full absolute inset-0 bg-gradient-to-tr from-[#03498D] to-[#03498D] z-[2]"
                        style="clip-path: polygon(100% -20%, 100% 100%, 50% 100%);"></div>
                    <div class="w-full h-full absolute bg-gradient-to-r from-[#03488d8a] via-[#03488d8a] to-[#03488d8a] z-[1]"></div>
                    <img class="w-full h-full object-cover z-0 hidden md:block" src="{{ asset($bgImg) }}" alt="">
                </div>
                <div class="col-span-2 bg-[#03498D] flex items-center justify-center"></div>
            </div>
        </div>

        {{-- ══════════════ EXTERNAL PARTNER ══════════════ --}}
        @elseif($activeRole === 'External Partner')
        <div class="w-full flex items-center justify-center relative min-h-[280px]">
            <div class="w-full h-full flex flex-col items-center justify-center gap-6 p-8 z-[1]">
                <div class="w-full text-white flex flex-col items-center sm:items-start">
                    <p class="text-xl md:text-2xl font-bold mb-2">Welcome {{ auth()->user()->name }}</p>
                    <p class="text-2xl md:text-4xl font-bold">Your involvement <br> is important to us!</p>
                </div>
                <div class="w-full grid grid-cols-2 gap-4 mx-auto text-center md:grid-cols-3">
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.events.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $partnerAvailableOpportunities }}</p>
                            <p class="text-sm font-bold text-[#03498D]">AVAILABLE OPPORTUNITIES</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <a href="{{ route('filament.admin.resources.events.index') }}"
                           class="hover:text-[#FF9141] transition-colors duration-200">
                            <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $partnerUpcomingOpportunities }}</p>
                            <p class="text-sm font-bold text-[#03498D]">UPCOMING OPPORTUNITIES</p>
                        </a>
                    </div>
                    <div class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#FFFFFFCC] p-8">
                        <p class="text-3xl md:text-[40px] font-bold text-[#F55E1D]">{{ $partnerHours }}</p>
                        <p class="text-sm font-bold text-[#03498D]">TOTAL BUSINESS UNIT HOURS</p>
                    </div>
                </div>
            </div>
            <div class="w-full h-full grid grid-cols-5 absolute z-0">
                <div class="col-span-3 flex items-center justify-center relative overflow-hidden">
                    <div class="w-full h-full absolute inset-0 bg-gradient-to-tr from-[#03498D] to-[#03498D] z-[2]"
                        style="clip-path: polygon(100% -20%, 100% 100%, 50% 100%);"></div>
                    <div class="w-full h-full absolute bg-gradient-to-r from-[#03488d8a] via-[#03488d8a] to-[#03488d8a] z-[1]"></div>
                    <img class="w-full h-full object-cover z-0 hidden md:block" src="{{ asset($bgImg) }}" alt="">
                </div>
                <div class="col-span-2 bg-[#03498D] flex items-center justify-center"></div>
            </div>
        </div>
        @endif

    </div>
</x-filament-widgets::widget>
