<x-filament-panels::page>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
</head>
<style>
    /* For Volunteer Dashboard Container(Start) */
    .fi-main {
        margin: 0px !important;
        padding: 0px 0px !important;
        margin-top: 0px !important;
        margin-bottom: 0px !important;
        padding-top: 20px !important;
        padding-bottom: 0px !important;
        border-radius: 0px !important;
        max-width: 100% !important;
    }

    .fi-page section {
        padding: 0px 0px 32px 0px !important;
    }
    .fi-breadcrumbs {
        display:none;
    }
    .fi-header {
        padding-left: 20px !important;
    }

    /* For Volunteer Dashboard Container(End) */
</style>

<div class="flex flex-col w-full px-4 mx-auto md:px-6 lg:px-8 max-w-full space-y-6">
    <div class="w-full flex items-center justify-end mb-8">
        <button onclick="history.back()" class="py-2 px-4 flex items-center justify-center bg-[#F55E1D] hover:bg-[#FF9141]">
            <p class="text-lg font-normal text-white">Back To Event List</p>
        </button>
    </div>

    {{-- @php
        dd($record->slots);
    @endphp --}}
    <div class="w-full bg-white rounded-xl shadow">
        <div class="px-4 py-4 space-y-4">
              {{-- Hero Banner --}}
        <div class="w-full flex items-center justify-center overflow-hidden relative" style="height: 50vh;">
            <a href="{{ asset('img/ayala-foundation-bg.jpg') }}" class="glightbox flex items-center justify-between h-[580px] w-full gap-4 bg-cover bg-center" data-gallery="gallery1">
                <div class="flex items-center justify-center overflow-hidden w-full">
                    <img class="object-cover w-full h-[580px]" src="{{ asset('img/ayala-foundation-bg.jpg') }}">
                </div>
            </a>
            {{-- @php
            $banner = $record->media->first();
            $banner_source = ( url('') . '/storage/event-banner-attachments/' . $banner->file_name );

             @endphp
            <img class="h-full w-full object-cover" src="{{ $banner_source }}"
                alt="User Profile Image"> --}}
        </div>

            <div class="flex flex-col justify-start items-start gap-2">
                <div class="py-2 px-4 flex items-center justify-center bg-[#005096] rounded-md">
                    <p class="text-md md:text-lg lg:text-base font-normal text-white capitalize">{{$record->program->name}}</p>
                </div>
                <h2 class="text-3xl md:text-3xl lg:text-3xl text-gray-900 font-extrabold capitalize">{{ $record->title }}</h2>
            </div>
            <div class="flex flex-col justify-start items-start gap-2">
                <p class="text-md md:text-lg lg:text-base font-normal capitalize">{!! strip_tags($record->description) !!}</p>
                <div class="py-2 px-4 flex items-center justify-center bg-[#FF781E] rounded-md">
                    <p class="text-md md:text-lg lg:text-base font-normal text-white capitalize">Sign In</p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full flex flex-col bg-white rounded-xl shadow" style="width:100%">
        <div class="px-4 py-4 space-y-4">
            <h2 class="text-black md:pl-10 lg:pl-0 text-lg text-start font-extrabold">
                Event Details
            </h2>
            {{-- @php
                dd($record->facilitators);
            @endphp --}}
            <div class="w-full flex flex-col md:flex-row space-y-4" style="width:100%">
                <div class="w-full md:w-3/5 flex flex-col space-y-2" style="width:100%">
                    <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                        <svg class="w-8 h-4 mr-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"> <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"></path></svg><strong>Location: &nbsp;</strong>
                        {{$record->location}}
                    </p>
                    <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                        <svg class="w-8 h-4 mr-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M19 3h-1V2a1 1 0 1 0-2 0v1H8V2a1 1 0 1 0-2 0v1H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3zm1 16a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V10h16v9zM4 8V6a1 1 0 0 1 1-1h1v1a1 1 0 1 0 2"></path></svg><strong>Schedule: &nbsp;</strong>{{ \Carbon\Carbon::parse($record->start_date)->format('M-d-Y') }}
                    </p>
                    <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                        <svg class="w-8 h-4 mr-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 10.59l3.29 3.3a1 1 0 0 1-1.42 1.42l-3.3-3.29a1 1 0 0 1-.29-.7V7a1 1 0 0 1 2 0v5.59z"></path></svg><strong>Recurrence Type: &nbsp;</strong>
                        {{$record->event_recurrence_type->name}}
                    </p>
                    <br>
                    <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                        <svg class="w-8 h-4 mr-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"> <path d="M112 48a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm40 304V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V256.9L59.4 304.5c-9.1 15.1-28.8 20-43.9 10.9s-20-28.8-10.9-43.9l58.3-97c17.4-28.9 48.6-46.6 82.3-46.6h29.7c33.7 0 64.9 17.7 82.3 46.6l58.3 97c9.1 15.1 4.2 34.8-10.9 43.9s-34.8 4.2-43.9-10.9L232 256.9V480c0 17.7-14.3 32-32 32s-32-14.3-32-32V352H152z"></path></svg><strong>Volunteer Slot: &nbsp; </strong>
                        @foreach ($record->slots as $slot)
                            {{ $slot->total_slots }}
                        @endforeach
                        {{-- {{$record->slots[0]->total_slots}} --}}
                    </p>
                    <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center">
                        <svg class="w-8 h-4 mr-4" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"> <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 10.59l3.29 3.3a1 1 0 0 1-1.42 1.42l-3.3-3.29a1 1 0 0 1-.29-.7V7a1 1 0 0 1 2 0v5.59z"></path></svg><strong>Shift: &nbsp;</strong>{{$record->slots[0]->shift_name}}
                    </p>
                    <br>
                    <p class="text-black md:pl-20 lg:pl-0 text-md inline-flex items-center font-bold">Volunteer Responsibility</p>
                    <p class="text-md md:text-lg lg:text-base pr-4 font-normal capitalize">&emsp; &emsp;
                        @foreach ($record->slots as $slots)
                            {{ $slots->responsibilities }}
                        @endforeach
                        {{-- {{$record->slots[0]->responsibilities}} --}}
                    </p>
                </div>

                <div class="w-full flex flex-col space-y-2" style="width:40%">
                    <h2 class="text-black md:pl-10 lg:pl-0 text-lg text-start font-extrabold">
                        Contact Information
                    </h2>
                    <div class="w-full flex flex-col">
                        <p class="text-md md:text-lg lg:text-base text-start font-bold">Point-of-Contact:</p>{{$record->point_of_contact->firstname}} {{$record->point_of_contact->lastname}}
                        <p class="text-md md:text-lg lg:text-base text-start font-bold">Facilitator/s:</p>
                        @foreach ($record->facilitators as $facilitator)
                                {{$facilitator->name}}
                            @endforeach
                        <br>
                        <p class="text-md md:text-lg lg:text-base text-start font-bold">File Attachment:</p>
                        <div class="flex items-center justify-start">
                            <a href="#" class="text-black text-base font-normal underline">
                                Download PDF
                            </a>
                        </div>
                        <br>
                        <p class="text-md md:text-lg lg:text-base text-start font-bold">Tags:</p>
                        <div
                        class="w-full max-w-[70%] sm:max-w-[40%] lg:max-w-[100%] flex items-center justify-start gap-2 p-2 px-4 text-black text-xs font-normal rounded-[20px] bg-[#F5F5F5]">
                        {{-- <div class="w-fit px-2 py-1" style="background:#DADADA; border-radius: 10px;">
                            <p>Health <span class="w- inline-flex items-center justify-center cursor-pointer hover:font-[700]">X</span></p>
                        </div> --}}
                        @foreach ($record->tags as $tag)
                            <div class="w-fit px-2 py-1" style="background:#DADADA; border-radius: 10px;">
                                <p>{{ \Illuminate\Support\Str::upper($tag->name) }} <span class="w- inline-flex items-center justify-center cursor-pointer hover:font-[700]">X</span></p>
                            </div>
                        @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

<script>
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
    });
</script>

</x-filament-panels::page>
