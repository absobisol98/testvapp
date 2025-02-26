<x-filament-panels::page>
    <!-- Tab Navigation -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center gap-4"
            id="reportTabs"
            data-tabs-toggle="#reportTabContent"
            role="tablist">
            <li role="presentation">
                <button class="inline-flex items-center px-6 py-3 border-b-2 border-transparent rounded-t-lg hover:text-primary-600 hover:border-primary-600 active group"
                        id="volunteers-tab"
                        data-tabs-target="#volunteers"
                        type="button"
                        role="tab"
                        aria-controls="volunteers"
                        aria-selected="true">
                    <x-heroicon-o-users class="w-5 h-5 mr-2" />
                    Volunteers
                </button>
            </li>
            <li role="presentation">
                <button class="inline-flex items-center px-6 py-3 border-b-2 border-transparent rounded-t-lg hover:text-primary-600 hover:border-primary-600 group"
                        id="opportunities-tab"
                        data-tabs-target="#opportunities"
                        type="button"
                        role="tab"
                        aria-controls="opportunities"
                        aria-selected="false">
                    <x-heroicon-o-calendar class="w-5 h-5 mr-2" />
                    Opportunities
                </button>
            </li>
            <li role="presentation">
                <button class="inline-flex items-center px-6 py-3 border-b-2 border-transparent rounded-t-lg hover:text-primary-600 hover:border-primary-600 group"
                        id="leaderboard-tab"
                        data-tabs-target="#leaderboard"
                        type="button"
                        role="tab"
                        aria-controls="leaderboard"
                        aria-selected="false">
                    <x-heroicon-o-trophy class="w-5 h-5 mr-2" />
                    Leaderboard
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Content -->
    <div id="reportTabContent" class="space-y-6">
        <!-- Volunteers Tab -->
        <div class="block space-y-6 rounded-lg"
             id="volunteers"
             role="tabpanel"
             aria-labelledby="volunteers-tab">
            <!-- Stats Overview -->
            <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Statistics</h3>
                @livewire('App\Filament\Widgets\TotalVolunteerHoursStats')
            </div>


            <!-- Filtered Hours -->
            <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Hours</h3>
                @livewire('App\Filament\Widgets\FilteredVolunteerHours')
            </div>

            <!-- Participation List -->
            <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Participation</h3>
                @livewire('App\Filament\Widgets\VolunteerParticipationList')
            </div>
        </div>

        <!-- Opportunities Tab -->
        <div class="hidden rounded-lg"
             id="opportunities"
             role="tabpanel"
             aria-labelledby="opportunities-tab">
            <!-- Stats Overview -->
            <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Opportunity Statistics</h3>
                @livewire('App\Filament\Widgets\OpportunityStatistics')
            </div>

            <!-- Business Unit Breakdown -->
            <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Business Unit Participation</h3>
                @livewire('App\Filament\Widgets\BusinessUnitParticipationList')
            </div>
        </div>

        <!-- Leaderboard Tab -->
        <div class="hidden rounded-lg"
             id="leaderboard"
             role="tabpanel"
             aria-labelledby="leaderboard-tab">
            <div class="p-6 bg-white rounded-lg shadow-sm dark:bg-gray-800">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Top Volunteers</h3>
                @livewire('App\Filament\Widgets\TopVolunteersLeaderboard')
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = new Tabs(document.querySelector('#reportTabs'));
            tabs.show('volunteers-tab');
        });
    </script>
    @endpush
</x-filament-panels::page>
