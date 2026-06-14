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

    .mobile-menu {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 90vh;
        background-color: white;
        z-index: 40;
        padding: 2rem;
        transition: all 0.3s ease-in-out;
        overflow-y: auto;
    }

    .mobile-menu.active {
        display: block;
        animation: slideDown 0.3s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hamburger {
        display: none;
    }

    @media screen and (max-width: 768px) {
        .hamburger {
            display: block;
        }

        .desktop-menu {
            display: none;
        }
    }
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
        background: #0433ff !important;
        }

    @media screen and (max-width: 1279px) {
        #navBar {
            padding: 30px 16px;
        }
    }
</style>

    @php
        $isBusinessUnit = (false) ? true : false;
    @endphp

<div id="navBar" class="w-full flex items-center justify-between z-50">

    <a href="{{ route('main.homepage.view') }}" id="logoWhite" class="flex items-center">
        <img class="h-8 md:h-10 w-auto" src="{{ asset('img/logo-vapp.svg') }}" alt="VApp">
    </a>

    <a href="{{ route('main.homepage.view') }}" id="logoColored" class="none flex items-center">
        <img class="h-8 md:h-10 w-auto" src="{{ asset('img/logo-vapp.svg') }}" alt="VApp">
    </a>

    <!-- Desktop Menu -->
    <div class="desktop-menu flex items-center justify-center gap-6">
        <a href="{{ route('main.homepage.view') }}">
            <p class="font-medium text-sm {{ request()->routeIs('main.homepage.view') ? 'text-[#0433ff]' : 'text-gray-700 hover:text-[#0433ff]'}} transition">
                Home
            </p>
        </a>
        <a href="{{ url('/admin/events') }}">
            <p class="font-medium text-sm text-gray-700 hover:text-[#0433ff] transition">
                Opportunities
            </p>
        </a>
        <a href="{{ route('stories.view') }}">
            <p class="font-medium text-sm {{ request()->routeIs('stories.view') ? 'text-[#0433ff]' : 'text-gray-700 hover:text-[#0433ff]'}} transition">
                Stories
            </p>
        </a>
        <a href="{{ route('ourpartners.view') }}">
            <p class="font-medium text-sm {{ request()->routeIs('ourpartners.view') ? 'text-[#0433ff]' : 'text-gray-700 hover:text-[#0433ff]'}} transition">
                Partners
            </p>
        </a>
    </div>

    <div class="h-full flex items-center justify-between gap-3">
        @guest
            <a href="{{route('filament.admin.auth.login')}}" class="hidden md:inline-flex items-center gap-2 text-gray-700 font-medium text-sm hover:text-[#0433ff] transition px-4 py-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Log In
            </a>
            <a href="{{ route('volunteer.form.view') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#ff7b00] text-white font-semibold text-sm hover:bg-[#e06e00] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Become a Volunteer
            </a>
        @endguest

        {{-- Logged-in user --}}
        @auth
        <a href="{{ route('filament.admin.pages.dashboard') }}" class="hidden md:inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#0433ff] text-white font-semibold text-sm hover:bg-[#0228cc] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>
        @endauth
    </div>

    <!-- Hamburger Button -->
    <button class="hamburger p-2" id="hamburgerBtn">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="flex justify-end mb-8">
            <button class="p-2" id="closeMenuBtn">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="w-full h-full flex flex-col items-center justify-evenly space-y-4">
            <div class="w-full flex flex-col items-center space-y-4">
                <a href="{{ route('main.homepage.view') }}">
                    <p class="font-medium text-base {{ request()->routeIs('main.homepage.view') ? 'text-[#0433ff]' : 'text-gray-700' }}">
                        Home
                    </p>
                </a>
                <a href="{{ url('/admin/events') }}">
                    <p class="font-medium text-base text-gray-700">Opportunities</p>
                </a>
                <a href="{{ route('stories.view') }}">
                    <p class="font-medium text-base {{ request()->routeIs('stories.view') ? 'text-[#0433ff]' : 'text-gray-700' }}">
                        Stories
                    </p>
                </a>
                <a href="{{ route('ourpartners.view') }}">
                    <p class="font-medium text-base {{ request()->routeIs('ourpartners.view') ? 'text-[#0433ff]' : 'text-gray-700'}}">
                        Partners
                    </p>
                </a>
            </div>

            <div class="w-full flex flex-col space-y-3">
                @guest
                <a href="{{route('filament.admin.auth.login')}}" class="w-full">
                    <div class="w-full rounded-full border-2 border-gray-300 flex items-center justify-center gap-2 p-3 hover:border-[#0433ff] hover:text-[#0433ff]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <p class="font-medium text-base">Log In</p>
                    </div>
                </a>
                <a href="{{ route('volunteer.form.view') }}" class="w-full">
                    <div class="w-full rounded-full bg-[#ff7b00] flex items-center justify-center gap-2 p-3 hover:bg-[#e06e00]">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <p class="font-medium text-base text-white">Become a Volunteer</p>
                    </div>
                </a>
                @endguest

                @auth
                <a href="{{ route('filament.admin.pages.dashboard') }}" class="w-full">
                    <div class="w-full rounded-full bg-[#0433ff] flex items-center justify-center gap-2 p-3 hover:bg-[#0228cc]">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <p class="font-medium text-base text-white">Dashboard</p>
                    </div>
                </a>
                @endauth
            </div>
        </div>
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
                avatarContainer.style.background = '#0433ff';
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

    // Hamburger Menu functionality
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburgerBtn.addEventListener('click', function() {
            mobileMenu.classList.add('active');
        });

        closeMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
        });
    });
</script>
