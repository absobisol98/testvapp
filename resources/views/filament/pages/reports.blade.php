<x-filament-panels::page>
    

    





<div class="mb-4 border-b border-gray-200 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist" ata-tabs-active-classes="text-white bg-primary-500 hover:text-purple-600 " data-tabs-inactive-classes="dark:border-transparent text-primary-500 hover:text-primary-600 dark:text-gray-400 border-gray-100 hover:border-gray-300">
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab" data-tabs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Overview</button>
        </li>
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="dashboard-tab" data-tabs-target="#dashboard" type="button" role="tab" aria-controls="dashboard" aria-selected="false">Volunteers By Department</button>
        </li>
        <li class="me-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-t-lg" id="settings-tab" data-tabs-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false">Volunteers By Program</button>
        </li>
        <li role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-t-lg opacity-50" id="contacts-tab" data-tabs-target="#contacts" type="button" role="tab" aria-controls="contacts" aria-selected="false" disabled>Volunteers By Event</button>
        </li>
    </ul>
</div>
<div id="default-tab-content">
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800 grid grid-cols-2 gap-4" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="col-span-2">
            @livewire(\App\Filament\Widgets\StatsOverview::class)
        </div>
        <div>
            @livewire(\App\Filament\Widgets\VolunteerUsageWidgetByDepartment::class, ['progNames' =>$widgetData['widgetByDepartment']['progNames'] ,'count'=>$widgetData['widgetByDepartment']['count'] ,'overall_hrs' => $widgetData['widgetByDepartment']['overall_hrs']])  
        </div>
        <div>
            @livewire(\App\Filament\Widgets\VolunteerUsageWidgetByProgram::class, ['progNames' =>$widgetData['widgetByProgram']['progNames'] ,'count'=>$widgetData['widgetByProgram']['count'] ,'overall_hrs' => $widgetData['widgetByProgram']['overall_hrs']])
        </div>
        <div class="col-span-2">
            @livewire(\App\Filament\Widgets\VolunteerSignupPerMonth::class)
        </div>
    </div>
    
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">
        @livewire(\App\Filament\Widgets\ReportByDepartmentBar::class)
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="settings" role="tabpanel" aria-labelledby="settings-tab">
        @livewire(\App\Filament\Widgets\ReportByProgram::class)
    </div>
    <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="contacts" role="tabpanel" aria-labelledby="contacts-tab">
        <p class="text-sm text-gray-500 dark:text-gray-400">This is some placeholder content the <strong class="font-medium text-gray-800 dark:text-white">Contacts tab's associated content</strong>. Clicking another tab will toggle the visibility of this one for the next. The tab JavaScript swaps classes to control the content visibility and styling.</p>
    </div>
</div>

</x-filament-panels::page>
