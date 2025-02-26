<x-filament-widgets::widget>
    <x-filament::section>
        {{ $this->form }}

        @if(!empty($summary))
            <div class="mt-6 space-y-6">
                <!-- Overall Statistics -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-600">Total Volunteer Hours</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $summary['total_hours'] }}</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="text-sm text-gray-600">Total Volunteers</div>
                        <div class="text-2xl font-bold text-primary-600">{{ $summary['total_volunteers'] }}</div>
                    </div>
                </div>

                <!-- Volunteer Type Distribution -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Volunteer Distribution</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm">Ayala Employees</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">{{ $summary['ayala_volunteers']['count'] }}</span>
                                <span class="text-sm text-gray-500">({{ $summary['ayala_volunteers']['percentage'] }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $summary['ayala_volunteers']['percentage'] }}%"></div>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-sm">Non-Ayala Volunteers</span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium">{{ $summary['non_ayala_volunteers']['count'] }}</span>
                                <span class="text-sm text-gray-500">({{ $summary['non_ayala_volunteers']['percentage'] }}%)</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-secondary-600 h-2.5 rounded-full" style="width: {{ $summary['non_ayala_volunteers']['percentage'] }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Company Breakdown -->
                @if(!empty($summary['company_breakdown']))
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Business Unit Breakdown</h3>
                        <div class="space-y-4">
                            @foreach($summary['company_breakdown'] as $company)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm">{{ $company['name'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium">{{ $company['count'] }}</span>
                                        <span class="text-sm text-gray-500">({{ $company['percentage'] }}%)</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-primary-600 h-2.5 rounded-full" style="width: {{ $company['percentage'] }}%"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Volunteer List -->
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-medium mb-4">Volunteer List</h3>
                    {{ $this->table }}
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
