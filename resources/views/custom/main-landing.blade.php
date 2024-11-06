@extends('custom.layouts.app')

@section('content')
    <div id="mainLandingPage" class="w-full flex flex-col items-center justify-center">


        {{-- Hero Banner Section --}}
        <div class="h-[90vh] w-full flex flex-col items-start justify-center px-[80px] py-12 text-white"
            style="background: url('{{ asset('img/background-img.png') }}') no-repeat center center; background-size: cover;">

            <div class="max-w-[671px]">
                <p class="font-[700] text-[60px]">Your involvement is important to us!</p>
            </div>

            <div class="max-w-[735px] pb-[100px]">
                <p class="font-[400] text-[28px]">Ayala Corporate Citizenship and Volunteer Program</p>
            </div>
        </div>


        {{-- Opportunity Section --}}
        <div class="w-[98%] bg-[#FFFFFFE5] m-[-25vh] px-8 pt-8 pb-[80px] mb-8 xl:w-[80%] lg:w-[85%] md:w-[90%] sm:w-[95%]">
            {{-- Featured Opportunity --}}
            <div class="flex items-center justify-center gap-4">
                <div class="flex items-center justify-between h-[400px] w-[40%] gap-4"
                    style="background: url('{{ asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                    <div class="h-full w-full flex items-end justify-start p-4"
                        style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                        <img class="w-[30%]" src="{{ asset('img/logo-colored.png') }}" alt="">
                    </div>
                </div>
        
                <div class="w-[60%]">
                    <p class="text-[18px] font-[400]">FEATURED OPPORTUNITY</p>
                    <p class="text-[40px] font-[700] text-[#03498D] mt-3">Lorem ipsum sit dolorem ipsum sit dolor met.</p>
        
                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4 max-w-[600px]"></div>
        
                    <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>
                    <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                            <p class="font-[600]">DATE: Aug-27-2024</p>
                            <p class="font-[600]">2:00 PM - 6:00 PM</p>
                        </div>
                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                            <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the session</p>
                            <div class="flex items-center justify-start gap-4">
                                <p><span class="font-[600]">BATCH 1:</span> 2:00 PM - 4:00 PM</p>
                                <p><span class="font-[600]">BATCH 2:</span> 2:00 PM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>
        
                    <div class="flex items-center justify-start gap-4 mt-8 max-w-[416px]">
                        <button onclick="openModal()" class="w-full">
                            <div
                                class="h-[48px] w-full bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                                <p class="font-[400] text-[18px] text-white">VIEW DETAILS</p>
                            </div>
                        </button>
        
                        <a href="" class="w-full">
                            <div
                                class="h-[48px] w-full bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                <p class="font-[400] text-[18px] text-white">JOIN</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        
            <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
        
            {{-- OPPORTUNITIES --}}
            <div class="w-full">
                <div class="flex items-center justify-between gap-4 font-[400]">
                    <div class="w-fit">
                        <p class="text-[40px]">OPPORTUNITIES</p>
                    </div>
        
                    <div class="flex items-center justify-between gap-8">
                        <a class="text-[20px] font-[400] hover:underline" href="">VIEW</a>
        
                        {{-- Style for the tabs --}}
                        <style>
                            /* Default stroke color */
                            #icon svg path,
                            #icon-calendar svg path {
                                stroke: #000000; /* Default stroke color */
                                transition: stroke 0.3s ease; /* Smooth transition for stroke color change */
                            }
                        
                            /* Stroke color on hover */
                            #icon:hover svg path,
                            #icon-calendar:hover svg path {
                                stroke: #FF781E; /* Change this to your desired hover color */
                            }
                        
                            /* Stroke color on click (active state) */
                            #icon.active svg path,
                            #icon-calendar.active svg path {
                                stroke: #FF9141; /* Change this to your desired active color */
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
                <div id="opportunityList" class="w-full flex flex-col items-center justify-between gap-8 p-4 duration-300">
                    {{-- List 1 --}}
                    <div class="w-full flex items-center justify-between gap-8">
                        <div class="w-[200px] h-[140px] flex items-center justify-center overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                        </div>
        
                        <div class="w-full">
                            <p class="text-[28px] font-[400] text-[#03498D]">Ayala Reading Am<span class="font-[700]">BASA</span>dors Storytelling Webinar</p>
        
                            <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>
        
                            <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                                <div class="w-fit flex flex-col items-start justify-between gap-1">
                                    <p class="font-[600]">DATE: Aug-27-2024</p>
                                    <p class="font-[600]">2:00 PM - 6:00 PM</p>
                                </div>
                                <div class="w-fit flex flex-col items-start justify-between gap-1">
                                    <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the session</p>
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
                                    <p class="font-[400] text-[18px] text-white">JOIN</p>
                                </div>
                            </a>
                        </div>
                    </div>
        
                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
        
                    {{-- List 2 --}}
                    <div class="w-full flex items-center justify-between gap-8">
                        <div class="w-[200px] h-[140px] flex items-center justify-center overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                        </div>
        
                        <div class="w-full">
                            <p class="text-[28px] font-[400] text-[#03498D]">Ayala Reading Am<span class="font-[700]">BASA</span>dors Storytelling Webinar</p>
        
                            <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>
        
                            <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                                <div class="w-fit flex flex-col items-start justify-between gap-1">
                                    <p class="font-[600]">DATE: Aug-27-2024</p>
                                    <p class="font-[600]">2:00 PM - 6:00 PM</p>
                                </div>
                                <div class="w-fit flex flex-col items-start justify-between gap-1">
                                    <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the session</p>
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
                                    <p class="font-[400] text-[18px] text-white">JOIN</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
        
                    {{-- List 3 --}}
                    <div class="w-full flex items-center justify-between gap-8">
                        <div class="w-[200px] h-[140px] flex items-center justify-center overflow-hidden">
                            <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}" alt="">
                        </div>
        
                        <div class="w-full">
                            <p class="text-[28px] font-[400] text-[#03498D]">Ayala Reading Am<span class="font-[700]">BASA</span>dors Storytelling Webinar</p>
        
                            <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>
        
                            <div class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                                <div class="w-fit flex flex-col items-start justify-between gap-1">
                                    <p class="font-[600]">DATE: Aug-27-2024</p>
                                    <p class="font-[600]">2:00 PM - 6:00 PM</p>
                                </div>
                                <div class="w-fit flex flex-col items-start justify-between gap-1">
                                    <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the session</p>
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
                                    <p class="font-[400] text-[18px] text-white">JOIN</p>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>

                {{-- OPPORTUNITIES Calendar --}}
                <div id="opportunityCalendar" class="w-full flex flex-col items-center justify-between gap-8 p-4 duration-300 hidden">
                    {{-- This is a sample calendar --}}
                    <div id="calendar" class="w-full grid grid-cols-7 gap-0 border border-gray-300 rounded-md overflow-hidden">
                        <div class="text-center font-bold border-b border-gray-300">Sun</div>
                        <div class="text-center font-bold border-b border-gray-300">Mon</div>
                        <div class="text-center font-bold border-b border-gray-300">Tue</div>
                        <div class="text-center font-bold border-b border-gray-300">Wed</div>
                        <div class="text-center font-bold border-b border-gray-300">Thu</div>
                        <div class="text-center font-bold border-b border-gray-300">Fri</div>
                        <div class="text-center font-bold border-b border-gray-300">Sat</div>
                        <!-- Placeholder for empty days, adjust as necessary for the month -->
                        <div class="h-20 flex items-center justify-center border border-gray-300"></div> <!-- Empty for padding -->
                        <div class="h-20 flex items-center justify-center border border-gray-300"></div> <!-- Empty for padding -->
                        <div class="h-20 flex items-center justify-center border border-gray-300"></div> <!-- Empty for padding -->
                        <div class="h-20 flex items-center justify-center border border-gray-300"></div> <!-- Empty for padding -->
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">1</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">2</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">3</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">4</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">5</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">6</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">7</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">8</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">9</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">10</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">11</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">12</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">13</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">14</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">15</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">16</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">17</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">18</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">19</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">20</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">21</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">22</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">23</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">24</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">25</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">26</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">27</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">28</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">29</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">30</div>
                        <div class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">31</div>
                    </div>
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
                </script>                
            </div>
        </div>
    

        {{--  --}}
        <div class="relative w-full">
            <div class="absolute w-full z-10 flex items-center justify-between">
                <div class="h-[33px] w-full max-w-[535px] bg-[#005096]"></div>
                <div class="h-[33px] w-full max-w-[535px] bg-[#FF781E]"></div>
            </div>
        
            <div class="h-[452px] w-full flex flex-col items-center justify-center text-white mt-4 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('img/background-img-2.png') }}')">
                <div class="h-full w-full px-20 py-12 flex items-center justify-center bg-black/10">
                    <div class="flex items-center justify-center gap-12">
                        <!-- Statistics Item -->
                        @foreach ([
                            ['number' => '1269', 'label' => 'VOLUNTEERS'],
                            ['number' => '284.98', 'label' => 'HOURS LOGGED'],
                            ['number' => '20', 'label' => 'PROGRAMS'],
                            ['number' => '300', 'label' => 'OPPORTUNITIES']
                        ] as $stat)
                            <div class="w-fit flex flex-col items-center justify-center gap-1">
                                <p class="text-[80px] font-bold">{{ $stat['number'] }}</p>
                                <p class="text-[20px] font-medium">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        

        {{-- For Not Logged In Users Only --}}
        @guest
            {{-- Our Program Section --}}
            <div class="w-full grid grid-cols-2">
                <!-- Our Program Section -->
                <div class="col-span-1 py-24 px-20 flex flex-col items-center justify-between gap-8 font-medium text-white bg-[#03498D]">
                    <div class="w-full min-h-[450px] flex flex-col items-start">
                        <p class="text-[36px] font-semibold">Our Program</p>
                        <p class="text-[48px] font-bold">Corporate Citizenship and Volunteerism</p>
                        <p class="text-[20px] font-light">
                            We believe in contributing to the nation’s development goals by adapting to the evolving needs of
                            stakeholders to remain relevant and responsive. Through our programs, we affirm our commitment to
                            aligning, giving focus, and making an impact in the lives of people in our conglomerate, communities,
                            and country.
                        </p>
                    </div>
                    <div class="w-full">
                        <a href="">
                            <div class="h-16 w-[305px] bg-[#FF781E] flex items-center justify-center hover:bg-[#FF9141]">
                                <p class="font-medium text-lg text-white">SEE ALL PROGRAMS</p>
                            </div>
                        </a>
                    </div>
                </div>
            
                <!-- Become a Volunteer Section -->
                <div class="col-span-1 py-24 px-20 flex flex-col items-center justify-between gap-8 font-medium text-white relative bg-cover bg-center bg-no-repeat"
                     style="background-image: url('{{ asset('img/ayala-foundation-bg-1.jpg') }}')">
                    <!-- Diagonal Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#03498D4D] to-[#03498D4D] z-0"
                         style="clip-path: polygon(0 0, 100% 100%, 0 100%);"></div>
                    
                    <div class="w-full min-h-[450px] flex flex-col items-start z-10">
                        <p class="text-[36px] font-semibold">Become a</p>
                        <p class="text-[48px] font-bold">Volunteer</p>
                        <p class="text-[24px] w-full max-w-[510px] font-light">Ayala Corporate Citizenship and Volunteer Program</p>
                    </div>
                    <div class="w-full z-10">
                        <a href="">
                            <div class="h-16 w-[305px] bg-[#FF781E] flex items-center justify-center hover:bg-[#FF9141]">
                                <p class="font-medium text-lg text-white">BECOME A VOLUNTEER</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endguest


        {{-- For Logged In Users Only --}}
        @auth
            {{-- Our Program Section --}}
            <div class="w-full grid grid-cols-2">
                <!-- Program Section with Swiper -->
                <div class="col-span-1 py-24 px-20 flex flex-col items-center justify-between gap-8 font-medium text-white bg-[#F55E1D]">
                    <div class="program-swiper-container w-full overflow-hidden">
                        <div class="swiper-wrapper w-full">
                            <!-- Slide 1 -->
                            <div class="swiper-slide">
                                <div class="flex flex-col items-center gap-8">
                                    <div class="w-full min-h-[450px] flex flex-col items-start">
                                        <p class="text-[36px] font-semibold">Our Program</p>
                                        <p class="text-[48px] font-bold">Community Development 1</p>
                                        <p class="text-[20px] font-light">
                                            We aim to elevate Filipino families from poverty to the middle class. To achieve this goal, we
                                            take systemic approaches to fulfilling basic needs — enhancing nutrition, health, education, WASH
                                            (water, sanitation, and hygiene), electrification, and connectivity within our target communities.
                                            We boost economic vitality through programs in financial inclusion, sustainable livelihood, and by
                                            supporting local museums and libraries. Working closely with partners, we serve as an integrator
                                            of interventions to find solutions that are suited and relevant to the needs of communities.
                                        </p>
                                    </div>
                                    <div class="w-full">
                                        <a href="">
                                            <div class="h-12 w-[325px] bg-[#03498D] flex items-center justify-center hover:bg-[#1A67B1]">
                                                <p class="font-medium text-lg text-white">SEE ALL OPPORTUNITIES</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
            
                            <!-- Slide 2 -->
                            <div class="swiper-slide">
                                <div class="flex flex-col items-center gap-8">
                                    <div class="w-full min-h-[450px] flex flex-col items-start">
                                        <p class="text-[36px] font-semibold">Our Program</p>
                                        <p class="text-[48px] font-bold">Community Development 2</p>
                                        <p class="text-[20px] font-light">
                                            We aim to elevate Filipino families from poverty to the middle class. To achieve this goal, we
                                            take systemic approaches to fulfilling basic needs — enhancing nutrition, health, education, WASH
                                            (water, sanitation, and hygiene), electrification, and connectivity within our target communities.
                                            We boost economic vitality through programs in financial inclusion, sustainable livelihood, and by
                                            supporting local museums and libraries. Working closely with partners, we serve as an integrator
                                            of interventions to find solutions that are suited and relevant to the needs of communities.
                                        </p>
                                    </div>
                                    <div class="w-full">
                                        <a href="">
                                            <div class="h-12 w-[325px] bg-[#03498D] flex items-center justify-center hover:bg-[#1A67B1]">
                                                <p class="font-medium text-lg text-white">SEE ALL OPPORTUNITIES</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <div class="w-full flex items-center justify-start gap-4">
                        <div class="program-button-36-prev w-[36px] h-[36px] flex items-center justify-center bg-white hover:bg-gray-100">
                            @include('custom.icons.landing-page-icons', ['icon' => 'navigate-prev-36'])
                        </div>
                        <div class="program-button-36-next w-[36px] h-[36px] flex items-center justify-center bg-white hover:bg-gray-100">
                            @include('custom.icons.landing-page-icons', ['icon' => 'navigate-next-36'])
                        </div>
                    </div>
            
                    <!-- Swiper Script -->
                    <script>
                        const programSwiper = new Swiper('.program-swiper-container', {
                            loop: true,
                            slidesPerView: 1,
                            navigation: {
                                nextEl: '.program-button-36-next',
                                prevEl: '.program-button-36-prev',
                            },
                        });
                    </script>
                </div>
            
                <!-- Right Section with Background and Overlay -->
                <div class="col-span-1 py-24 px-20 flex flex-col items-center justify-between gap-8 font-medium text-white relative bg-cover bg-center bg-no-repeat"
                     style="background-image: url('{{ asset('img/ayala-foundation-bg-2.png') }}')">
                    <!-- Diagonal Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-tr from-[#F55E1D4D] to-[#03498D4D] z-0"
                         style="clip-path: polygon(0 0, 100% 100%, 0 100%);"></div>
                </div>
            </div>
        @endauth

        {{-- Stories Section --}}
        <div class="w-full h-fit relative flex items-center justify-center text-[24px] font-medium">
            <div class="grid grid-cols-2 gap-12 w-full z-10 px-20 py-12">
                <!-- Left Section: Stories -->
                <div class="col-span-1 h-full">
                    <p class="mb-10">STORIES</p>
        
                    <div class="flex items-center justify-between h-[509px] gap-4 bg-cover bg-center"
                        style="background-image: url('{{ asset('img/ayala-foundation-bg-3.jpg') }}');">
                        <div class="h-full w-full flex items-end p-4 bg-gradient-to-t from-[#03498D] to-transparent"></div>
                    </div>
        
                    <style>
                        .swiper-slide {
                            flex-grow: 1;
                            min-width: 50%;
                        }
                    </style>
        
                    <div class="w-[80%] p-2 flex items-start justify-between gap-4 bg-white">
                        <div class="stories-swiper-container w-full overflow-hidden">
                            <div class="swiper-wrapper w-full">
                                <!-- Slide 1 -->
                                <div class="swiper-slide">
                                    <div class="w-full flex items-start justify-between gap-4">
                                        <div class="w-fit min-w-[104px] p-4 bg-white shadow-sm flex flex-col items-center">
                                            <p>AUG</p>
                                            <p>21</p>
                                        </div>
                                        <div class="w-full text-[#03498D]">
                                            <p class="text-[32px] font-semibold">Opportunity Story 1 goes here</p>
                                            <p class="text-[14px] mb-8">Lorem Ipsum is simply dummy text of the printing and
                                                typesetting industry.</p>
                                            <a href="">
                                                <div
                                                    class="h-12 w-[219px] bg-[#F55E1D] flex items-center justify-center hover:bg-[#FF8252]">
                                                    <p class="font-medium text-lg text-white">READ MORE</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
        
                                <!-- Slide 2 -->
                                <div class="swiper-slide">
                                    <div class="w-full flex items-start justify-between gap-4">
                                        <div class="w-fit min-w-[104px] p-4 bg-white shadow-sm flex flex-col items-center">
                                            <p>AUG</p>
                                            <p>25</p>
                                        </div>
                                        <div class="w-full text-[#03498D]">
                                            <p class="text-[32px] font-semibold">Opportunity Story 2 goes here</p>
                                            <p class="text-[14px] mb-8">Lorem Ipsum is simply dummy text of the printing and
                                                typesetting industry.</p>
                                            <a href="">
                                                <div
                                                    class="h-12 w-[219px] bg-[#F55E1D] flex items-center justify-center hover:bg-[#FF8252]">
                                                    <p class="font-medium text-lg text-white">READ MORE</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        
                        <!-- Carousel Navigation Buttons -->
                        <div class="stories-button-24-prev w-fit flex items-center justify-between gap-4">
                            <div class="w-[24px] h-[24px] flex items-center justify-center bg-[#f3f2f2] hover:bg-gray-200">
                                @include('custom.icons.landing-page-icons', ['icon' => 'navigate-prev-36'])
                            </div>
                            <div
                                class="stories-button-24-next w-[24px] h-[24px] flex items-center justify-center bg-[#f3f2f2] hover:bg-gray-200">
                                @include('custom.icons.landing-page-icons', ['icon' => 'navigate-next-36'])
                            </div>
                        </div>
                    </div>
        
                    <!-- Swiper Script -->
                    <script>
                        const storiesSwiper = new Swiper('.stories-swiper-container', {
                            loop: true,
                            slidesPerView: 1,
                            navigation: {
                                nextEl: '.stories-button-24-next',
                                prevEl: '.stories-button-24-prev',
                            },
                        });
                    </script>
                </div>
        
                <!-- Right Section: Testimonials -->
                <div class="col-span-1 h-full flex flex-col items-center justify-between gap-16 text-white p-16 bg-[#03498DB2]">
                    <p>VOLUNTEER TESTIMONIALS</p>
        
                    <div class="flex items-center gap-4 h-fit">
                        <div class="w-16 h-full flex items-start">
                            @include('custom.icons.landing-page-icons', ['icon' => 'double-quote'])
                        </div>
                        <p class="px-2 py-4 text-[36px]">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard</p>
                        <div class="w-16 h-full flex items-end">
                            @include('custom.icons.landing-page-icons', ['icon' => 'double-quote'])
                        </div>
                    </div>
        
                    <div class="w-full px-16">
                        <p>JUAN DELA CRUZ</p>
                        <p class="text-[#817B7B]">Business Associate, Company Name</p>
                    </div>
                </div>
            </div>
        
            <!-- Background with Overlay -->
            <div class="absolute inset-0 flex items-center justify-between z-0">
                <div class="w-[40%] h-full"></div>
                <div class="w-[60%] h-full bg-cover bg-center"
                    style="background-image: linear-gradient(to top right, black, rgba(0, 0, 0, 0)), url('{{ asset('img/ayala-foundation-bg-2.jpg') }}');">
                </div>
            </div>
        </div>
        

        {{-- Featured Opportunity Modal --}}
        <div class="w-full h-fit">
            <div id="featuredImageModal" class="fixed inset-0 flex justify-center items-center z-50 hidden transition-opacity duration-300 bg-black bg-opacity-50" onclick="closeModal(event)">
                <!-- Modal Content -->
                <div class="modal-content bg-white shadow-lg max-w-[80%] w-full p-8 transform transition-all duration-300 scale-95 opacity-0">
                    <div class="w-full flex justify-end items-end p-4">
                        <button id="closeModal" class="text-2xl font-semibold text-black hover:bg-gray-100 focus:outline-none">
                            @include('custom.icons.landing-page-icons', ['icon' => 'close-25'])
                        </button>
                    </div>
        
                    <div class="h-fit max-h-[80vh] overflow-y-auto mb-4">
                        <div class="flex flex-col items-center justify-center">
                            <div class="flex items-center justify-between h-[580px] w-full gap-4 bg-cover bg-center" style="background-image: url('{{ asset('img/ayala-foundation-bg.jpg') }}');">
                                <div class="h-full w-full flex items-end justify-start p-8 bg-gradient-to-t from-black to-transparent">
                                    <img class="w-[30%]" src="{{ asset('img/logo-colored.png') }}" alt="Logo">
                                </div>
                            </div>
        
                            <div class="w-full p-4">
                                <div class="w-fit py-2 px-4 flex items-center justify-center bg-[#F55E1D]">
                                    <p class="text-lg font-normal text-white">EDUCATION</p>
                                </div>
        
                                <p class="text-4xl font-normal text-[#03498D]">Ayala Reading Am<span class="font-bold">BASA</span>dors Storytelling Webinar</p>
        
                                <p class="text-2xl font-normal">Zoom Webinar Online, National Capital Region</p>
        
                                <p class="text-lg font-normal my-16 text-justify">
                                    &emsp; &emsp;Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                </p>
        
                                <div class="w-full flex flex-col items-start justify-start text-xl font-normal gap-2 my-16">
                                    <p class="font-semibold">DATE: Aug-27-2024 | 2:00 PM - 6:00 PM</p>
                                    <p><span class="font-semibold">SHIFTS:</span> Listen attentively and engage actively in the session</p>
                                    <p><span class="font-semibold">BATCH 1:</span> 2:00 PM - 4:00 PM</p>
                                    <p><span class="font-semibold">BATCH 2:</span> 2:00 PM - 4:00 PM</p>
                                </div>
        
                                <div class="flex items-center justify-start gap-4 mt-8">
                                    <a href="">
                                        <div class="h-[53px] w-[229px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                            <p class="font-normal text-lg text-white">SIGN UP</p>
                                        </div>
                                    </a>
        
                                    <a href="">
                                        <div class="h-[53px] w-[229px] bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                                            <p class="font-normal text-lg text-white">FAVORITE</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            {{-- Modal Scripts --}}
            <script>
                // Function to open the modal
                function openModal() {
                    const modal = document.getElementById('featuredImageModal');
                    const modalContent = modal.querySelector('.modal-content');
        
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modal.classList.remove('opacity-0');
                        modalContent.classList.remove('scale-95', 'opacity-0');
                    }, 10); // small delay to trigger the transition
                }
        
                // Function to close the modal
                function closeModal(event) {
                    const modal = document.getElementById('featuredImageModal');
                    const modalContent = modal.querySelector('.modal-content');
        
                    // Check if the clicked element is the modal background or the close button
                    if (event.target === modal || event.target.closest('#closeModal')) {
                        modalContent.classList.add('scale-95', 'opacity-0');
                        modal.classList.add('opacity-0');
        
                        setTimeout(() => {
                            modal.classList.add('hidden');
                        }, 300); // delay for the transition to complete
                    }
                }
        
                // Example: Open modal on page load (or use your own trigger)
                // window.onload = openModal; // You might want to change this to a specific trigger
            </script>
        </div>


    </div>
@endsection
