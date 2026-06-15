<x-filament-widgets::widget>
    {{-- OPPORTUNITIES --}}
    <div class="w-full px-8">
        <div class="flex items-center justify-between gap-4 font-[400]">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Upcoming Opportunity</p>
            </div>

            <div class="flex items-center justify-between gap-4 md:gap-8">
                <a class="text-[20px] font-[400] hover:underline" href="">VIEW</a>

                {{-- Style for the tabs --}}
                <style>
                    /* Default stroke color */
                    #icon svg path,
                    #icon-calendar svg path {
                        stroke: #000000;
                        /* Default stroke color */
                        transition: stroke 0.3s ease;
                        /* Smooth transition for stroke color change */
                    }

                    /* Stroke color on hover */
                    #icon:hover svg path,
                    #icon-calendar:hover svg path {
                        stroke: #FF781E;
                        /* Change this to your desired hover color */
                    }

                    /* Stroke color on click (active state) */
                    #icon.active svg path,
                    #icon-calendar.active svg path {
                        stroke: #f7723a;
                        /* Change this to your desired active color */
                    }
                </style>

                <button id="icon" class="w-[32px] h-[32px] active" onclick="changeTab('list')">
                    @include('custom.icons.landing-page-icons', ['icon' => 'list-32'])
                </button>

                <button id="icon-calendar" class="w-[32px] h-[32px]" onclick="changeTab('calendar')">
                    @include('custom.icons.landing-page-icons', ['icon' => 'calendar-32'])
                </button>
            </div>
        </div>

        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>

        {{-- OPPORTUNITIES List --}}
        <div id="opportunityList" class="w-full flex flex-col items-center justify-between gap-4 p-4 duration-300 text-[#000000]">
            {{-- List --}}
            @foreach ($opportunities as $index => $opportunity)
                <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="h-fit md:w-[200px] flex items-center justify-center overflow-hidden" style="width: max-content">
                        <img class="w-[auto] md:w-[200px] h-[220px] md:h-[140px] object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                    </div>

                    <div class="w-full">
                        <p class="text-2xl font-[700] text-[#03498D] mr-[12px] mb-[4px] cursor-pointer capitalize leading-none">{{ $opportunity->title }}</p>
                        <p class="text-lg font-[400] mb-[12px]">Zoom Webinar Online, {{ $opportunity->location }}</p>

                        <div class="w-full flex items-center justify-start text-[14px] font-[400] gap-4">
                            <div class="w-fit flex flex-col items-start justify-between gap-1">
                                <p class="font-[600]">DATE: {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}</p>
                                <p class="font-[600]">{{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}</p>
                            </div>
                            <div class="w-fit flex flex-col items-start justify-between gap-1">
                                <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the
                                    session</p>
                                <div class="flex items-center justify-start gap-4">
                                    @foreach ($opportunity->slots as $index => $slot)
                                        <p>
                                            <span class="font-[600]">BATCH {{ $index + 1 }}:</span>
                                            {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                            {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="width:200px">
                        <a href="">
                            <div
                                class="h-[48px] w-full bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#f7723a]">
                                <p class="font-400 text-lg text-white">CHECK-IN</p>
                            </div>
                        </a>
                    </div>
                </div>

                @if (!$loop->last)
                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                @endif
            @endforeach
        </div>

        {{-- OPPORTUNITIES Calendar --}}
        <div id="opportunityCalendar"
            class="w-full gap-8 p-4 duration-300">
            @livewire(\App\Filament\Widgets\CalendarWidget::class)
        </div>

        {{-- Tab Scripts --}}
        <script>
            const opportunityList = document.getElementById("opportunityList");
            const opportunityCalendar = document.getElementById("opportunityCalendar");
            const iconList = document.getElementById("icon");
            const iconCalendar = document.getElementById("icon-calendar");

            function changeTab(type) {
                if (type === "list") {
                    opportunityList.classList.remove("hidden");
                    opportunityCalendar.classList.add("hidden");

                    // Set the list icon to active
                    iconList.classList.add("active");
                    iconCalendar.classList.remove("active");
                } else if (type === "calendar") {
                    opportunityList.classList.add("hidden");
                    opportunityCalendar.classList.remove("hidden");

                    // Set the calendar icon to active
                    iconCalendar.classList.add("active");
                    iconList.classList.remove("active");
                }
            }

            setTimeout(() => {
                opportunityCalendar.classList.add("hidden");
            }, 2000);
        </script>
    </div>
</x-filament-widgets::widget>
