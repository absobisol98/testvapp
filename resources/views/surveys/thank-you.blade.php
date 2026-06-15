@extends('custom.layouts.app')

@section('content')
<div class="min-h-screen md:h-auto flex items-center justify-center bg-gray-50 pb-[5%] pt-[10%]">
    <div class="max-w-7xl flex flex-col items-center justify-evenly w-full bg-white rounded-lg shadow-lg p-8 text-center" style="min-height: 50vh">
        <div class="mb-8">
            <img src="{{ asset('img/logo-vapp.svg') }}" alt="Ayala Logo" class="mx-auto h-16">
        </div>

        <div class="space-y-4">
            <h1 class="text-3xl font-bold text-[#f55e1d]">Thank You!</h1>
            <p class="text-gray-600">Your response has been recorded. We appreciate your feedback.</p>
        </div>
        <div class="mt-8">
            <a href="{{ route('filament.admin.pages.dashboard') }}"
               class="inline-flex items-center justify-center px-6 py-3 bg-[#0433ff] text-white font-medium rounded-lg hover:bg-opacity-90 transition duration-300">
                <span>Return to Dashboard</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection



