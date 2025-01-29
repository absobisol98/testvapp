<x-filament-panels::page>
    <style>
        /* For Volunteer Dashboard Container(Start) */
        .fi-main {
            margin: 0px !important;
            padding: 0px 0px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            border-radius: 0px !important;
            max-width: 100% !important;
        }

        .fi-page section {
            padding: 0px 0px 32px 0px !important;
        }

        /* For Volunteer Dashboard Container(End) */
    </style>

    <div class="w-full flex flex-col gap-8">
        @livewire(\App\Filament\Widgets\HeroBannerWidget::class)


        {{-- If the user is a Volunteer --}}
        @if (auth()->user()->hasRole('Volunteer'))
            @livewire(\App\Filament\Widgets\AdsWidget::class)
            @livewire(\App\Filament\Widgets\UpcomingOpportunityWidget::class)
            {{-- @livewire(\App\Filament\Widgets\RecentOpportunitiesWidget::class) --}}
        @endif

        {{-- If the user is an AFI Admin --}}
        @if (auth()->user()->hasRole('super_admin'))
            @livewire(\App\Filament\Widgets\AFIAdminOpportunitiesWidget::class)
            @livewire(\App\Filament\Widgets\BusinessUnitOrExternalPartersWidget::class)
            @livewire(\App\Filament\Widgets\VolunteersWidget::class)
        @endif

        {{-- If the user is a Partner --}}
        @if (false)
            @livewire(\App\Filament\Widgets\PartnersOnGoingOpportunitiesWidget::class)
            @livewire(\App\Filament\Widgets\PartnersMyOpportunitiesWidget::class)
            @livewire(\App\Filament\Widgets\FacilitatorWidget::class)
        @endif
    </div>
</x-filament-panels::page>
