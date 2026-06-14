@extends('custom.layouts.app')


@section('content')
    <div id="StoriesPage" class="w-full flex flex-col items-center justify-center">

        <div class="w-full grid grid-cols-1 lg:grid-cols-2 mt-[128px]">
            <!-- Our Program Section -->
            <div
                class="col-span-1 flex flex-col items-center justify-between gap-8 font-medium bg-[#0433ff]">

            </div>

            <!-- Become a Volunteer Section -->
            <div class="col-span-1 min-h-[500px] flex flex-col items-center justify-between gap-8 font-medium text-white relative bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('img/ayala-foundation-bg-2.jpg') }}')"></div>
        </div>


        <div class="w-[98%] m-[-30vh] pb-[80px] mb-8 xl:w-[90%] lg:w-[85%] md:w-[90%] sm:w-[95%] z-10">
            <div class="w-full flex flex-col items-start justify-center gap-8 mb-12 text-white">
                <p class="text-4xl md:text-6xl font-bold">Our Partners</p>
                <p class="text-lg md:text-xl font-normal leading-none">Ayala Corporate Citizenship and Volunteer Program
                </p>
            </div>

            <div class="bg-[#FFFFFF] rounded-xl shadow-md px-8 py-8">
                {{-- Search Bar --}}
                <div class="w-full mb-12">
                    <p class="text-xl font-bold text-black mb-4">Search Business Partner Unit </p>
                    <form action="{{ route('ourpartners.view') }}" method="GET" class="w-full mx-auto">
                        <div class="flex">
                            <div class="relative w-full">
                                <input
                                    type="search"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="block p-2.5 w-full z-20 text-lg bg-[#ffffff] border-0 shadow-md rounded-lg placeholder-[#B6B6B6] focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Search by request, organisation name or category"
                                />

                                <button type="submit"
                                    class="absolute top-0 end-0 h-full p-2.5 text-sm font-medium text-white rounded-e-lg">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 20 20">
                                        <path stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Partners List --}}
                <div class="w-full flex flex-col items-center justify-between gap-8 max-h-[1000px] overflow-y-auto">
                    @if($partners->isEmpty())
                        <div class="w-full text-center py-8">
                            <p class="text-gray-500 text-lg">
                                @if(request('search'))
                                    No partners found for "{{ request('search') }}"
                                @else
                                    No partners available
                                @endif
                            </p>
                        </div>
                    @else
                        @foreach($partners as $partner)

                            <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4">
                                <div class="w-fit h-fit md:w-[300px] md:h-[180px] flex items-center justify-center overflow-hidden">
                                    <a href="{{ route('businessunit.homepage.view', $partner->slug) }}" class="w-full h-full flex items-center justify-center hover:opacity-80 transition duration-300">
                                        <img class="w-full h-full object-contain"
                                             src="{{ $partner->getMedia('bu_logo')->first()?->getUrl() ?? asset('img/ayala-foundation-bg.jpg') }}"
                                             alt="{{ $partner->name }}"
                                             title="Click to view {{ $partner->name }} page">
                                    </a>
                                </div>

                                <div class="w-full flex flex-col items-start justify-center gap-2">
                                    <p class="text-[28px] font-bold text-[#0433ff]">{{ $partner->name }}</p>
                                    <p class="font-normal text-black">{{ strtoupper($partner->category) }}</p>
                                    <p class="font-normal text-[#7A7A7A]">{{ $partner->description }}</p>
                                </div>

                                <a href="{{ route('businessunit.homepage.view', $partner->slug) }}" class="cursor-pointer">
                                    <div class="h-auto md:h-[48px] w-[200px] bg-[#ff7b00] flex items-center justify-center p-2 rounded-3xl hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                        <p class="font-medium text-base md:text-[18px] text-white">EXPLORE</p>
                                    </div>
                                </a>
                            </div>

                            @unless($loop->last)
                                <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                            @endunless
                        @endforeach

                        {{-- Pagination --}}
                        <div class="mt-8 w-full">
                            {{ $partners->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection
