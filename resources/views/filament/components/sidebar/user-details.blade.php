{{-- Tailwind --}}
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}

{{-- <a class="w-full" href="{{ route("filament.admin.resources.volunteers.index") }}">
    <div class="w-full flex flex-col items-center justify-center text-center gap-2">
        <div class="!w-[120px] !h-[120px] flex items-center justify-center overflow-hidden rounded-full relative">
            <img class="h-full w-full object-cover" src="https://cms.imgworlds.com/assets/a5366382-0c26-4726-9873-45d69d24f819.jpg?key=home-gallery" alt="User Profile Image">
        </div>

        <div>
            <p class="!text-[20px] !font-[500]">{{ auth()->user()->name }}</p>
            <p class="!text-[14px] !font-[300]">Member Since: {{ auth()->user()->created_at->format('F j, Y') }}</p>
        </div>
    </div>
</a> --}}

<style>
    /* Profile Container */
    .profileContainer {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 10px;
        /* Space between profile image and text */
    }

    /* Profile Image Wrapper */
    .profileImageWrapper {
        width: 120px;
        height: 120px;
        overflow: hidden;
        border-radius: 50%;
        /* Ensures the image stays circular */
        display: flex;
        align-items: center;
        justify-content: center;
        transition: width 0.3s, height 0.3s;
        /* Smooth size transitions */
    }

    /* Profile Image */
    .profileImage {
        width: 100%;
        /* Fill the wrapper */
        height: 100%;
        object-fit: cover;
        /* Ensure the image scales proportionally */
        transition: width 0.3s, height 0.3s;
    }

    /* Profile Text */
    .profileText {
        display: block;
        transition: display 0.1s, transform 0.1s;
    }

    /* Profile Name */
    .profileName {
        font-size: 20px;
        font-weight: 500;
        margin: 0;
        transition: font-size 0.1s;
    }

    /* Member Since */
    .memberSince {
        font-size: 14px;
        font-weight: 300;
        margin: 0;
        color: #666666;
        transition: font-size 0.1s;
    }
</style>

<a id="profileLink" href="{{ route('filament.admin.resources.volunteers.index') }}">
    <div class="profileContainer">
        <div class="profileImageWrapper">
            <img class="profileImage"
                src="https://cms.imgworlds.com/assets/a5366382-0c26-4726-9873-45d69d24f819.jpg?key=home-gallery"
                alt="User Profile Image">
        </div>
        <div class="profileText">
            <p class="profileName">{{ auth()->user()->name }}</p>
            <p class="memberSince">Member Since: {{ auth()->user()->created_at->format('F j, Y') }}</p>
        </div>
    </div>
</a>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const profileText = document.querySelector('.profileText');
        const profileImageWrapper = document.querySelector('.profileImageWrapper');
        const sidebarHeaderButtons = document.querySelectorAll('.fi-sidebar-header button');

        setTimeout(() => {
            if (getComputedStyle(sidebarHeaderButtons[0]).display !== 'none') {
                profileImageWrapper.style.width = "24px";
                profileImageWrapper.style.height = "24px";

                if (profileText) {
                    profileText.style.display = "none"; // Hide text
                    profileText.style.pointerEvents = "none";
                }
            }
        }, 50);

        if (sidebarHeaderButtons.length > 0) {
            // Add event listeners to each sidebar button
            sidebarHeaderButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    // Check if the first button is hidden (collapsed state)
                    if (getComputedStyle(sidebarHeaderButtons[0]).display === 'none') {
                        profileImageWrapper.style.width = "24px";
                        profileImageWrapper.style.height = "24px";

                        if (profileText) {
                            profileText.style.display = "none"; // Hide text
                            profileText.style.pointerEvents = "none";
                        }
                    } else {
                        profileImageWrapper.style.width = "120px";
                        profileImageWrapper.style.height = "120px";

                        if (profileText) {
                            profileText.style.display = "block"; // Show text
                            profileText.style.pointerEvents = "auto";
                        }
                    }
                });
            });
        }
    });
</script>
