<x-filament-widgets::widget>
    {{-- OPPORTUNITIES --}}
    <div class="w-full">
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
                        stroke: #FF9141;
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
                    <div class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                            alt="">
                    </div>

                    <div class="w-full">
                        <p class="text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}</p>

                        <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, {{ $opportunity->location }}</p>

                        <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
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

                    <div class="w-[200px]">
                        <a href="">
                            <div
                                class="h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                <p class="font-[400] text-[18px] text-white">CHECK-IN</p>
                            </div>
                        </a>
                    </div>
                </div>

                @if (!$loop->last)
                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                @endif
            @endforeach




            {{-- List 2 --}}
            {{-- <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                    <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                        alt="">
                </div>

                <div class="w-full">
                    <p class="text-[28px] font-[400] text-[#03498D]">Ayala Reading Am<span
                            class="font-[700]">BASA</span>dors Storytelling Webinar</p>

                    <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>

                    <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                            <p class="font-[600]">DATE: Aug-27-2024</p>
                            <p class="font-[600]">2:00 PM - 6:00 PM</p>
                        </div>
                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                            <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the
                                session</p>
                            <div class="flex items-center justify-start gap-4">
                                <p><span class="font-[600]">BATCH 1:</span> 2:00 PM - 4:00 PM</p>
                                <p><span class="font-[600]">BATCH 2:</span> 2:00 PM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-[200px]">
                    <a href="">
                        <div
                            class="h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                            <p class="font-[400] text-[18px] text-white">CHECK-IN</p>
                        </div>
                    </a>
                </div>
            </div> --}}

            {{-- <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div> --}}

            {{-- List 3 --}}
            {{-- <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                    <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                        alt="">
                </div>

                <div class="w-full">
                    <p class="text-[28px] font-[400] text-[#03498D]">Ayala Reading Am<span
                            class="font-[700]">BASA</span>dors Storytelling Webinar</p>

                    <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>

                    <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                            <p class="font-[600]">DATE: Aug-27-2024</p>
                            <p class="font-[600]">2:00 PM - 6:00 PM</p>
                        </div>
                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                            <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the
                                session</p>
                            <div class="flex items-center justify-start gap-4">
                                <p><span class="font-[600]">BATCH 1:</span> 2:00 PM - 4:00 PM</p>
                                <p><span class="font-[600]">BATCH 2:</span> 2:00 PM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-[200px]">
                    <a href="">
                        <div
                            class="h-[48px] w-[200px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                            <p class="font-[400] text-[18px] text-white">CHECK-IN</p>
                        </div>
                    </a>
                </div>
            </div> --}}

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
