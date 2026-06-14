<div id="footer" class="py-[5%] px-[5%] bg-[#F2F2F2]">
    <div class="w-full grid grid-cols-1 md:grid-cols-4 gap-8 mb-4 md:mb-8">
        <!-- First Column - Logos and Social Links -->
        <div class="w-full flex flex-col items-start justify-center gap-[24px] text-[16px] font-semibold">
            <!-- Responsive Logo Container -->
            <div class="flex items-center justify-between w-full">
                <img class="w-[200px] md:w-[280px]" src="{{ asset('img/logo-colored.png') }}" alt="VAPP Logo">
                <img class="md:hidden" src="{{ asset('img/npc-logo.png') }}" alt="NPC Logo" style="width: 100px">
            </div>

            <!-- Social Media Icons -->
            <div class="flex items-center justify-between w-full md:w-fit gap-6 mt-4">
                <!-- Facebook -->
                <a href="#" class="bg-[#e87722] p-3 rounded-full hover:bg-[#004b87] transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/>
                    </svg>
                </a>
                <!-- Twitter -->
                <a href="#" class="bg-[#e87722] p-3 rounded-full hover:bg-[#004b87] transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>
                    </svg>
                </a>
                <!-- Instagram -->
                <a href="#" class="bg-[#e87722] p-3 rounded-full hover:bg-[#004b87] transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
                <!-- YouTube -->
                <a href="#" class="bg-[#e87722] p-3 rounded-full hover:bg-[#004b87] transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                    </svg>
                </a>
                <!-- LinkedIn -->
                <a href="#" class="bg-[#e87722] p-3 rounded-full hover:bg-[#004b87] transition-all duration-300 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                    </svg>
                </a>
            </div>

            <!-- Terms and Privacy Links -->
            <div class="flex items-center justify-center w-full md:w-fit gap-3">
                <nav class="flex flex-row md:flex-col justify-between gap-3 text-[#004b87]">
                    <a href="{{ route('terms-and-conditions') }}" class="text-gray-600 hover:text-[#004b87] transition-colors font-bold text-[16px]">Terms & Conditions</a>
                    <a href="{{ route('data-privacy-policy') }}" class="text-gray-600 hover:text-[#004b87] transition-colors font-bold text-[16px]">Data Privacy Policy</a>
                </nav>
            </div>
        </div>

        <!-- Second Column - Contact Information -->
        <div class="w-full flex flex-col items-start justify-start gap-[24px] text-[14px] font-semibold">
            <h3 class="text-[#004b87] font-bold text-xl">Contact Us</h3>
            <div class="flex flex-col gap-6">
                <div class="flex items-start text-[#004b87] gap-3">
                    <svg class="w-8 h-8 flex-shrink-0" fill="#e87722" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-gray-600 text-[16px] leading-relaxed font-bold">4th Floor of 6767 Ayala Makati Stock Exchange Building, Brgy. Bel-air, Makati City</p>
                </div>
                <div class="flex items-center text-[#004b87] gap-3">
                    <svg class="w-8 h-8 flex-shrink-0" fill="#e87722" stroke="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <p class="text-gray-600 text-[16px] font-bold">(+632) 7759 8288</p>
                </div>
            </div>
        </div>

        <!-- Third Column - Empty or Additional Content -->
        <div class="hidden md:block">
            <!-- Additional content if needed -->
        </div>

        <!-- Fourth Column - NPC Logo (Desktop Only) -->
        <div class="hidden md:flex w-full flex-col items-end justify-end">
            <img class="w-auto h-[200px] object-contain" src="{{ asset('img/npc-logo.png') }}" alt="NPC Logo">
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="w-full pt-4">
        <p class="text-[16px] font-semibold text-[#004b87] text-center md:text-left">
            © {{ date('Y') }} Ayala Foundation, Inc. All Rights Reserved
        </p>
    </div>
</div>
