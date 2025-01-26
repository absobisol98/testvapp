<x-filament-panels::page>
    <style>
        /* For Profile Page Container(Start) */
        .fi-main {
            margin: 0px !important;
            padding: 0px 0px !important;
            margin-top: 0px !important;
            margin-bottom: 0px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            border-radius: 0px !important;
            max-width: 100% !important;
        }

        .fi-page section {
            padding: 0px 0px 32px 0px !important;
        }

        .fi-header {
            padding: 32px 32px 0 32px;
        }

        /* For Profile Page Container(End) */
    </style>



    <div class="w-full p-8 flex flex-col items-center justify-center">
        <div class="w-full flex items-center justify-end mb-8">
            <button class="py-2 px-4 flex items-center justify-center bg-[#F55E1D] hover:bg-[#FF9141]">
                <p class="text-lg font-normal text-white">PROGRAM</p>
            </button>
        </div>

        {{-- Hero Banner --}}
        <div class="w-full flex items-center justify-center overflow-hidden relative" style="height: 50vh;">
            <img class="h-full w-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                alt="User Profile Image">
        </div>

        <div class="w-full p-4 flex flex-col items-center justify-center gap-8">
            <div class="w-full">
                <button class="py-2 px-4 flex items-center justify-center bg-[#F55E1D] hover:bg-[#FF9141]">
                    <p class="text-lg font-normal text-white">PROGRAM</p>
                </button>
            </div>

            <div class="w-full">
                <p class="text-4xl font-bold mb-4 text-[#03498D]">{{ $record->title }}</p>

                <span class="text-lg font-normal text-justify">{!! $record->description !!}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <div class="col-span-1 flex flex-col items-center justify-center gap-8">
                    {{-- EVENT DETAILS --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-xl font-bold mb-4">EVENT DETAILS</p>
                        <p class="text-lg font-bold mb-4">@include('custom.icons.event-icons', ['icon' => 'location']) LOCATION:
                            {{ strtoupper($record->location) }}</p>
                        <p class="text-lg font-bold mb-4">@include('custom.icons.event-icons', ['icon' => 'schedule']) SCHEDULE:
                            {{ \Carbon\Carbon::parse($record->start_date)->format('M-d-Y') }} |
                            {{ \Carbon\Carbon::parse($record->start_date)->format('g:i A') }} -
                            {{ \Carbon\Carbon::parse($record->end_date)->format('g:i A') }}</p>
                        <p class="text-lg font-bold mb-4">@include('custom.icons.event-icons', ['icon' => 'clock']) RECURRENCE:
                            {{ strtoupper($record->event_recurrence_type->name) }}</p>
                        <p class="text-lg font-bold mb-4">@include('custom.icons.event-icons', ['icon' => 'person']) VOLUNTEER SLOT:
                            @if ($record->slots->count() === 1)
                                {{ $record->slots->first()->total_slots }}
                            @elseif ($record->slots->count() > 1)
                                {{ $record->slots->min('total_slots') }} -
                                {{ $record->slots->max('total_slots') }}
                            @else
                                No slots available
                            @endif
                        </p>
                        <p class="text-lg font-bold mb-4">@include('custom.icons.event-icons', ['icon' => 'clock']) SHIFT:</p>
                    </div>

                    {{-- VOLUNTEER RESPONSIBILITY --}}
                    <div class="w-full">
                        <p class="text-[#F55E1D] text-xl font-bold mb-4">VOLUNTEER RESPONSIBILITY</p>

                        <p class="text-lg font-normal text-justify">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                            unknown printer took a galley of type and scrambled it to make a type specimen book. It
                            has survived not only five centuries, but also the leap into electronic typesetting,
                            remaining essentially unchanged. It was popularised in the 1960s with the release of
                            Letraset sheets containing Lorem Ipsum passages, and more recently with desktop
                            publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                    </div>
                </div>

                {{-- CONTACT INFORMATION --}}
                <div class="col-span-1">
                    <p class="text-[#F55E1D] text-xl font-bold mb-4">CONTACT INFORMATION</p>
                    <p class="text-lg font-bold mb-4">POINT OF CONTACT: JUAN DELA CRUZ</p>
                    <p class="text-lg font-bold mb-4">FILE ATTACHMENTS: <a href=""
                            class="text-black hover:!text-[#F55E1D]">DOWNLOAD</a></p>
                    <p class="text-lg font-bold mb-4">TAGS</p>
                    <div
                        class="w-full max-w-[500px] min-h-[78px] flex flex-wrap items-start justify-start gap-2 p-4 text-black text-xs font-normal rounded-[20px] bg-[#F5F5F5]">
                        @foreach ($record->tags as $tag)
                            <div class="w-fit px-2 py-1" style="background:#DADADA; border-radius: 10px;">
                                <p>{{ \Illuminate\Support\Str::upper($tag->name) }} <span
                                        class="w- inline-flex items-center justify-center cursor-pointer hover:font-[700]">X</span>
                                </p>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page>
