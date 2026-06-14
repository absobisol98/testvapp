<x-filament-widgets::widget>
    {{-- Partners On-Going Opportunities --}}
    <div class="w-full px-8">
        <div class="w-full flex items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#0433ff] font-[700]">On-going Opportunities</p>
            </div>

            <a href="/admin/events/create">
                <button class="w-fit">
                    <div
                        class="h-auto md:h-[48px] w-full bg-[#0433ff] flex items-center justify-center rounded-full rounded-none py-0 md:py-2 px-2 md:px-6 hover:bg-[#1A67B1]">
                        <p class="font-normal text-lg text-white hidden md:block">CREATE NEW OPPORTUNITY</p>

                        <!-- Tablet and Mobile text -->
                        <p class="text-white whitespace-nowrap block md:hidden text-2xl font-bold">+</p>
                    </div>
                </button>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1] my-4"></div>

        <div class="w-full overflow-y-scroll h-[500px] custom-scrollbar flex flex-col items-center justify-between gap-4">
            {{-- List --}}
            @foreach ($opportunities as $opportunity)

            @php
                $mediaItems = $opportunity->getMedia('event-banner-attachments')?->first()?->getUrl();
            @endphp

                <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                    <div
                        class="w-fit h-fit md:w-[250px] md:h-[180px] flex items-center justify-center overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ $mediaItems ?? url('img/ayala-foundation-bg.jpg') }}"
                            alt="">
                    </div>

                    <div class="w-full">
                        <div class="flex items-center justify-start gap-4 text-sm font-[400]">
                            <p>Created by: <span class="font-[700]">{{$opportunity->created_by_user?->firstname ?? ''}} {{$opportunity->created_by_user?->lastname ?? ''}}</span></p>

                            <div class="flex items-center justify-start gap-2">
                                    @include('custom.icons.admin-icons', ['icon' => 'for-review'])
                                    <p class="font-[700] text-[#ff7b00]">{{$opportunity->status?->name}}</p>
                            </div>
                        </div>
                        <p class="text-[28px] font-bold font-bold capitalize text-[#0433ff]">{{ $opportunity->title }}</p>

                        <p class="text-xl font-normal mb-3">{{ $opportunity->location }}
                        </p>

                        <div
                            class="w-full h-fit flex flex-col md:flex-row items-center justify-start text-[14px] font-[400] gap-4">
                            <div class="w-full md:w-fit flex flex-col items-start justify-between gap-0">
                                <div class="flex w-full gap-2" >
                                    <p class="font-semibold">DATE: {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }} |</p>
                                    <p class="font-semibold">
                                        {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}
                                    </p>

                                </div>

                                <p><span class="font-semibold">NUMBER OF SHIFTS:</span> {{$opportunity->slots->count()}}</p>
                                <div class="flex w-full gap-2">
                                    @foreach ($opportunity->slots as $key=> $slot)
                                    <p><span class="font-semibold">BATCH {{$key+1}}:</span>
                                        {{$slot->type->name}}
                                    </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @if (!$loop->last)
                    <div class="w-full h-[1px] border-t border-[#DFDFDF] my-4"></div>
                @endif
            @endforeach
        </div>

        <div class="w-full border-t border-[#E1E1E1] mt-4"></div>
    </div>
</x-filament-widgets::widget>
