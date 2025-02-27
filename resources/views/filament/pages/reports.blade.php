@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 Styling */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--multiple {
            border-color: #d1d5db;
            border-radius: 0.375rem;
            min-height: 38px;
            padding: 2px;
        }

        .dark .select2-container--default .select2-selection--multiple {
            background-color: #374151;
            border-color: #4b5563;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e5e7eb;
            border: none;
            border-radius: 0.25rem;
            padding: 2px 8px;
            margin: 3px;
        }

        .dark .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #4b5563;
            color: #fff;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            margin-right: 5px;
            color: #6b7280;
        }

        .select2-dropdown {
            border-color: #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .dark .select2-dropdown {
            background-color: #374151;
            border-color: #4b5563;
            color: #fff;
        }

        .select2-results__option {
            padding: 8px 12px;
        }

        .dark .select2-results__option {
            color: #fff;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb;
        }

        .select2-search--dropdown .select2-search__field {
            border-color: #d1d5db;
            border-radius: 0.25rem;
            padding: 6px;
        }

        .dark .select2-search--dropdown .select2-search__field {
            background-color: #1f2937;
            border-color: #4b5563;
            color: #fff;
        }
    </style>
@endpush

<x-filament-panels::page>
    <!-- Tab Navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700">
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

            <!-- Export Section - ADD THIS SECTION -->
            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Export Volunteers</h3>
                <form action="{{ route('volunteers.export') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Companies Filter -->
                        <div>
                            <label for="companies" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Business Units
                            </label>
                            <select name="companies[]" id="companies" class="select2-dropdown w-full" multiple>
                                @foreach(App\Models\Company::orderBy('name')->get() as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Affiliations Filter -->
                        <div>
                            <label for="affiliations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Affiliations
                            </label>
                            <select name="affiliations[]" id="affiliations" class="select2-dropdown w-full" multiple>
                                @foreach(App\Models\AffiliateType::orderBy('name')->get() as $affiliation)
                                    <option value="{{ $affiliation->id }}">{{ $affiliation->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Events Filter -->
                        <div>
                            <label for="events" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Events
                            </label>
                            <select name="events[]" id="events" class="select2-dropdown w-full" multiple>
                                @foreach(App\Models\Event::orderBy('title')->get() as $event)
                                    <option value="{{ $event->id }}">{{ $event->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date Range Filters -->
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                From Date
                            </label>
                            <input type="date" name="date_from" id="date_from" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>

                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                To Date
                            </label>
                            <input type="date" name="date_to" id="date_to" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white font-medium rounded-md shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export to CSV
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Overview -->
            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Statistics</h3>
                @livewire('App\Filament\Widgets\TotalVolunteerHoursStats')
            </div>

            <!-- Filtered Hours -->
            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Hours</h3>
                @livewire('App\Filament\Widgets\FilteredVolunteerHours')
            </div>

            <!-- Participation List -->
            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Participation</h3>
                @livewire('App\Filament\Widgets\VolunteerParticipationList')
            </div>
        </div>

        <!-- Opportunities Tab -->
        <div class="hidden rounded-lg"
             id="opportunities"
             role="tabpanel"
             aria-labelledby="opportunities-tab">

            <!-- Add the new Event Opportunity Summary widget -->
            <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Event Opportunity Summary</h3>
                @livewire('App\Filament\Widgets\EventOpportunitySummary')
            </div>
        </div>

        <!-- Leaderboard Tab -->
        <div class="hidden rounded-lg"
             id="leaderboard"
             role="tabpanel"
             aria-labelledby="leaderboard-tab">
            <div class="space-y-6">
                <!-- Top Volunteers Section -->
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Top Volunteers</h3>
                    @livewire('App\Filament\Widgets\TopVolunteersLeaderboard')
                </div>

                <!-- Top Companies Section -->
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Top Business Units</h3>
                    @livewire('App\Filament\Widgets\TopCompaniesLeaderboard')
                </div>

                <!-- Age Distribution Section -->
                <div class="bg-white rounded-lg shadow-sm dark:bg-gray-800 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Volunteer Age Distribution</h3>
                    @livewire('App\Filament\Widgets\VolunteerAgeDistribution')
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tabs
            const tabs = new Tabs(document.querySelector('#reportTabs'));
            tabs.show('volunteers-tab');

            // Make sure jQuery is loaded
            if (typeof jQuery !== 'undefined') {
                // Initialize Select2 with improved configuration
                $('.select2-dropdown').select2({
                    placeholder: 'Select options',
                    allowClear: true,
                    width: '100%',
                    closeOnSelect: false,
                    templateResult: formatOption,
                    templateSelection: formatSelection
                });

                // Custom formatting for dropdown options
                function formatOption(option) {
                    if (!option.id) return option.text;
                    return $('<span>' + option.text + '</span>');
                }

                // Custom formatting for selected options
                function formatSelection(option) {
                    if (!option.id) return option.text;
                    return option.text;
                }

                // Dark mode detection and adjustment
                if (document.documentElement.classList.contains('dark')) {
                    $('.select2-dropdown').data('select2').$dropdown.addClass('dark');
                }

                // Add search box placeholder
                setTimeout(function() {
                    $('.select2-search__field').attr('placeholder', 'Type to search...');
                }, 100);
            }
        });
    </script>
    @endpush
</x-filament-panels::page>
