@extends('custom.layouts.app')

@section('title')
    {{$business_unit->nickname}}
@endsection
@section('content')
    <div id="bpiHomePage" class="w-full flex flex-col items-center justify-center">
        {{-- Desktop: Hero Banner Section --}}
        <section class="hidden lg:block h-fit w-full bg-[#03498D] mt-[128px]">
            <div class="h-[90vh] w-full p-8 flex flex-col justify-center items-center gap-4 text-white"
                style="background: url('{{$eventCover }}') no-repeat center center; background-size: cover;">
                <img class="w-[280px]" src="{{ $logo }}" alt="bpi-logo">
                <p class="font-[700] text-[50px] text-center">{{$business_unit->header_tagline}}</p>
                <p class="font-medium text-center text-lg max-w-[900px]">
                    {{$business_unit->header_description}}
                </p>

                <a href="#opportunity-list">
                    <div
                        class="h-[56px] w-[184px] my-8 rounded-[10px] bg-[#F55E1D] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                        <p class="font-medium text-base text-white">SEE OPPORTUNITIES</p>
                    </div>
                </a>

                <div class="grid grid-cols-2 md:grid-cols-2 gap-4 md:gap-12">
                    <div class="col-span-1 flex flex-col items-center justify-center">
                        <p class="font-[700] text-[40px] text-center">{{$opportunities->count()}}</p>
                        <p class="font-medium text-center text-sm text-[#FDFDFD]">Number of Opportunities</p>
                    </div>

                    <div class="col-span-1 flex flex-col items-center justify-center">
                        <p class="font-[700] text-[40px] text-center">{{$total_volunteers}}</p>
                        <p class="font-medium text-center text-sm text-[#FDFDFD]">All of Volunteers</p>
                    </div>
                </div>
            </div>
        </section>
        {{-- Tablet & mobile: Hero Banner Section --}}
        <section
            class="h-fit w-full flex lg:hidden flex-col items-center justify-center p-[5%] text-white relative mt-[87px] gap-4 py-8"
            style="background: url('{{ $eventCover }}') no-repeat center center; background-size: cover;">
            <img class="w-[60%] max-w-[220px]" src="{{ $logo }}" alt="bpi-logo">
            <p class="font-[700] text-[40px] text-center">{{$business_unit->header_tagline}}</p>
            <p class="font-medium text-center text-lg max-w-[700px]">
                {{$business_unit->header_description}}
            </p>

            <a href="#opportunity-list">
                <div
                    class="h-[56px] w-[184px] my-8 rounded-[10px] bg-[#D43F3F] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                    <p class="font-medium text-base text-white">SEE OPPORTUNITIES</p>
                </div>
            </a>

            <div class="grid grid-cols-2 gap-4 md:gap-12 justify-center">
                <div class="col-span-1 flex flex-col items-center justify-center">
                    <p class="font-[700] text-[40px] text-center">{{$opportunities->count()}}</p>
                    <p class="font-medium text-center text-sm text-[#FDFDFD]">Number of Opportunities</p>
                </div>

                <div class="col-span-1 flex flex-col items-center justify-center">
                    <p class="font-[700] text-[40px] text-center">{{$total_volunteers}}</p>
                    <p class="font-medium text-center text-sm text-[#FDFDFD]">All of Volunteers</p>
                </div>
            </div>
        </section>


        {{-- Opportunity Section --}}

        <div class="w-full flex flex-col items-center justify-between bg-[#FFFFFFE5] py-4 mb-8 px-[16px] xl:px-[80px] gap-8"  id="opportunity-list">
            <div class="grid grid-cols-2 lg:grid-cols-3 w-full gap-4">
                @if (count($galleries) > 0)
                    <div class="w-full program-swiper-container2 col-span-2  h-full w-full overflow-hidden ">
                        <div class="swiper-wrapper">
                            @foreach ($galleries as $chunk_gallery)
                                <div class="swiper-slide">
                                    <div class="grid grid-cols-2 lg:grid-cols-2 gap-4">

                                        @foreach ( $chunk_gallery as $gallery )
                                            <div class="col-span-1 flex items-center justify-between h-full min-h-[300px] gap-4"
                                                style="background: url('{{ $gallery}}') no-repeat center center; background-size: contain;">
                                                <div class="h-full w-full flex items-end justify-start p-4"
                                                    style="background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));">
                                                    <img class="w-[20%]" src="{{ $logo }}" alt="">
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="w-full h-full p-4 col-span-2 flex items-center justify-between gap-4 absolute">
                                            <div
                                                class="program-button-36-prev2 w-[36px] h-[36px] flex items-center justify-center rounded-full shadow-lg bg-white hover:bg-gray-100">
                                                @include('custom.icons.landing-page-icons', ['icon' => 'arrow-left'])
                                            </div>
                                            <div
                                                class="program-button-36-next2 w-[36px] h-[36px] flex items-center justify-center rounded-full shadow-lg bg-white hover:bg-gray-100">
                                                @include('custom.icons.landing-page-icons', ['icon' => 'arrow-right'])
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="w-full h-[300px] bg-gray-100 flex items-center justify-center col-span-2">
                        <p class="text-base text-black">This Business Unit has no Event Gallery.</p>
                    </div>
                @endif



                <div class="col-span-2 md:col-span-1 flex flex-col items-start justify-start gap-2">
                    @if ($upcoming)
                        <p class="text-[18px] font-[400]">UPCOMING OPPORTUNITY</p>
                        <div class="flex flex-col items-start justify-between">
                            <p class="text-[40px] font-[700] text-[#D43F3F] mt-3 leading-none">{{$upcoming->title}}</p>

                            <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4 max-w-[600px]"></div>

                            <p class="text-[18px] font-[400] mb-3">{{$upcoming?->location}}</p>
                            <div class="w-full flex flex-row items-start justify-start text-[14px] font-[400] gap-4">
                                <div class="w-fit flex flex-col items-start justify-between gap-2">
                                    <p class="font-[600]">DATE: {{\Carbon\Carbon::parse($upcoming?->start_date)->isoFormat('MMMM DD, YYYY')}}</p>
                                    <p class="font-[600]">{{\Carbon\Carbon::parse($upcoming?->start_date)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::parse($upcoming?->end_date)->isoFormat('hh:mm A')}}</p>
                                </div>
                                <div class="w-fit flex flex-col items-start justify-between gap-2">
                                    <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage actively in the session</p>
                                        <div class="flex flex-col items-center justify-start">
                                            @foreach ($upcoming->slots as $key => $slot)
                                                <p><span class="font-[600]">BATCH {{$key+1}}: ({{$slot->shift_name}}) - </span> {{\Carbon\Carbon::parse($slot?->start_time)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::parse($slot?->end_time)->isoFormat('hh:mm A')}}</p>
                                            @endforeach

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
                                <a href="{{route('filament.admin.resources.events.view',['record' => $upcoming->id])}}" class="w-full">
                                    <div
                                        class="h-[48px] w-full bg-[#D43F3F] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                                        <p class="font-[400] text-[18px] text-white">JOIN</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                    @else
                        <div class="flex flex-col items-center justify-center w-full" style="height: 100%;">
                            <div class="bg-gray-100 w-full h-full flex items-center justify-center p-4">
                                <p class="text-base text-black">This Business Unit has no Upcoming Opportunity.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8">
                <div class="col-span-3 md:col-span-1 flex flex-col items-center justify-start gap-8">
                    <p class="text-[40px] font-[400] text-black text-center">About <span
                            class="font-[600] text-[#F55E1D]">{{$business_unit->nickname}}</span></p>

                    <div class="flex flex-col items-center justify-center gap-3">
                        {!! nl2br($business_unit->about ?? "Here's where your about us displayed") !!}
                    </div>

                    <div class="flex flex-col items-center justify-center gap-3">
                        @if ($website)
                            <a href="{{$website->link}}" target="_blank">
                                <p class="text-lg font-[400] text-[#D43F3F] text-center">{{$website->link}}</p>
                            </a>
                        @endif
                    </div>
                    <div class="flex items-center justify-center gap-4">
                        @foreach ($socials as $social)
                            @if ($social->social != 'website')
                                <a href="{{$social->link}}" target="_blank">
                                    @include('custom.icons.landing-page-icons', ['icon' => $social->social])
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                {{-- OPPORTUNITIES --}}
                <div class="col-span-3 md:col-span-2">
                    <div class="flex items-center justify-between gap-4 font-[400]">
                        <div class="w-fit">
                            <p class="text-2xl md:text-[32px] md:text-[40px]">OPPORTUNITIES</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 md:gap-8">
                            <a class="text-lg md:text-xl font-[400] hover:underline" href="">VIEW</a>

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
                                    stroke: #F55E1D;
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
                    <div
                        class="w-full flex flex-col items-center justify-between gap-8 p-4 duration-300 h-full max-h-[1000px] md:max-h-[600px] overflow-y-auto">
                        {{-- List --}}
                        @foreach ($opportunities as $index => $opportunity)
                            <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                                <div
                                    class="w-contain h-contain md:w-[200px] md:h-[140px] flex items-center justify-center overflow-hidden">
                                    <img class="w-full h-full object-contain"
                                        src="{{  $opportunity->getBanner() }}" alt="">
                                </div>

                                <div class="w-full">
                                    <p class="text-[28px] font-bold text-[#03498D] capitalize">{{ $opportunity->title }}</p>

                                    <p class="text-[18px] font-[400] mb-3 capitalize">
                                        {{ $opportunity->location }}</p>

                                    <div
                                        class="w-full flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                                            <p class="font-[600]">DATE:
                                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}</p>
                                            <p class="font-[600]">
                                                {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}</p>
                                        </div>
                                        <div class="w-fit flex flex-col items-start justify-between gap-1">
                                            <p><span class="font-[600]">SHIFTS:</span> Listen attentively and engage
                                                actively
                                                in the session</p>
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
                                    @if ( \Carbon\Carbon::parse($opportunity->created_at)->lt(now()))
                                        <a href="{{route('filament.admin.resources.events.view',['record' => $opportunity->id])}}">
                                            <div
                                                class="h-auto md:h-[48px] w-[200px] bg-[#CE3434] flex items-center justify-center p-2 hover:bg-[#E97C7C]">
                                                <p class="font-[400] text-base md:text-[18px] text-white">JOIN</p>
                                            </div>
                                        </a>
                                    @else

                                        <div
                                            class="opacity-50 h-auto md:h-[48px] w-[200px] bg-[#CE3434] flex items-center justify-center p-2">
                                            <p class="font-[400] text-base md:text-[18px] text-white ">Event Done</p>
                                        </div>

                                    @endif
                                </div>
                            </div>

                            @if (!$loop->last)
                                <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                            @endif
                        @endforeach
                    </div>

                    {{-- OPPORTUNITIES Calendar --}}
                    <div id="opportunityCalendar" class="w-full gap-8 p-4 duration-300">
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
            </div>
        </div>

        @if ($featuredEvents->isNotEmpty())
            <h2 class="text-2xl md:text-[36px] font-semibold m-3">Recent Event Gallery</h2>
            <div class="w-full program-swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($featuredEvents as $featured)
                        <div class="swiper-slide relative">
                            <div class="grid grid-cols-2 lg:grid-cols-2 ">
                                <div class="col-span-1 h-full min-h-[500px] gap-8 font-medium text-white bg-[#F55E1D]">
                                    <img src="{{  $featured->getBanner() }}" alt="" class="object-fit w-full h-full">
                                </div>
                                <div class="col-span-1 py-24 px-20 flex flex-col items-center justify-center gap-8 z-1">
                                    <div class="w-full flex flex-col items-start gap-4 z-10 text-[#5B5B5B] font-[400]">
                                        <p class="text-3xl md:text-5xl">{{$featured->title}}</p>
                                        <p class="text-xl md:text-2xl w-full max-w-[510px] text-[#494949]">
                                            {!! nl2br($featured->description ?? "Here's where your about us displayed") !!}
                                        </p>
                                    </div>
                                    <div class="w-full z-50">
                                        @if (auth()->check())
                                            <a href="{{ route('filament.admin.resources.events.view',['record' => $featured->id]) }}">
                                                <div class="h-12 w-[260px] flex items-center justify-center rounded-[10px] border border-[#D43F3F] hover:bg-[#fff6f6]">
                                                    <p class="font-[800] text-sm text-[#D43F3F]">View Event</p>
                                                </div>
                                            </a>
                                        @else
                                            <a href="{{ route('volunteer.form.view') }}">
                                                <div class="h-12 w-[260px] flex items-center justify-center rounded-[10px] border border-[#D43F3F] hover:bg-[#fff6f6]">
                                                    <p class="font-[800] text-sm text-[#D43F3F]">SIGN UP NOW!</p>
                                                </div>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="w-full h-full p-4 col-span-2 flex items-center justify-between gap-4 absolute">
                                    <div
                                        class="program-button-36-prev w-[36px] h-[36px] flex items-center justify-center rounded-full shadow-lg bg-white hover:bg-gray-100">
                                        @include('custom.icons.landing-page-icons', ['icon' => 'arrow-left'])
                                    </div>
                                    <div
                                        class="program-button-36-next w-[36px] h-[36px] flex items-center justify-center rounded-full shadow-lg bg-white hover:bg-gray-100">
                                        @include('custom.icons.landing-page-icons', ['icon' => 'arrow-right'])
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        </div>

        {{-- Featured Opportunity Modal --}}

        <div class="w-full h-fit">
            @if ($upcoming)
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
                                    style="background-image: url('{{  $upcoming_banner}}');">
                                    <div
                                        class="h-full w-full flex items-end justify-start p-8 bg-gradient-to-t from-black to-transparent">
                                        <img class="w-[30%]" src="{{ $logo }}" alt="Logo">
                                    </div>
                                </div>


                                <div class="w-full p-4">
                                    <div class="w-fit py-2 px-4 flex items-center justify-center bg-[#F55E1D]">
                                        <p class="text-lg font-normal text-white">{{$upcoming->event_type->name}}</p>
                                    </div>

                                    <p class="text-4xl font-normal text-[#03498D]">{{$upcoming->title}}</p>

                                    <p class="text-2xl font-normal">{{$upcoming?->location}}</p>

                                    <p class="text-lg font-normal my-16 text-justify">
                                        {!! nl2br($upcoming->description ?? 'No description created') !!}
                                    </p>

                                    <div
                                        class="w-full flex flex-col items-start justify-start text-xl font-normal gap-2 my-16">
                                        <p class="font-semibold">DATE: {{\Carbon\Carbon::parse($upcoming?->start_date)->isoFormat('MMMM DD, YYYY')}} | {{\Carbon\Carbon::parse($upcoming?->start_date)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::parse($upcoming?->end_date)->isoFormat('hh:mm A')}}</p>
                                        <p><span class="font-semibold">SHIFTS:</span> Listen attentively and engage actively in
                                            the session</p>

                                        @foreach ($upcoming->slots as $key => $slot)
                                            <p><span class="font-semibold">BATCH {{$key+1}}: ({{$slot->shift_name}}) - </span> {{\Carbon\Carbon::parse($slot?->start_time)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::parse($slot?->end_time)->isoFormat('hh:mm A')}}</p>
                                        @endforeach

                                    </div>

                                    <div class="flex flex-col md:flex-row items-center justify-start gap-4 mt-8">
                                        <a href="{{route('filament.admin.resources.events.view',['record' => $opportunity->id])}}">
                                            <div
                                                class="h-[53px] w-[229px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                                                <p class="font-normal text-lg text-white">SIGN UP</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            @endif


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
                // slider
                const programSwiper = new Swiper('.program-swiper-container', {
                        loop: true,
                        slidesPerView: 1,

                        navigation: {
                            nextEl: '.program-button-36-next',
                            prevEl: '.program-button-36-prev',
                        },
                    });

                const programSwiper2 = new Swiper('.program-swiper-container2', {
                        loop: true,
                        autoplay: {
                            delay: 3000,
                        },
                        slidesPerView: 1,
                        navigation: {
                            nextEl: '.program-button-36-next2',
                            prevEl: '.program-button-36-prev2',
                        },
                    });



                // Example: Open modal on page load (or use your own trigger)
                // window.onload = openModal; // You might want to change this to a specific trigger
            </script>
        </div>
    </div>
@endsection
