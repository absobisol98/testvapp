<style>
    #logoWhite {
        display: block;
    }

    #logoColored {
        display: none;
    }

    #navBar {
        padding: 1.5rem 6rem;
        max-width: 1920px;
        margin: 0 auto;
        background: white;
        position: absolute;
        transition: all 0.3s ease-in-out;
    }

    #navBar.scrolled {
        padding: 1rem 6rem;
        background-color: white;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    #userDropdownBtn {
        color: white;
    }

    #avatarContainer {
        background: transparent;
    }

    /* Desktop menu improvements */
    .desktop-menu {
        gap: 2.5rem;
    }

    .desktop-menu p {
        font-size: 1rem;
        padding: 0.5rem 0;
        transition: all 0.2s ease;
        color: #6B7280;
    }

    .desktop-menu a.active p {
        color: #004b87;
        font-weight: 600;
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

    /* Action buttons */
    .nav-button {
        padding: 0.75rem 1.5rem;
        gap: 0.75rem;
        transition: all 0.3s ease;
    }

    /* Responsive styles */
    @media screen and (max-width: 1279px) {
        #navBar {
            padding: 1.5rem 2rem;
        }

        #navBar.scrolled {
            padding: 1rem 2rem;
        }

        .desktop-menu {
            gap: 1.5rem;
        }
    }

    @media screen and (max-width: 768px) {
        .hamburger {
            display: block;
        }

        .desktop-menu {
            display: none;
        }

        #navBar {
            padding: 1rem;
        }

        #navBar.scrolled {
            padding: 1rem;
        }
    }
    

    #userDropdownBtn {
        color: black !important;
    }

    #avatarContainer {
        background: #005096 !important;
    }

</style>

    @php
        $isBusinessUnit = (false) ? true : false;
    @endphp

<div id="navBar" class="w-full flex items-center justify-between z-50">

    <a href="{{ route('main.homepage.view') }}" id="logoWhite" class="h-full max-w-[150px] md:max-w-[273px]">
        <img class="w-full" src="{{ asset('img/logo-colored.png') }}" alt="">
    </a>

    <a href="{{ route('main.homepage.view') }}" id="logoColored" class="none h-full max-w-[150px] md:max-w-[273px]">
        <img class="w-full" src="{{ asset('img/logo-colored.png') }}" alt="">
    </a>

    <!-- Desktop Menu -->
    <div class="desktop-menu flex items-center justify-center gap-4">
        <a href="{{ route('main.homepage.view') }}" class="{{ request()->routeIs('main.homepage.view') ? 'active' : '' }}">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Home</p>
        </a>

        <a href="{{ route('stories.view') }}" class="{{ request()->routeIs('stories.view') ? 'active' : '' }}">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Stories</p>
        </a>

        <a href="{{ route('ourpartners.view') }}" class="{{ request()->routeIs('ourpartners.view') ? 'active' : '' }}">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Our Partners</p>
        </a>

        <!-- <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Who We Are</p>
        </a>

        <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Programs</p>
        </a>

        <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">How to Help</p>
        </a>

        <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">News</p>
        </a>

        <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Careers</p>
        </a>

        <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">Contact Us</p>
        </a>

        <a href="">
            <p class="font-medium text-base text-black hidden md:block hover:underline">E-learning</p>
        </a> --->
    </div>

    <div class="h-full flex items-center justify-between gap-4">
        @guest
            <a href="{{ route('volunteer.form.view') }}">
                <div
                    class="h-auto md:h-[56px] w-auto md:w-[244px] rounded-full md:rounded-[20px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                    <div class="w-[30px] h-[30px] inline-flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="31" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-plus">
                            <path d="M2 21a8 8 0 0 1 13.292-6"/>
                            <circle cx="10" cy="8" r="5"/>
                            <path d="M19 16v6"/>
                            <path d="M22 19h-6"/>
                        </svg>
                    </div>
                    <p class="font-medium text-base text-white">Become a Volunteer</p>
                </div>
            </a>

            <a href="{{route('filament.admin.auth.login')}}">
                <div
                    class="h-auto md:h-[56px] w-auto md:w-[200px] rounded-full md:rounded-[20px] bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                    <div class="w-[30px] h-[30px] inline-flex items-center justify-center">
                        <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.6265 4.83138H6.4602C5.77507 4.83138 5.11807 5.10353 4.63364 5.58796C4.14921 6.07239 3.87703 6.72943 3.87703 7.41452V25.4965C3.87703 26.1816 4.14921 26.8386 4.63364 27.3231C5.11807 27.8075 5.77507 28.0796 6.4602 28.0796H11.6265M18.0843 22.9134L11.6265 16.4555M11.6265 16.4555L18.0843 9.99766M11.6265 16.4555H27.1253"
                                stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <p class="font-medium text-base text-white">Log In</p>
                </div>
            </a>
        @endguest

        {{-- User Dropdown --}}
        @auth
        <a href="{{ route('filament.admin.pages.dashboard') }}" class="hidden md:block">
            <div class="h-auto md:h-[56px] w-auto md:w-[184px] rounded-[20px] bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                <div class="w-[30px] h-[30px] inline-flex items-center justify-center">
                    @include('custom.icons.navbar-icons', [
                        'icon' => 'avatar',
                    ])
                </div>
                <p class="font-medium text-base text-white">Dashboard</p>
            </div>
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
                <a href="{{ route('main.homepage.view') }}" class="{{ request()->routeIs('main.homepage.view') ? 'active' : '' }}">
                    <p class="font-medium text-base text-black hover:underline">Home</p>
                </a>
                <a href="{{ route('stories.view') }}" class="{{ request()->routeIs('stories.view') ? 'active' : '' }}">
                    <p class="font-medium text-base text-black hover:underline">Stories</p>
                </a>
                <a href="{{ route('ourpartners.view') }}" class="{{ request()->routeIs('ourpartners.view') ? 'active' : '' }}">
                    <p class="font-medium text-base text-black hover:underline">Our Partners</p>
                </a>

                <!-- <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">Who We Are</p>
                </a>

                <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">Programs</p>
                </a>

                <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">How to Help</p>
                </a>

                <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">News</p>
                </a>

                <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">Careers</p>
                </a>

                <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">Contact Us</p>
                </a>

                <a href="">
                    <p class="font-medium text-base text-black hidden md:block hover:underline">E-learning</p>
                </a> --->
            </div>

            <div class="w-full flex flex-col space-y-4">
                @guest
                <a href="{{ route('volunteer.form.view') }}">
                    <div
                        class="h-auto md:h-[56px] w-auto md:w-[244px] rounded-full md:rounded-[20px] bg-[#FF781E] flex items-center justify-center p-2 hover:bg-[#FF9141]">
                        <div class="w-[30px] h-[30px] inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-round-plus">
                                <path d="M2 21a8 8 0 0 1 13.292-6"/>
                                <circle cx="10" cy="8" r="5"/>
                                <path d="M19 16v6"/>
                                <path d="M22 19h-6"/>
                            </svg>
                        </div>
                        <p class="font-medium text-base text-white">Become a Volunteer</p>
                    </div>
                </a>

                <a href="{{route('filament.admin.auth.login')}}">
                    <div
                        class="h-auto md:h-[56px] w-auto md:w-[200px] rounded-full md:rounded-[20px] bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                        <div class="w-[30px] h-[30px] inline-flex items-center justify-center">
                            <svg width="31" height="32" viewBox="0 0 31 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.6265 4.83138H6.4602C5.77507 4.83138 5.11807 5.10353 4.63364 5.58796C4.14921 6.07239 3.87703 6.72943 3.87703 7.41452V25.4965C3.87703 26.1816 4.14921 26.8386 4.63364 27.3231C5.11807 27.8075 5.77507 28.0796 6.4602 28.0796H11.6265M18.0843 22.9134L11.6265 16.4555M11.6265 16.4555L18.0843 9.99766M11.6265 16.4555H27.1253"
                                    stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <p class="font-medium text-base text-white">Log In</p>
                    </div>
                </a>
                @endguest

                {{-- User Dropdown --}}
                @auth
                <a href="{{ route('filament.admin.pages.dashboard') }}">
                    <div class="h-auto md:h-[56px] w-auto md:w-[184px] rounded-[20px] bg-[#005096] flex items-center justify-center p-2 hover:bg-[#1A67B1]">
                        <div class="w-[30px] h-[30px] inline-flex items-center justify-center">
                            @include('custom.icons.navbar-icons', [
                                'icon' => 'avatar',
                            ])
                        </div>
                        <p class="font-medium text-base text-white">Dashboard</p>
                    </div>
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Existing mobile menu functionality
        const hamburgerBtn = document.getElementById('hamburgerBtn');
        const closeMenuBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        
        // Add active state handler
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.desktop-menu a');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });

        // Existing hamburger menu listeners
        hamburgerBtn.addEventListener('click', function() {
            mobileMenu.classList.add('active');
        });

        closeMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
        });
    });

    // Existing scroll event listener
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
