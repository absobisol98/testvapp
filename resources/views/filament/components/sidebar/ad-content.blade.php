<div class="w-full flex flex-col items-center justify-between gap-8 mt-8">
    {{-- If user is a Volunteer this is the sidebar links --}}
    @if (true)
        <div class="adsSection w-full">
            @livewire(\App\Filament\Widgets\AdsWidget::class,['type' => 'side'])
        </div>
    @endif

    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const adsSection = document.querySelector('.adsSection');
            const sidebarHeaderButtons = document.querySelectorAll('.fi-sidebar-header button');

            if (sidebarHeaderButtons.length > 0) {
                setTimeout(() => {
                    if (getComputedStyle(sidebarHeaderButtons[0]).display !== 'none') {
                        if (adsSection) {
                            adsSection.style.display = "none";
                            adsSection.style.pointerEvents = "none";
                        }
                    }
                }, 50);

                sidebarHeaderButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        if (getComputedStyle(sidebarHeaderButtons[0]).display === 'none') {
                            if (adsSection) {
                                adsSection.style.display = "none"; // Hide text
                                adsSection.style.pointerEvents = "none";
                            }
                        } else {
                            if (adsSection) {
                                adsSection.style.display = "block"; // Show text
                                adsSection.style.pointerEvents = "auto";
                            }
                        }
                    });
                });
            }
        });
    </script> --}}

    {{-- <div class="w-full h-[350px] flex items-center justify-center">
        <img class="w-full h-full object-cover" src="{{ asset('img/ayala-foundation-bg-2.jpg') }}" alt="">
    </div>

    {{-- If user is a AFI Admin or Partners this is the sidebar links
    @if (true)
        <div class="w-full h-[600px] flex items-center justify-center">
            <img class="w-full h-full object-cover" src="{{ asset('img/hero-banner-bg_2.jpg') }}" alt="">
        </div>
    @endif --}}
</div>
