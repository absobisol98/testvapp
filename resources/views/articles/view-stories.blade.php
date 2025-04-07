@extends('custom.layouts.app')

@section('content')
    <div id="StoriesPage" class="w-full flex flex-col items-center justify-center">
        <div class="w-full grid grid-cols-1 lg:grid-cols-2 mt-[128px]">
            <!-- Our Program Section -->
            <div class="col-span-1 flex flex-col items-center justify-between gap-8 font-medium bg-[#03498D]">
            </div>

            <!-- Become a Volunteer Section -->
            <div class="col-span-1 min-h-[500px] flex flex-col items-center justify-between gap-8 font-medium text-white relative bg-cover bg-center bg-no-repeat"
                style="background-image: url('{{ asset('img/ayala-foundation-bg-2.jpg') }}')">
            </div>
        </div>

        <div class="w-[98%] m-[-30vh] pb-[80px] mb-8 xl:w-[90%] lg:w-[85%] md:w-[90%] sm:w-[95%] z-10">
            <div class="w-full flex flex-col items-start justify-center gap-8 mb-12 text-white">
                <p class="text-4xl md:text-6xl font-bold">Stories</p>
                <p class="text-lg md:text-xl font-normal leading-none">Ayala Corporate Citizenship and Volunteer Program</p>
            </div>

            {{-- Add search form here --}}
            <div class="w-full bg-white rounded-xl shadow-md px-8 py-4 mb-8">
                <form action="{{ route('stories.view') }}" method="GET" class="flex gap-4">
                    <div class="flex-1">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search stories by title..."
                            value="{{ request('search') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#FF781E] focus:border-transparent outline-none"
                        >
                    </div>
                    <button type="submit" class="px-6 py-2 bg-[#FF781E] text-white rounded-lg hover:bg-[#FF9141] transition duration-300">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('stories.view') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-300">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-[#FFFFFF] rounded-xl shadow-md px-8 py-8">
                @if($articles->isEmpty())
                    <div class="w-full text-center py-8">
                        <p class="text-gray-500 text-lg">
                            @if(request('search'))
                                No stories found for "{{ request('search') }}"
                            @else
                                No stories available
                            @endif
                        </p>
                    </div>
                @else
                    {{-- Articles Grid --}}
                    <div class="w-full grid grid-cols-1 gap-8">
                        @foreach($articles as $index => $article)
                            @if($index === 0)

                                {{-- Featured Article --}}
                                <div class="w-full mb-8">
                                    <div class="w-full flex flex-col md:flex-row items-center justify-between gap-4">
                                        <div class="flex items-start justify-start h-[300px] lg:h-[500px] w-full rounded-2xl overflow-hidden"
                                            style="background: url('{{ $article->getMedia('images')->first()?->getUrl() ?? asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: contain;">
                                            <div class="h-full w-full flex items-end justify-start p-4"
                                                style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                                            </div>
                                        </div>

                                        <div class="w-full flex flex-col items-start justify-center h-full px-0 md:px-6 gap-4">
                                            <div class="w-full flex flex-col items-start justify-start gap-2">
                                                <div class="min-h-[80px] flex items-center">
                                                    <p class="text-xl md:text-2xl lg:text-3xl font-bold text-[#03498D]">{{ $article->title }}</p>
                                                </div>
                                                <p class="text-sm md:text-base font-normal text-black">Written By: {{ $article->blog_author ?? 'Unknown' }} | Date: {{ $article->created_at->format('F d, Y') }}</p>
                                                <div class="min-h-[100px]">
                                                    <p class="text-sm md:text-base font-normal text-[#7A7A7A]">
                                                        {{ Str::limit(strip_tags($article->content), 150) }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="h-[40px] w-full lg:max-w-[300px] bg-[#FF781E] rounded-xl hover:bg-[#FF9141] transition duration-300 ease-in-out">
                                                <a href="{{ url('/article'). '/' . $article->slug }}"
                                                   class="h-full w-full flex items-center justify-center p-2">
                                                    <p class="font-medium text-sm md:text-base text-white">READ MORE</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        {{-- Grid Articles --}}
                        <div class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($articles as $index => $article)
                                @if($index !== 0)
                                    <div class="w-full flex flex-col items-start justify-start gap-3 min-h-[500px]">
                                        <div class="flex items-start justify-start h-[300px] w-full rounded-xl overflow-hidden"
                                            style="background: url('{{ $article?->getMedia('images')?->first()?->getUrl() ?? asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                                            <div class="h-full w-full flex items-end justify-start p-4"
                                                style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                                            </div>
                                        </div>

                                        <div class="w-full flex flex-col items-start justify-start gap-2 flex-grow">
                                            <div class="min-h-[60px] flex items-center">
                                                <p class="text-lg md:text-xl font-bold text-[#03498D]">{{ $article->title }}</p>
                                            </div>
                                            <p class="text-xs md:text-sm font-normal text-black">Written By: {{ $article->blog_author ?? 'Unknown' }} | Date: {{ $article->created_at->format('F d, Y') }}</p>
                                            <div class="min-h-[80px]">
                                                <p class="text-xs md:text-sm font-normal text-[#7A7A7A]">
                                                    {{ Str::limit(strip_tags($article->content), 150) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="h-[36px] w-full bg-[#FF781E] rounded-xl hover:bg-[#FF9141] transition duration-300 ease-in-out mt-auto">
                                            <a href="{{ url('/article'). '/' . $article->slug }}"
                                               class="h-full w-full flex items-center justify-center p-2">
                                                <p class="font-medium text-sm text-white">READ MORE</p>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $articles->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
