<x-filament-panels::page>
    <div class="w-full flex flex-col items-center justify-between gap-8">
        {{-- STATS OVERVIEW --}}
        <div class="w-full">
            @livewire(\App\Livewire\StatsOverviewWidget::class)
        </div>

        {{-- ADS SECTION --}}
        <div class="w-full px-4">
            @livewire(\App\Livewire\AdsSectionWidget::class)
        </div>

        {{-- UPCOMING OPPORTUNITY --}}
        <div class="w-full px-4">
            @livewire(\App\Livewire\UpcomingOpportunityWidget::class)
        </div>

        {{-- RECENT OPPORTUNITIES --}}
        <div class="w-full px-4">
            @livewire(\App\Livewire\RecentOpportunitiesWidget::class)
        </div>
    </div>
</x-filament-panels::page>
