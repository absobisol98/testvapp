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
<div class="max-w-screen-xl mx-auto relative flex flex-col gap-8 " style="padding-top:10%; padding-bottom:5%; padding-right:5%; padding-left:5%">
    <div class="w-full flex items-center justify-end">
        <button onclick="history.back()" class="rounded-lg py-2 px-4 flex items-center justify-center bg-[#F55E1D] hover:bg-[#FF9141]">
            <p class="text-base md:text-lg font-normal text-white">Home</p>
        </button>
    </div>

    <!-- Hero Section -->
    <div class="bg-cover h-auto text-center overflow-hidden"
        style="height: 550px; background-image: url('https://api.time.com/wp-content/uploads/2020/07/never-trumpers-2020-election-01.jpg?quality=85&amp;w=1201&amp;h=676&amp;crop=1'); background-position: center center;">
    </div>

    <!-- Article Content -->
    <div class="bg-white flex flex-col gap-4 leading-normal">
        <div class="flex flex-col gap-0">
            <h1 class="text-[#03498D] font-bold text-3xl mb-2 capitalize">{{ $article->title }}</h1>
            <div class="flex flex-row gap-4">
                <p class="text-gray-600 text-xs">Written By:
                    <a href="#"
                        class="text-gray-800 font-medium hover:text-gray-900 transition duration-500 ease-in-out">
                        {{ $article->author->name ?? 'Unknown' }}
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


    <div class="w-full ">
        <div class="h-[20vh]"></div>
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

                        @foreach ($articles as $item)

                            <div class="swiper-slide">
                                <div class="w-full min-h-[200px] flex flex-row items-start gap-4">
                                    <div class="w-fit min-w-[90px] p-4 bg-white shadow-md flex flex-col items-center">
                                        <p>{{ \Carbon\Carbon::parse($item->date)->format('M') }}</p>
                                        <p>{{ \Carbon\Carbon::parse($item->date)->format('d') }}</p>
                                    </div>
                                    <div class="w-full text-[#03498D] flex flex-col gap-4">
                                        <p class="text-xl font-semibold leading-none">{{ $item->title }}</p>
                                        <p class="text-[14px] line-clamp-1">{{ $item->description }}</p>
                                        <a href="#">
                                            <div class="h-10 w-[200px] bg-[#F55E1D] flex items-center justify-center hover:bg-[#FF8252]">
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
