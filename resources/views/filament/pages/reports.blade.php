<x-filament-panels::page>

{{-- ── Summary Stats Row ──────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    @php
        $stats = [
            ['label' => 'Total Volunteers',    'value' => $this->summary['total_volunteers'],    'icon' => 'heroicon-o-users',           'color' => 'text-blue-600',   'bg' => 'bg-blue-50'],
            ['label' => 'Total Hours Logged',  'value' => $this->summary['total_hours'],         'icon' => 'heroicon-o-clock',           'color' => 'text-green-600',  'bg' => 'bg-green-50'],
            ['label' => 'Opportunities Run',   'value' => $this->summary['total_events'],        'icon' => 'heroicon-o-calendar',        'color' => 'text-purple-600', 'bg' => 'bg-purple-50'],
            ['label' => 'Total Registrations', 'value' => $this->summary['total_registrations'], 'icon' => 'heroicon-o-clipboard-list',  'color' => 'text-orange-600', 'bg' => 'bg-orange-50'],
            ['label' => 'Avg Hrs / Volunteer', 'value' => $this->summary['avg_hours'],           'icon' => 'heroicon-o-chart-bar',       'color' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
            ['label' => 'Attendance Rate',     'value' => $this->summary['completion_rate'].'%', 'icon' => 'heroicon-o-check-circle',    'color' => 'text-teal-600',   'bg' => 'bg-teal-50'],
        ];
    @endphp

    @foreach($stats as $stat)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex flex-col gap-2">
        <div class="flex items-center gap-2">
            <span class="p-1.5 rounded-lg {{ $stat['bg'] }}">
                <x-dynamic-component :component="$stat['icon']" class="w-4 h-4 {{ $stat['color'] }}" />
            </span>
            <span class="text-xs text-gray-500">{{ $stat['label'] }}</span>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── Tabs ────────────────────────────────────────────────────────────── --}}
<div x-data="{ tab: 'volunteers' }">

    {{-- Tab bar --}}
    <div class="border-b border-gray-200 mb-6">
        <ul class="flex gap-1 -mb-px text-sm font-medium">
            @foreach([
                ['id' => 'volunteers',   'label' => 'Volunteers',    'icon' => 'heroicon-o-users'],
                ['id' => 'opportunities','label' => 'Opportunities',  'icon' => 'heroicon-o-calendar'],
                ['id' => 'leaderboard',  'label' => 'Leaderboard',   'icon' => 'heroicon-o-trophy'],
            ] as $t)
            <li>
                <button
                    @click="tab = '{{ $t['id'] }}'"
                    :class="tab === '{{ $t['id'] }}'
                        ? 'border-primary-500 text-primary-600 border-b-2'
                        : 'border-transparent text-gray-500 hover:text-primary-600 hover:border-gray-300'"
                    class="inline-flex items-center gap-2 px-5 py-3 border-b-2 rounded-t-lg transition-colors">
                    <x-dynamic-component :component="$t['icon']" class="w-4 h-4" />
                    {{ $t['label'] }}
                </button>
            </li>
            @endforeach
        </ul>
    </div>

    {{-- ── Volunteers Tab ──────────────────────────────────────────────── --}}
    <div x-show="tab === 'volunteers'" x-cloak class="space-y-6">

        {{-- Volunteer Hours Stats --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Volunteer Hours by Affiliation</h3>
            @livewire('App\Filament\Widgets\TotalVolunteerHoursStats')
        </div>

        {{-- Monthly Signups Chart --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Monthly Registrations (This Year)</h3>
            @livewire('App\Filament\Widgets\VolunteerSignupPerMonth')
        </div>

        {{-- Program Hours Breakdown --}}
        @if(count($this->programBreakdown))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Hours by Program</h3>
            @php $maxHours = collect($this->programBreakdown)->max('hours'); @endphp
            <div class="space-y-3">
                @foreach($this->programBreakdown as $row)
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700">{{ $row['name'] }}</span>
                        <span class="text-gray-500">{{ number_format($row['hours'], 1) }} hrs · {{ $row['count'] }} event(s)</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-primary-500 h-2 rounded-full"
                             style="width: {{ $maxHours > 0 ? round(($row['hours'] / $maxHours) * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Detailed Volunteer Hours Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Volunteer Hours Detail</h3>
            @livewire('App\Filament\Widgets\FilteredVolunteerHours')
        </div>

        {{-- Participation List --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Volunteer Participation</h3>
            @livewire('App\Filament\Widgets\VolunteerParticipationList')
        </div>
    </div>

    {{-- ── Opportunities Tab ──────────────────────────────────────────── --}}
    <div x-show="tab === 'opportunities'" x-cloak class="space-y-6">

        {{-- Aggregate stats for this year --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $os = $this->opportunityStats;
                $oppStats = [
                    ['label' => $os['year'].' Events', 'value' => $os['events_this_year'], 'sub' => $os['completed_events'].' completed'],
                    ['label' => 'Total Slots',          'value' => $os['slots_total'],      'sub' => $os['slots_filled'].' filled'],
                    ['label' => 'Slot Fill Rate',       'value' => $os['fill_rate'].'%',    'sub' => 'of all available slots'],
                    ['label' => 'Most Registrations',   'value' => $os['top_event_regs'],   'sub' => $os['top_event']],
                ];
            @endphp
            @foreach($oppStats as $s)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <p class="text-xs text-gray-500 mb-1">{{ $s['label'] }}</p>
                <p class="text-2xl font-bold text-gray-800">{{ $s['value'] }}</p>
                <p class="text-xs text-gray-400 mt-1 truncate" title="{{ $s['sub'] }}">{{ $s['sub'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Per-event summary --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Per-Opportunity Summary</h3>
            @livewire('App\Filament\Widgets\EventOpportunitySummary')
        </div>
    </div>

    {{-- ── Leaderboard Tab ────────────────────────────────────────────── --}}
    <div x-show="tab === 'leaderboard'" x-cloak class="space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Top Volunteers</h3>
            @livewire('App\Filament\Widgets\TopVolunteersLeaderboard')
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-base font-semibold text-gray-800 mb-4">Volunteer Age Distribution</h3>
            @livewire('App\Filament\Widgets\VolunteerAgeDistribution')
        </div>
    </div>

</div>{{-- end x-data tabs --}}

</x-filament-panels::page>
