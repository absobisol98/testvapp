@extends('custom.layouts.app')

@section('content')
    <div id="bpiHomePage" class="w-full flex flex-col items-center justify-center">
        {{-- Desktop: Hero Banner Section --}}
        <section class="hidden lg:block h-fit w-full bg-[#03498D] mt-[128px]">
            <div class="h-[90vh] w-full p-8 flex flex-col justify-center items-center gap-4 text-white"
                style="background: url('{{ asset('img/bpi-homepage-bg.png') }}') no-repeat center center; background-size: cover;">
                <img class="w-[280px]" src="{{ asset('img/bpi-logo.png') }}" alt="bpi-logo">
                <p class="font-[700] text-[50px] text-center">Spreading Love <br> Become Volunteer</p>
                <p class="font-medium text-center text-lg max-w-[900px]">In a world that can sometimes feel disconnected, we
                    envision a community bound together by compassion and generosity. Through your donations, we strive to
                    create a ripple effect of love that reaches those in need, touching lives and building bridges of hope.
                </p>

                <a href="">
                    <div
                        class="h-[56px] w-[184px] my-8 rounded-[10px] bg-[#D43F3F] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                        <p class="font-medium text-base text-white">SEE OPPORTUNITIES</p>
                    </div>
                </a>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-12">
                    <div class="col-span-1 flex flex-col items-center justify-center">
                        <p class="font-[700] text-[40px] text-center">500k+</p>
                        <p class="font-medium text-center text-sm text-[#FDFDFD]">Number of Supporters</p>
                    </div>

                    <div class="col-span-1 flex flex-col items-center justify-center">
                        <p class="font-[700] text-[40px] text-center">150k</p>
                        <p class="font-medium text-center text-sm text-[#FDFDFD]">All of Volunteers</p>
                    </div>

                    <div class="col-span-1 flex flex-col items-center justify-center">
                        <p class="font-[700] text-[40px] text-center">800k+</p>
                        <p class="font-medium text-center text-sm text-[#FDFDFD]">Total That We Helped</p>
                    </div>
                </div>
            </div>
        </section>
        {{-- Tablet & mobile: Hero Banner Section --}}
        <section
            class="h-fit w-full flex lg:hidden flex-col items-center justify-center p-[5%] text-white relative mt-[87px] gap-4 py-8"
            style="background: url('{{ asset('img/bpi-homepage-bg.png') }}') no-repeat center center; background-size: cover;">
            <img class="w-[60%] max-w-[220px]" src="{{ asset('img/bpi-logo.png') }}" alt="bpi-logo">
            <p class="font-[700] text-[40px] text-center">Spreading Love <br> Become Volunteer</p>
            <p class="font-medium text-center text-lg max-w-[700px]">In a world that can sometimes feel disconnected, we
                envision a community bound together by compassion and generosity. Through your donations, we strive to
                create a ripple effect of love that reaches those in need, touching lives and building bridges of hope.</p>

            <a href="">
                <div
                    class="h-[56px] w-[184px] my-8 rounded-[10px] bg-[#D43F3F] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                    <p class="font-medium text-base text-white">SEE OPPORTUNITIES</p>
                </div>
            </a>

            <div class="grid grid-cols-2 gap-4 md:gap-12">
                <div class="col-span-1 flex flex-col items-center justify-center">
                    <p class="font-[700] text-[40px] text-center">500k+</p>
                    <p class="font-medium text-center text-sm text-[#FDFDFD]">Number of Supporters</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center">
                    <p class="font-[700] text-[40px] text-center">150k</p>
                    <p class="font-medium text-center text-sm text-[#FDFDFD]">All of Volunteers</p>
                </div>

                <div class="col-span-2 flex flex-col items-center justify-center">
                    <p class="font-[700] text-[40px] text-center">800k+</p>
                    <p class="font-medium text-center text-sm text-[#FDFDFD]">Total That We Helped</p>
                </div>
            </div>
        </section>


        {{-- Opportunity Section --}}
        <div class="w-full flex flex-col items-center justify-between bg-[#FFFFFFE5] p-4 mb-8 gap-8">
            <div class="grid grid-cols-2 lg:grid-cols-3 w-full gap-4">
                <div class="col-span-2 xl:col-span-1 lg:col-span-3 min-h-[300px] flex items-center justify-between gap-4"
                    style="background: url('{{ asset('img/bpi-bg-1.jpg') }}') no-repeat center center; background-size: cover;">
                </div>

                <div class="col-span-2 md:col-span-1 flex items-center justify-between h-full min-h-[300px] gap-4"
                    style="background: url('{{ asset('img/bpi-bg-2.png') }}') no-repeat center center; background-size: cover;">
                    <div class="h-full w-full flex items-end justify-start p-4"
                        style="background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));">
                        <img class="w-[20%]" src="{{ asset('img/bpi-logo.png') }}" alt="">
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1 flex flex-col items-start justify-between gap-2">
                    <p class="text-[18px] font-[400]">FEATURED OPPORTUNITY</p>
                    <p class="text-[40px] font-[700] text-[#D43F3F] mt-3 leading-none">Lorem ipsum sit dolorem ipsum sit
                        dolor met.</p>

                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4 max-w-[600px]"></div>

                    <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, National Capital Region</p>
                    <div class="w-full flex flex-row items-start justify-start text-[14px] font-[400] gap-4">
                        <div class="w-fit flex flex-col items-start justify-between gap-2">
                            <p class="font-[600]">DATE: Aug-27-2024</p>
                            <p class="font-[600]">2:00 PM - 6:00 PM</p>
                        </div>
                        <div class="w-fit flex flex-col items-start justify-between gap-2">
                            <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the session
                            </p>
                            <div class="flex flex-col items-center justify-start">
                                <p><span class="font-[600]">BATCH 1:</span> 2:00 PM - 4:00 PM</p>
                                <p><span class="font-[600]">BATCH 2:</span> 2:00 PM - 4:00 PM</p>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex items-center justify-start gap-4 mt-8 max-w-[416px]">
                        <button onclick="openModal()" class="w-full">
                            <div
                                class="h-[48px] w-full bg-[#272727] flex items-center justify-center p-2 hover:bg-[#595959]">
                                <p class="font-[400] text-[18px] text-white">VIEW DETAILS</p>
                            </div>
                        </button>

                        <a href="" class="w-full">
                            <div
                                class="h-[48px] w-full bg-[#D43F3F] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                                <p class="font-[400] text-[18px] text-white">JOIN</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8">
                <div class="col-span-3 md:col-span-1 flex flex-col items-center justify-start gap-8">
                    <p class="text-[40px] font-[400] text-black text-center">About <span
                            class="font-[600] text-[#D43F3F]">BPI Foundation</span></p>

                    <div class="flex flex-col items-center justify-center gap-3">
                        <p class="text-lg font-[400] text-[#4B4B4B] text-center">In a world that can sometimes feel
                            disconnected, we envision a community bound together by compassion and generosity. Through your
                            donations, we strive to create a ripple effect of love that reaches those in need, touching
                            lives and building bridges of hope.</p>
                        <p class="text-lg font-[400] text-[#4B4B4B] text-center">In a world that can sometimes feel
                            disconnected, we envision a community bound together by compassion and generosity. Through your
                            donations, we strive to create a ripple effect of love that reaches those in need, touching
                            lives and building bridges of hope.</p>
                    </div>

                    <div class="flex flex-col items-center justify-center gap-3">
                        <p class="text-lg font-[400] text-[#D43F3F] text-center">www.bpi.com.ph</p>
                        <p class="text-lg font-[400] text-[#D43F3F] text-center">volunteers@bpi.com.ph</p>
                    </div>

                    <div class="flex items-center justify-center gap-4">
                        @include('custom.icons.landing-page-icons', ['icon' => 'instagram'])
                        @include('custom.icons.landing-page-icons', ['icon' => 'facebook'])
                        @include('custom.icons.landing-page-icons', ['icon' => 'linkedin'])
                    </div>
                </div>

                {{-- OPPORTUNITIES --}}
                <div class="col-span-3 md:col-span-2">
                    <div class="flex items-center justify-between gap-4 font-[400]">
                        <div class="w-fit">
                            <p class="text-[32px] md:text-[40px]">OPPORTUNITIES</p>
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
                                    stroke: #E97C7C;
                                    /* Change this to your desired hover color */
                                }

                                /* Stroke color on click (active state) */
                                #icon.active svg path,
                                #icon-calendar.active svg path {
                                    stroke: #CE3434;
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
                    <div id="opportunityList"
                        class="w-full flex flex-col items-center justify-between gap-8 p-4 duration-300 h-full max-h-[1000px] md:max-h-[600px] overflow-y-auto">
                        {{-- List 1 --}}
                        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                            <div
                                class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
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
                                        <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively
                                            in the session</p>
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
                                        class="h-[48px] w-[200px] bg-[#CE3434] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                                        <p class="font-[400] text-[18px] text-white">JOIN</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>

                        {{-- List 2 --}}
                        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                            <div
                                class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
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
                                        <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively
                                            in the session</p>
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
                                        class="h-[48px] w-[200px] bg-[#CE3434] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                                        <p class="font-[400] text-[18px] text-white">JOIN</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>

                        {{-- List 3 --}}
                        <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                            <div
                                class="w-fit h-fit md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
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
                                        <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively
                                            in the session</p>
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
                                        class="h-[48px] w-[200px] bg-[#CE3434] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                                        <p class="font-[400] text-[18px] text-white">JOIN</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>

                    {{-- OPPORTUNITIES Calendar --}}
                    <div id="opportunityCalendar"
                        class="w-full flex flex-col items-center justify-between gap-8 p-4 duration-300 hidden">
                        {{-- This is a sample calendar --}}
                        <div id="calendar"
                            class="w-full grid grid-cols-7 gap-0 border border-gray-300 rounded-md overflow-hidden">
                            <div class="text-center font-bold border-b border-gray-300">Sun</div>
                            <div class="text-center font-bold border-b border-gray-300">Mon</div>
                            <div class="text-center font-bold border-b border-gray-300">Tue</div>
                            <div class="text-center font-bold border-b border-gray-300">Wed</div>
                            <div class="text-center font-bold border-b border-gray-300">Thu</div>
                            <div class="text-center font-bold border-b border-gray-300">Fri</div>
                            <div class="text-center font-bold border-b border-gray-300">Sat</div>
                            <!-- Placeholder for empty days, adjust as necessary for the month -->
                            <div class="h-20 flex items-center justify-center border border-gray-300"></div>
                            <!-- Empty for padding -->
                            <div class="h-20 flex items-center justify-center border border-gray-300"></div>
                            <!-- Empty for padding -->
                            <div class="h-20 flex items-center justify-center border border-gray-300"></div>
                            <!-- Empty for padding -->
                            <div class="h-20 flex items-center justify-center border border-gray-300"></div>
                            <!-- Empty for padding -->
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                1</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                2</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                3</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                4</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                5</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                6</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                7</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                8</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                9</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                10</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                11</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                12</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                13</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                14</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                15</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                16</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                17</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                18</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                19</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                20</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                21</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                22</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                23</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                24</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                25</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                26</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                27</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                28</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                29</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                30</div>
                            <div
                                class="h-20 flex items-center justify-center border border-gray-300 hover:bg-orange-200 transition duration-300">
                                31</div>
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
        </div>


        {{--  --}}
        <div class="relative w-full">
            <div class="absolute w-full z-10 flex items-center justify-start">
                <div class="h-[33px] w-full md:w-[20%] bg-[#F51D1D]"></div>
            </div>

            <div class="h-fit md:h-[600px] w-full flex flex-col items-center justify-center text-white mt-4 bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('img/bpi-bg-3.jpg') }}')">
                <div class="h-full w-full px-20 py-12 flex flex-col items-start justify-center bg-[#2E2E2E96]">
                    <p class="w-full text-[40px] text-center md:text-start">Mission Statement</p>
                    <p class="w-full max-w-[600px] text-[50px] text-center md:text-start">Feed the Hungry! Walang
                        Pilipinong Nagugutom!</p>
                </div>
            </div>

            <div class="absolute w-full z-10 mt-[-17px] flex items-center justify-end">
                <div class="h-[33px] w-full md:w-[80%] bg-[#F51D1D]"></div>
            </div>
        </div>


        {{-- Recent Event Gallery Section --}}
        <div class="w-full grid grid-cols-1 lg:grid-cols-2">
            <!-- Program Section with Swiper -->
            <div
                class="col-span-1 h-full min-h-[740px] flex flex-col items-center justify-between gap-8 font-medium text-white bg-[#F55E1D] relative">
                <div class="program-swiper-container h-full w-full overflow-hidden z-0">
                    <div class="swiper-wrapper w-full">
                        <!-- Slide 1 -->
                        <div class="swiper-slide w-full h-full" style="background: url('{{ asset('img/bpi-bg-4.jpg') }}') no-repeat center center; background-size: cover;"></div>

                        <!-- Slide 2 -->
                        <div class="swiper-slide w-full h-full" style="background: url('{{ asset('img/bpi-bg-3.jpg') }}') no-repeat center center; background-size: cover;"></div>
                    </div>
                </div>

                <div class="w-full h-full p-4 flex items-center justify-between gap-4 absolute z-10">
                    <div
                        class="program-button-36-prev w-[36px] h-[36px] flex items-center justify-center rounded-full shadow-lg bg-white hover:bg-gray-100">
                        @include('custom.icons.landing-page-icons', ['icon' => 'arrow-left'])
                    </div>
                    <div
                        class="program-button-36-next w-[36px] h-[36px] flex items-center justify-center rounded-full shadow-lg bg-white hover:bg-gray-100">
                        @include('custom.icons.landing-page-icons', ['icon' => 'arrow-right'])
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
            <div class="col-span-1 py-24 px-20 flex flex-col items-center justify-center gap-8">
                <div class="w-full flex flex-col items-start gap-4 z-10 text-[#5B5B5B] font-[400]">
                    <p class="text-[36px]">Recent Event Gallery</p>
                    <p class="text-[48px]">JUST BRING YOUR HEARTS.</p>
                    <p class="text-[24px] w-full max-w-[510px] text-[#494949]">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard</p>
                </div>
                <div class="w-full z-10">
                    <a href="{{ route('volunteer.form.view') }}">
                        <div class="h-12 w-[260px] flex items-center justify-center rounded-[10px] border border-[#D43F3F] hover:bg-[#fff6f6]">
                            <p class="font-[800] text-sm text-[#D43F3F]">SIGN UP NOW!</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>


        {{-- Featured Opportunity Modal --}}
        <div class="w-full h-fit">
            <div id="featuredImageModal"
                class="fixed inset-0 flex justify-center items-center z-50 hidden transition-opacity duration-300 bg-black bg-opacity-50"
                onclick="closeModal(event)">
                <!-- Modal Content -->
                <div
                    class="modal-content bg-white shadow-lg max-w-[80%] w-full p-8 transform transition-all duration-300 scale-95 opacity-0">
                    <div class="w-full flex justify-end items-end p-4">
                        <button id="closeModal"
                            class="text-2xl font-semibold text-black hover:bg-gray-100 focus:outline-none">
                            @include('custom.icons.landing-page-icons', ['icon' => 'close-25'])
                        </button>
                    </div>

                    <div class="h-fit max-h-[80vh] overflow-y-auto mb-4">
                        <div class="flex flex-col  items-center justify-center">
                            <div class="flex items-center justify-between h-[580px] w-full gap-4 bg-cover bg-center"
                                style="background-image: url('{{ asset('img/ayala-foundation-bg.jpg') }}');">
                                <div
                                    class="h-full w-full flex items-end justify-start p-8 bg-gradient-to-t from-black to-transparent">
                                    <img class="w-[30%]" src="{{ asset('img/logo-colored.png') }}" alt="Logo">
                                </div>
                            </div>

                            <div class="w-full p-4">
                                <div class="w-fit py-2 px-4 flex items-center justify-center bg-[#F55E1D]">
                                    <p class="text-lg font-normal text-white">EDUCATION</p>
                                </div>

                                <p class="text-4xl font-normal text-[#03498D]">Ayala Reading Am<span
                                        class="font-bold">BASA</span>dors Storytelling Webinar</p>

                                <p class="text-2xl font-normal">Zoom Webinar Online, National Capital Region</p>

                                <p class="text-lg font-normal my-16 text-justify">
                                    &emsp; &emsp;Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                    unknown printer took a galley of type and scrambled it to make a type specimen book. It
                                    has survived not only five centuries, but also the leap into electronic typesetting,
                                    remaining essentially unchanged. It was popularised in the 1960s with the release of
                                    Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                                    publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                                </p>

                                <div
                                    class="w-full flex flex-col items-start justify-start text-xl font-normal gap-2 my-16">
                                    <p class="font-semibold">DATE: Aug-27-2024 | 2:00 PM - 6:00 PM</p>
                                    <p><span class="font-semibold">SHIFTS:</span> Listen attentively and engage actively in
                                        the session</p>
                                    <p><span class="font-semibold">BATCH 1:</span> 2:00 PM - 4:00 PM</p>
                                    <p><span class="font-semibold">BATCH 2:</span> 2:00 PM - 4:00 PM</p>
                                </div>

                                <div class="flex flex-col md:flex-row items-center justify-start gap-4 mt-8">
                                    <a href="">
                                        <div
                                            class="h-[53px] w-[229px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                            <p class="font-normal text-lg text-white">SIGN UP</p>
                                        </div>
                                    </a>

                                    <a href="">
                                        <div
                                            class="h-[53px] w-[229px] bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
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
