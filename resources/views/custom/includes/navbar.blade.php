<style>
    #logoWhite {
        display: block;
    }

    #logoColored {
        display: none;
    }

    #navBar {
        padding: 80px 80px;
        background: transparent;
        position: absolute;
        transition: background-color 0.3s ease-in-out, position 0.3s ease-in-out, padding 0.3s ease-in-out;
    }

    #navBar.scrolled {
        padding: 30px 80px;
        background-color: white;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
    }

    #userDropdownBtn {
        color: white;
    }

    #avatarContainer {
        background: transparent;
    }

    @media screen and (max-width: 1279px) {
        #navBar {
            padding: 30px 16px;
        }

        #navBar.scrolled {
            padding: 30px 16px;
        }
    }
</style>

@php
    $isBusinessUnit = (true) ? true : false;
@endphp

{{-- If user is BPI User --}}
@if ($isBusinessUnit)
    <style>
        #navBar {
            padding: 30px 80px;
            background: white;
            position: absolute;
            transition: background-color 0.3s ease-in-out, position 0.3s ease-in-out, padding 0.3s ease-in-out;
        }

        #userDropdownBtn {
            color: black !important;
        }

        #avatarContainer {
            background: #005096 !important;
        }

        @media screen and (max-width: 1279px) {
            #navBar {
                padding: 30px 16px;
            }
        }
    </style>
@endif

<div id="navBar" class="w-full flex items-center justify-between z-50">
    <a href="{{ $isBusinessUnit ? route('businessunit.homepage.view') : route('main.homepage.view') }}" id="logoWhite" class="h-full max-w-[150px] md:max-w-[273px]">

        @if ($isBusinessUnit)
            <img class="w-full" src="{{ asset('img/logo-colored.png') }}" alt="">
        @else
            <img class="w-full" src="{{ asset('img/logo-white.png') }}" alt="">
        @endif
    </a>

    <a href="{{ $isBusinessUnit ? route('businessunit.homepage.view') : route('main.homepage.view') }}" id="logoColored" class="none h-full max-w-[150px] md:max-w-[273px]">
        <img class="w-full" src="{{ asset('img/logo-colored.png') }}" alt="">
    </a>

    <div class="h-full flex items-center justify-between gap-4">
        @guest
            <a href="{{ route('volunteer.form.view') }}">
                <div
                    class="h-[36px] md:h-[56px] w-[200px] md:w-[244px] rounded-[20px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                    <p class="font-medium text-base text-white">BECOME A VOLUNTEER</p>
                </div>
            </a>
        @endguest

        {{-- User Dropdown --}}
        @auth
            <div>
                <div class="relative inline-block text-left">
                    <div>
                        <button id="userDropdownBtn" type="button"
                            class="inline-flex w-full items-center justify-center gap-x-1.5 px-3 py-2 text-base font-medium hover:underline"
                            aria-expanded="false" aria-haspopup="true">
                            <div id="avatarContainer"
                                class="w-[36px] h-[36px] inline-flex items-center justify-center rounded-full overflow-hidden">
                                <div class="w-[34px] h-[34px] inline-flex items-center justify-center">
                                    @include('custom.icons.navbar-icons', [
                                        'icon' => 'avatar',
                                    ])
                                </div>
                            </div>
                            Welcome, {{ auth()->user()->name }}
                        </button>
                    </div>

                    <div id="userDropdownItems"
                        class="absolute right-0 z-10 mt-2 pt-3 pb-8 w-[290px] origin-top-right divide-y divide-gray-100 bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                        style="display: none;" role="menu" aria-orientation="vertical" aria-labelledby="menu-button"
                        tabindex="-1">
                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="{{ route('filament.admin.pages.dashboard') }}"
                                class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem" tabindex="-1"
                                id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'gear',
                                        ])
                                    </div>
                                    <p>Dashboard</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="#" class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'bell',
                                        ])
                                    </div>
                                    <p>Notifications</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="#" class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'heart',
                                        ])
                                    </div>
                                    <p>Opportunities</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="#" class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'message',
                                        ])
                                    </div>
                                    <p>My Messages</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="#" class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'award',
                                        ])
                                    </div>
                                    <p>Certificates</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="#" class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'user',
                                        ])
                                    </div>
                                    <p>Account Settings</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-full px-3 text-base font-medium" role="none">
                            <a href="#" class="w-full block px-3 py-3 hover:bg-gray-100" role="menuitem"
                                tabindex="-1" id="menu-item-0">
                                <div class="w-full flex items-center justify-start gap-2">
                                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                                        @include('custom.icons.navbar-icons', [
                                            'icon' => 'log-out',
                                        ])
                                    </div>
                                    <p>Logout</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <script>
                        const userDropdownBtn = document.getElementById('userDropdownBtn');
                        const userDropdownItems = document.getElementById('userDropdownItems');

                        // Function to toggle the dropdown display
                        function toggleDropdown() {
                            const isExpanded = userDropdownBtn.getAttribute('aria-expanded') === 'true';

                            if (isExpanded) {
                                userDropdownItems.style.display = 'none';
                                userDropdownBtn.setAttribute('aria-expanded', 'false');
                            } else {
                                userDropdownItems.style.display = 'block';
                                userDropdownBtn.setAttribute('aria-expanded', 'true');
                            }
                        }

                        // Toggle dropdown when button is clicked
                        userDropdownBtn.addEventListener('click', function(event) {
                            event.stopPropagation(); // Prevent click from bubbling to the document
                            toggleDropdown();
                        });

                        // Hide dropdown when clicking outside of it
                        document.addEventListener('click', function(event) {
                            if (!userDropdownBtn.contains(event.target) && !userDropdownItems.contains(event.target)) {
                                userDropdownItems.style.display = 'none';
                                userDropdownBtn.setAttribute('aria-expanded', 'false');
                            }
                        });

                        // Hide dropdown when it loses focus (optional for keyboard users)
                        userDropdownItems.addEventListener('focusout', function(event) {
                            if (!userDropdownItems.contains(event.relatedTarget)) {
                                userDropdownItems.style.display = 'none';
                                userDropdownBtn.setAttribute('aria-expanded', 'false');
                            }
                        });
                    </script>
                </div>
            </div>
        @endauth
    </div>

</div>

<script>
    document.addEventListener("scroll", function() {
        const navBar = document.getElementById("navBar");
        const logoWhite = document.getElementById("logoWhite");
        const logoColored = document.getElementById("logoColored");
        const userDropdownBtn = document.getElementById("userDropdownBtn");
        const avatarContainer = document.getElementById("avatarContainer");

        if (window.scrollY > 50) {
            navBar.classList.add("scrolled");
            logoWhite.style.display = "none";
            logoColored.style.display = "block";

            if (userDropdownBtn) {
                userDropdownBtn.style.color = 'black';
            }

            if (avatarContainer) {
                avatarContainer.style.background = '#005096';
            }

        } else {
            navBar.classList.remove("scrolled");
            logoWhite.style.display = "block";
            logoColored.style.display = "none";

            if (userDropdownBtn) {
                userDropdownBtn.style.color = 'white';
            }

            if (avatarContainer) {
                avatarContainer.style.background = 'transparent';
            }
        }
    });
</script>
