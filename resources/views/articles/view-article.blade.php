@extends('custom.layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>

<style>
    .swiper-slide {
        flex-shrink: 0;
        width: 33.33%;
    }

    .stories-button-24-next, .stories-button-24-prev {
        z-index: 10;
        cursor: pointer;
    }
</style>

@php
    $article_banner = $article->media->first();
@endphp

<div class="w-full flex flex-col items-center justify-center">
    <div class="w-full bg-transparent md:bg-[#03498D] h-[100px] md:h-[400px]"></div>

    <div class="w-full md:max-w-screen-xl mx-auto relative flex flex-col gap-8 py-[10%] px-[5%]">
        <!-- Hero Section -->
        @isset($article_banner)
            <div class="bg-cover h-auto text-center overflow-hidden z-10 bg-white rounded-xl mt-0 md:-mt-[350px]">
                <img src="{{ asset('storage/' . $article_banner->id . '/' . $article_banner->file_name) }}" alt="">
            </div>
        @else
            <div class="flex items-center rounded-xl justify-between z-10 h-[500px] w-full gap-4 mt-0 md:-mt-[350px]" style="background-image: url({{ asset('/img/ayala-foundation-bg-3.jpg') }}); no-repeat center center; background-size: cover; background-position: top; background-repeat: no-repeat;">
                <div class="rounded-xl h-full w-full flex items-end justify-start p-4 bg-gradient-to-t from-[#03498D] to-transparent">
                </div>
            </div>
        @endisset

        <!-- Article Content -->
        <div class="bg-white flex flex-col gap-4 leading-normal">
            <div class="flex flex-col items-center gap-0">
                <h1 class="text-[#03498D] font-bold text-3xl mb-2 capitalize">{{ $article->title }}</h1>
                <div class="flex flex-row gap-4">
                    <p class="text-gray-600 text-xs">Written By:
                        <a href="#"
                            class="text-gray-800 font-medium hover:text-gray-900 transition duration-500 ease-in-out">
                       
                            {{ $article->blog_author ?? 'Unknown' }}
                        </a>
                    </p>
                    <p class="text-gray-600 text-xs">Date:
                        <a href="#"
                            class="text-gray-800 font-medium hover:text-gray-900 transition duration-500 ease-in-out">
                            {{ $article->created_at->format('F d, Y') ?? 'N/A' }}
                        </a>
                    </p>
                </div>
            </div>
            <div>
                <p class="text-base leading-8 my-5 text-justify">
                    {!! nl2br($article->content ?? 'Content not available') !!}
                </p>
            </div>
        </div>

        <div class="w-full">
            <div class="h-[10vh] md:h-[20vh]"></div>
        </div>

        <!-- Stories Section -->
        <div class="w-full flex-row">
            <div class="col-span-1 max-h-[500px]">
                <p class="text-[#03498D] font-bold text-2xl mb-5 capitalize">Recent Articles</p>

                <!-- Carousel Navigation Buttons -->
                <div class="flex justify-end">
                    <div class="stories-button-24-prev">
                        <div class="w-[24px] h-[24px] flex items-center justify-center bg-gray-200 hover:bg-[#f3f2f2]">
                            @include('custom.icons.landing-page-icons', ['icon' => 'navigate-prev-36'])
                        </div>
                    </div>
                    <div class="stories-button-24-next">
                        <div class="w-[24px] h-[24px] flex items-center justify-center bg-gray-200 hover:bg-[#f3f2f2]">
                            @include('custom.icons.landing-page-icons', ['icon' => 'navigate-next-36'])
                        </div>
                    </div>
                </div>

                <div class="w-full p-2 bg-white">
                    <div class="stories-swiper-container w-full overflow-hidden">
                        <div class="swiper-wrapper w-full">
                            @foreach ($articles->where('id', '!=', $article->id) as $item)
                                @php
                                    $banner = $item->media->first(); // Ensure banner is dynamically set per item
                                @endphp
                                <div class="swiper-slide h-[450px] flex">
                                    <div class="w-full min-h-[450px] flex flex-col justify-between gap-4">
                                        <div class="w-full flex flex-col items-start justify-between gap-4 flex-grow">
                                            @if($banner && $banner->file_name)
                                                <div class="w-full h-[200px] bg-cover bg-center bg-no-repeat rounded-xl"
                                                    style="background-image: url('{{ asset('storage/' . $banner->id . '/' . $banner->file_name) }}');">
                                                    <div class="rounded-xl h-full w-full flex items-end justify-start p-4 bg-gradient-to-t from-[#03498D] to-transparent"></div>
                                                </div>
                                            @else
                                                <div class="w-full h-[200px] bg-cover bg-center bg-no-repeat rounded-xl"
                                                    style="background-image: url('{{ asset('/img/ayala-foundation-bg-3.jpg') }}');">
                                                    <div class="rounded-xl h-full w-full flex items-end justify-start p-4 bg-gradient-to-t from-[#03498D] to-transparent">
                                                        <img class="w-full" src="{{ asset('img/logo-white.png') }}" alt="">
                                                    </div>
                                                </div>
                                            @endif

                                            <p class="text-xl text-[#03498D] font-bold leading-none">{{ $item->title }}</p>
                                            <div class="flex flex-row gap-1">
                                                <p class="text-gray-600 text-xs">Written By:
                                                    <a href="#" class="text-gray-800 font-medium hover:text-gray-900 transition duration-500 ease-in-out">
                                                        {{ $item->author->name ?? 'Unknown' }} |
                                                    </a>
                                                </p>
                                                <p class="text-gray-600 text-xs">Date:
                                                    <a href="#" class="text-gray-800 font-medium hover:text-gray-900 transition duration-500 ease-in-out">
                                                        {{ $item->created_at->format('M j, Y') ?? 'N/A' }}
                                                    </a>
                                                </p>
                                            </div>
                                            <p class="text-[14px] flex-grow">{{ $item->content_overview }}</p>
                                        </div>

                                        <div class="w-full flex justify-center items-center">
                                            <a href="{{ url('/article'). '/' . $item->slug }}">
                                                <div class="h-10 w-[200px] rounded-full bg-[#F55E1D] flex items-center justify-center hover:bg-[#FF8252]">
                                                    <p class="font-medium text-base text-white">READ MORE</p>
                                                </div>
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Swiper Script -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
            <script>
                const storiesSwiper = new Swiper('.stories-swiper-container', {
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.stories-button-24-next',
                        prevEl: '.stories-button-24-prev',
                    },
                    breakpoints: {
                        640: { slidesPerView: 1 },
                        768: { slidesPerView: 2 },
                        1024: { slidesPerView: 3 },
                    },
                });
            </script>
        </div>
    </div>
</div>
@endsection
