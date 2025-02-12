@extends('custom.layouts.app')


@section('content')
    <div id="StoriesPage" class="w-full flex flex-col items-center justify-center">

        <div class="w-full grid grid-cols-1 lg:grid-cols-2 mt-[128px]">
            <!-- Our Program Section -->
            <div class="col-span-1 flex flex-col items-center justify-between gap-8 font-medium bg-[#03498D]">

            </div>

            <!-- Become a Volunteer Section -->
            <div class="col-span-1 min-h-[500px] flex flex-col items-center justify-between gap-8 font-medium text-white relative bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('img/ayala-foundation-bg-2.jpg') }}')"></div>
        </div>


        <div class="w-[98%] m-[-30vh] pb-[80px] mb-8 xl:w-[90%] lg:w-[85%] md:w-[90%] sm:w-[95%] z-10">
            <div class="w-full flex flex-col items-start justify-center gap-8 mb-12 text-white">
                <p class="text-4xl md:text-6xl font-bold">Stories</p>
                <p class="text-lg md:text-xl font-normal leading-none">Ayala Corporate Citizenship and Volunteer Program
                </p>
            </div>

            <div class="bg-[#FFFFFF] rounded-xl shadow-md px-8 py-8">
                {{-- Search Bar --}}
                <div class="w-full mb-12">
                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center justify-between h-[300px] lg:h-[500px] w-full rounded-3xl overflow-hidden gap-4"
                            style="background: url('{{ asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                            <div class="h-full w-full flex items-end justify-start p-4"
                                style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                            </div>
                        </div>

                        <div class="w-full flex flex-col items-center justify-center px-0 md:justify-start md:px-8 gap-8">
                            <div class="w-full flex flex-col items-start justify-center gap-2">
                                <p class="text-[28px] md:text-[40px] font-bold text-[#03498D]">Article sample 001</p>
                                <p class="font-normal text-black">Written By: Karina Reyes | Date: February 10, 2025</p>
                                <p class="font-normal text-[#7A7A7A]">
                                    We believe in contributing to the nation’s development goals by adapting the evolving
                                    needs
                                    of stakeholders so we can remain relevant and responsive. Through our programs,
                                </p>
                            </div>

                            <a href="" class="cursor-pointer w-full">
                                <div
                                    class="h-auto md:h-[48px] w-full lg:max-w-[415px] bg-[#FF781E] flex items-center justify-center p-2 rounded-3xl hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                    <p class="font-medium text-base md:text-[18px] text-white">READ MORE</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Partners List --}}
                <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-h-[2000px] overflow-y-auto">

                    {{-- Partner 1 --}}
                    <div class="w-full flex flex-col items-center justify-between gap-4">
                        <div class="flex items-center justify-between h-[300px] w-full rounded-3xl overflow-hidden gap-4"
                            style="background: url('{{ asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                            <div class="h-full w-full flex items-end justify-start p-4"
                                style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                            </div>
                        </div>

                        <div class="w-full flex flex-col items-start justify-center gap-2">
                            <p class="text-[28px] font-bold text-[#03498D]">Article sample 001</p>
                            <p class="font-normal text-black">Written By: Karina Reyes | Date: February 10, 2025</p>
                            <p class="font-normal text-[#7A7A7A]">
                                We believe in contributing to the nation’s development goals by adapting the evolving needs
                                of stakeholders so we can remain relevant and responsive. Through our programs,
                            </p>
                        </div>

                        <a href="" class="cursor-pointer w-full">
                            <div
                                class="h-auto md:h-[48px] w-full bg-[#FF781E] flex items-center justify-center p-2 rounded-3xl hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                <p class="font-medium text-base md:text-[18px] text-white">READ MORE</p>
                            </div>
                        </a>
                    </div>


                    {{-- Partner 2 --}}
                    <div class="w-full flex flex-col items-center justify-between gap-4">
                        <div class="flex items-center justify-between h-[300px] w-full rounded-3xl overflow-hidden gap-4"
                            style="background: url('{{ asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                            <div class="h-full w-full flex items-end justify-start p-4"
                                style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                            </div>
                        </div>

                        <div class="w-full flex flex-col items-start justify-center gap-2">
                            <p class="text-[28px] font-bold text-[#03498D]">Article sample 001</p>
                            <p class="font-normal text-black">Written By: Karina Reyes | Date: February 10, 2025</p>
                            <p class="font-normal text-[#7A7A7A]">
                                We believe in contributing to the nation’s development goals by adapting the evolving needs
                                of stakeholders so we can remain relevant and responsive. Through our programs,
                            </p>
                        </div>

                        <a href="" class="cursor-pointer w-full">
                            <div
                                class="h-auto md:h-[48px] w-full bg-[#FF781E] flex items-center justify-center p-2 rounded-3xl hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                <p class="font-medium text-base md:text-[18px] text-white">READ MORE</p>
                            </div>
                        </a>
                    </div>



                </div>
            </div>
        </div>

    </div>
@endsection
