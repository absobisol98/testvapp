<x-filament-widgets::widget>
    {{-- Opportunities --}}
    <div class="w-full px-8">
        <div class="w-full flex items-center justify-between gap-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#03498D] font-[700]">Opportunities</p>
            </div>

            <button class="w-fit">
                <div
                    class="h-[48px] w-full bg-[#005096] flex items-center justify-center py-2 px-6 hover:bg-[#1A67B1]">
                    <p class="font-[400] text-[18px] text-white">CREATE NEW OPPORTUNITY</p>
                </div>
            </button>
        </div>

        <div class="w-full border-t border-[#E1E1E1] my-4"></div>

        <div class="w-full flex flex-col items-center justify-between gap-4">
            {{-- List --}}
            @foreach ($opportunities as $opportunity)
                <div class="w-full flex flex-col md:flex-row items-center justify-between gap-8">
                    <div
                        class="w-fit h-fit md:w-[250px] md:h-[180px] flex items-center justify-center overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg.jpg') }}"
                            alt="">
                    </div>

                    <div class="w-full">
                        <div class="flex items-center justify-start gap-4 text-sm font-[400]">
                            <p>Created by: <span class="font-[700]">AFI</span></p>

                            <div class="flex items-center justify-start gap-2">
                                @if(rand(0, 1))
                                    @include('custom.icons.admin-icons', ['icon' => 'published'])
                                    <p class="font-[700] text-black">PUBLISHED</p>
                                @else
                                    @include('custom.icons.admin-icons', ['icon' => 'for-review'])
                                    <p class="font-[700] text-[#F55E1D]">FOR REVIEW</p>
                                @endif
                            </div>
                        </div>
                        <p class="text-[28px] font-[400] text-[#03498D]">{{ $opportunity->title }}</p>

                        <p class="text-[18px] font-[400] mb-3">Zoom Webinar Online, {{ $opportunity->location }}
                        </p>

                        <div
                            class="w-full h-fit flex flex-row items-center justify-start text-[14px] font-[400] gap-4">
                            <div class="w-fit flex flex-col items-start justify-between gap-0">
                                <p class="font-[600]">DATE:
                                    {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}</p>
                                <p class="font-[600]">
                                    {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}</p>

                                <p><span class="font-[600]">SHIFTS:</span> Listen attentively and ...</p>
                                @foreach ($opportunity->slots as $index => $slot)
                                    <p>
                                        <span class="font-[600]">BATCH {{ $index + 1 }}:</span>
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                    </p>
                                @endforeach
                            </div>

                            <div class="h-full min-h-[118px] max-h-[118px] border-l border-[#B6B6B6] mx-4"></div>

                            <div class="w-fit grid grid-cols-2 gap-8">
                                <div
                                    class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-4">
                                    <p class="text-[40px] font-[700] text-[#F55E1D]">140</p>
                                    <p class="text-[14px] font-[700] text-[#03498D]">VOLUNTEERS</p>
                                </div>

                                <div
                                    class="col-span-1 flex flex-col items-center justify-center gap-2 bg-[#F9F9F9] shadow-md p-4">
                                    <p class="text-[40px] font-[700] text-[#F55E1D]">5</p>
                                    <p class="text-[14px] font-[700] text-[#03498D]">HOURS</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-fit flex items-center justify-between gap-8 p-4">
                        <x-filament::icon-button icon="heroicon-s-pencil-square" wire:click="openNewUserModal"
                            size="xl" label="Edit" />
                        <x-filament::icon-button icon="heroicon-s-trash" wire:click="openDeleteConfirmation"
                            size="xl" label="Delete" />

                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" wire:model="status" />
                            <div
                                class="w-11 h-6 bg-[#E6E0E9] rounded-full ring-2 ring-[#79747E] peer-checked:bg-[#65558F] peer-checked:ring-[#65558F] peer-checked:after:bg-white peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-[#79747E] after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all">
                            </div>
                        </label>
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
