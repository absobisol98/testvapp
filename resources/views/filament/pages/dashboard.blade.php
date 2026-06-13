<x-filament-panels::page>
    <style>
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
    </style>

    @php $activeRole = auth()->user()->activeRole(); @endphp

    <div class="w-full flex flex-col gap-8">
        @livewire(\App\Filament\Widgets\HeroBannerWidget::class)

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
</x-filament-panels::page>
