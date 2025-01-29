@extends('custom.layouts.app')

@section('content')
    <style>
        .clip-path-custom {
            clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 100%)
        }
    </style>

    <div id="mainLandingPage" class="w-full flex flex-col items-center justify-center">

        {{-- Desktop: Hero Banner Section --}}
        <section class="hidden lg:block h-[100vh] w-full bg-[#03498D]">
            <div class="grid grid-cols-5">
                <div class="flex justify-center items-center col-span-2 pl-5 pr-0 lg:pl-[10%] pr-10 md:pl-14 pr-5">
                    <div class="container whitespace-pre-line text-white">
                        <p class="font-[700] text-[70px] leading-none">Your involvement is <br> important to us!</p>
                        <p class="font-[400] text-[28px]">Ayala Corporate Citizenship and Volunteer Program</p>
                    </div>
                </div>

                <div class="h-[100vh] col-span-3 clip-path-custom" style="background: url('{{ asset('img/ayala.png') }}') no-repeat center center; background-size: cover;">
                </div>
            </div>
        </section>
        {{-- Tablet & mobile: Hero Banner Section --}}
        <section class="block lg:hidden h-[100vh] w-full flex flex-col items-center justify-center p-[5%] text-white relative" style="background: url('{{ asset('img/ayala.png') }}') no-repeat center center; background-size: cover;">
            <div class="absolute inset-0 bg-[#03498D] opacity-50"></div>
            <div class="flex flex-col items-center justify-center relative text-center z-10">
                <p class="font-[700] text-[70px] leading-none">Your involvement is important to us!</p>
                <p class="font-[400] text-[28px]">Ayala Corporate Citizenship and Volunteer Program</p>
            </div>
        </section>

        <div class="m-[-20vh] mb-8 xl:w-[80%] lg:w-[85%] md:w-[90%] sm:w-[95%] w-[98%] sm:w-[95%] z-10 bg-[#F55E1D] bg-cover lg:bg-right">
            <div class="flex flex-col items-center justify-between min-h-[756px] px-[5%] pt-[10%] pb-[5%] gap-12" style="background: url('{{ asset('img/reg.png') }}');">
                <div class="flex flex-col items-center justify-center text-white gap-8">
                    <h2 class="text-5xl text-center font-semibold">Welcome to Ayala Foundation</h2>
                    <p class="text-2xl text-center">Ayala Corporate Citizenship and Volunteer Program</p>
                    <p class="text-3xl text-center font-semibold whitespace-pre-line">Thank you for registering
                        a confirmation email was sent to
                        your email.
                    </p>
                </div>

                {{-- <div class="h-[10vh]"></div> --}}

                <div class="flex flex-col items-start justify-center text-white gap-4">
                    <div class="flex flex-col items-start justify-center">
                        <img class="w-[20%]" src="{{ asset('img/logo-white.png') }}" alt="">
                        <p class="text-base text-center font-normal">We will not share your information without your permission</p>
                    </div>

                    <div class="flex flex-col items-start justify-center font-normal ">
                        <p>By signing up you agree to our
                            <a href="/terms" class="text-[#03498D] underline hover:text-blue-500">Terms and Conditions</a>.
                        </p>
                        <p>Learn how we use your data in our
                            <a href="/privacy" class="text-[#03498D] underline hover:text-blue-500">Privacy Policy</a>.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

