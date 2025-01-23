@extends('custom.layouts.app')
<div class="max-w-screen-xl mx-auto p-5 sm:p-8 md:p-12 relative">
    <div class="bg-cover h-64 text-center overflow-hidden"
        style="height: 450px; background-image: url('https://api.time.com/wp-content/uploads/2020/07/never-trumpers-2020-election-01.jpg?quality=85&amp;w=1201&amp;h=676&amp;crop=1')">
    </div>
    <div class="max-w-2xl mx-auto">
        <div
            class="mt-3 bg-white rounded-b lg:rounded-b-none lg:rounded-r flex flex-col justify-between leading-normal">
@php
    dd($article);
@endphp
            <div class="">

                <h1 href="#" class="text-gray-900 font-bold text-3xl mb-2">{{ $article->title }}</h1>
                <p class="text-gray-700 text-xs mt-2">Written By:
                    <a href="#"
                        class="text-indigo-600 font-medium hover:text-gray-900 transition duration-500 ease-in-out">
                        {{ $article->author->name }}
                    </a>
                </p>

                <p class="text-base leading-8 my-5">
                 {!!  nl2br($article->content) !!}
                </p>


            </div>

        </div>
    </div>
</div>

