<x-filament-widgets::widget>
    <div class="w-full px-8">
        {{--  --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-start gap-4 mb-4">
            <div class="w-fit">
                <p class="text-[28px] md:text-[32px] text-[#0433ff] font-bold whitespace-nowrap">Recent Opportunities</p>
            </div>

            <div
                class="w-full sm:max-w-[40%] lg:max-w-[30%] flex items-center justify-start gap-2 p-2 px-4 text-black text-xs font-normal rounded-[20px] bg-[#F5F5F5]">
                @foreach ($tags as $tag)
                    <div class="w-fit px-2 py-1" style="background:#DADADA; border-radius: 10px;">
                        <p>{{ \Illuminate\Support\Str::upper($tag->name) }} <span class="w- inline-flex items-center justify-center cursor-pointer hover:font-[700]">X</span></p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 text-black">
            @foreach ($opportunities as $opportunity)
                <div class="col-span-1 flex flex-col justify-between shadow-md h-full">
                    <div class="flex items-center justify-between w-full gap-4"
                        style="height: 350px;  background: url('{{ asset('img/ayala-foundation-bg.jpg') }}') no-repeat center center; background-size: cover;">
                        <div class="h-full w-full flex items-end justify-start p-4"
                            style="background: linear-gradient(to top, rgba(0, 0, 0, 1), rgba(0, 0, 0, 0));">
                            <img style="width:40%" src="{{ asset('img/logo-vapp.svg') }}" alt="">
                        </div>
                    </div>

                    <div class="w-full text-[14px] p-4">
                        <div class="w-full flex items-center justify-between gap-4 text-[14px] font-normal">
                            <div class="w-fit py-1 px-2 flex items-center justify-center bg-[#f55e1d]">
                                <p class="font-normal text-white">
                                    {{ \Illuminate\Support\Str::upper($opportunity->program->name) }}</p>
                            </div>

                            <div class="w-fit">
                                <p class="text-[#000000] font-semibold">
                                    {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }}</p>
                            </div>
                        </div>

                        <p class="text-2xl font-bold text-[#0433ff] mt-3 mb-1 leading-none capitalize">
                            {{ \Illuminate\Support\Str::limit($opportunity->title, 22) }}</p>

                        <p class="font-normal mb-3">Zoom Webinar Online, {{ $opportunity->location }}</p>
                        <p><span class="font-semibold">SHIFTS:</span> Listen attentively and engage actively in the session
                        </p>

                        <div class="flex items-center justify-start gap-4">
                            @foreach ($opportunity->slots as $index => $slot)
                                <p>
                                    <span class="font-semibold">BATCH {{ $index + 1 }}:</span>
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                </p>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-start gap-4 mt-4 max-w-[416px] text-sm md:text-base font-normal">
                            <button id="recent-opportunity-btn-{{ $opportunity->id }}" class="w-full">
                                <div
                                    class="h-[40px] w-full flex items-center justify-center p-2 hover:bg-[#1A67B1]" style="background:#0433ff; ">
                                    <p class="text-white">VIEW DETAILS</p>
                                </div>
                            </button>

                            <a href="" class="w-full">
                                <div
                                    class="h-[40px] w-full bg-[#f55e1d] flex items-center justify-center p-2 hover:bg-[#f7723a]">
                                    <p class="text-white">SIGN UP</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Recent Opportunity Modal --}}
        @foreach ($opportunities as $opportunity)
            <div class="w-full h-fit">
                <div id="featuredImageModal{{ $opportunity->id }}"
                    class="fixed inset-0 flex justify-center items-center z-50 hidden transition-opacity duration-300 bg-black bg-opacity-50">
                    <!-- Modal Content -->
                    <div
                        class="modal-content text-[#000000] bg-white shadow-lg max-w-[80%] w-full p-8 transform transition-all duration-300 scale-95 opacity-0">
                        <div class="w-full flex justify-end items-end p-4">
                            <button id="closeModal{{ $opportunity->id }}"
                                class="text-2xl font-semibold text-black hover:bg-gray-100 focus:outline-none">
                                @include('custom.icons.landing-page-icons', ['icon' => 'close-25'])
                            </button>
                        </div>

                        <div class="h-fit max-h-[80vh] overflow-y-auto mb-4">
                            <div class="flex flex-col  items-center justify-center">
                                <div class="flex items-center justify-between h-[580px] w-full gap-4 bg-cover bg-center"
                                    style="background-image: url('{{ asset('img/ayala-foundation-bg.jpg') }}');">
                                    <div
                                        class="h-full w-full flex items-end justify-start p-8 bg-gradient-to-t from-black to-transparent">
                                        <img class="w-[30%]" src="{{ asset('img/logo-vapp.svg') }}" alt="Logo">
                                    </div>
                                </div>

                                <div class="w-full p-4 flex flex-col gap-4">
                                    <div class="w-fit py-1 md:py-2 px-4 flex items-center justify-center bg-[#f55e1d]">
                                        <p class="text-lg font-normal text-white">{{ \Illuminate\Support\Str::upper($opportunity->program->name) }}</p>
                                    </div>

                                    <p class="text-2xl md:text-4xl font-bold text-[#0433ff] capitalize">{{ $opportunity->title }}</p>

                                    <p class="text-xl md:text-2xl font-normal">Zoom Webinar Online, {{ $opportunity->location }}
                                    </p>

                                    <div class="text-lg font-normal my-4 text-justify">
                                        {!! $opportunity->description !!}
                                    </div>

                                    <div class="w-full flex flex-col md:flex-row items-center justify-center gap-4">
                                        <div class="w-full">
                                            <div
                                                class="w-full flex flex-col items-start justify-start text-base md:text-xl font-normal gap-2 mb-16">
                                                <p class="font-semibold">DATE: {{ \Carbon\Carbon::parse($opportunity->start_date)->format('M-d-Y') }} | {{ \Carbon\Carbon::parse($opportunity->start_date)->format('g:i A') }} -
                                                    {{ \Carbon\Carbon::parse($opportunity->end_date)->format('g:i A') }}</p>
                                                <p><span class="font-semibold">SHIFTS:</span> Listen attentively and engage
                                                    actively in the session</p>

                                                @foreach ($opportunity->slots as $index => $slot)
                                                    <p>
                                                        <span class="font-[600]">BATCH {{ $index + 1 }}:</span>
                                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i A') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                                    </p>
                                                @endforeach
                                            </div>

                                            <div
                                                class="flex flex-col md:flex-row items-center justify-start gap-4 mt-8">
                                                <a href="">
                                                    <div
                                                        class="h-auto md:h-[53px] w-[229px] bg-[#f55e1d] flex items-center justify-center p-2 hover:bg-[#f7723a]">
                                                        <p class="font-normal text-base md:text-lg text-white">SIGN UP</p>
                                                    </div>
                                                </a>

                                                <a href="">
                                                    <div
                                                        class="h-auto md:h-[53px] w-[229px] bg-[#0433ff] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                                                        <p class="font-normal text-base md:text-lg text-white">FAVORITE</p>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="w-fit">
                                            <img class="w-full max-w-[283px]" src="{{ asset('img/qr.png') }}"
                                                alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Scripts --}}
                <script>
                    // Add event listener to open modal for each opportunity
                    document.getElementById('recent-opportunity-btn-{{ $opportunity->id }}').addEventListener('click', function(
                        event) {
                        const modal = document.getElementById('featuredImageModal{{ $opportunity->id }}');
                        const modalContent = modal.querySelector('.modal-content');

                        modal.classList.remove('hidden');
                        setTimeout(() => {
                            modal.classList.remove('opacity-0');
                            modalContent.classList.remove('scale-95', 'opacity-0');
                        }, 10); // small delay to trigger the transition
                    });

                    // Add event listener to close modal for each opportunity
                    document.getElementById('featuredImageModal{{ $opportunity->id }}').addEventListener('click', function(event) {
                        const modal = document.getElementById('featuredImageModal{{ $opportunity->id }}');
                        const modalContent = modal.querySelector('.modal-content');

                        // Check if the clicked element is the modal background or the close button
                        if (event.target === modal || event.target.closest('#closeModal{{ $opportunity->id }}')) {
                            modalContent.classList.add('scale-95', 'opacity-0');
                            modal.classList.add('opacity-0');

                            setTimeout(() => {
                                modal.classList.add('hidden');
                            }, 300); // delay for the transition to complete
                        }
                    });

                    // Close modal directly when clicking the close button
                    document.getElementById('closeModal{{ $opportunity->id }}').addEventListener('click', function(event) {
                        const modal = document.getElementById('featuredImageModal{{ $opportunity->id }}');
                        const modalContent = modal.querySelector('.modal-content');

                        modalContent.classList.add('scale-95', 'opacity-0');
                        modal.classList.add('opacity-0');

                        setTimeout(() => {
                            modal.classList.add('hidden');
                        }, 300); // delay for the transition to complete
                    });
                </script>
            </div>
        @endforeach
    </div>
</x-filament-widgets::widget>
