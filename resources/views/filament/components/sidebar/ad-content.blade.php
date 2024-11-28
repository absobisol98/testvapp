<div class="adsSection">
    @livewire(\App\Livewire\AdsSectionWidget::class)
</div>

<script>
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
</script>
