{{-- If user is a Volunteer this is the sidebar links --}}
@if (false)
    <div class="w-full flex flex-col items-center justify-center gap-2 font-medium text-sm">
        {{-- Dashboard --}}
        <div class="w-full" role="none">
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="w-full block px-1 py-2 hover:bg-gray-100"
                role="menuitem" tabindex="-1" id="menu-item-0">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Notifications --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Opportunities --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- My Messages --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Certificates --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Account Settings --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Logout --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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
@endif


{{-- If user is a AFI Admin or Partners this is the sidebar links --}}
@if (true)
    <div class="w-full flex flex-col items-center justify-center gap-2 font-medium text-sm">
        {{-- Dashboard --}}
        <div class="w-full" role="none">
            <a href="{{ route('filament.admin.pages.dashboard') }}" class="w-full block px-1 py-2 hover:bg-gray-100"
                role="menuitem" tabindex="-1" id="menu-item-0">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Opportunities --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Volunteers --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
                <div class="w-full flex items-center justify-start gap-2">
                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                        @include('custom.icons.navbar-icons', [
                            'icon' => 'bell',
                        ])
                    </div>
                    <p>Volunteers</p>
                </div>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Email Announcement --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
                <div class="w-full flex items-center justify-start gap-2">
                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                        @include('custom.icons.navbar-icons', [
                            'icon' => 'message',
                        ])
                    </div>
                    <p>Email Announcement</p>
                </div>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Partners --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
                <div class="w-full flex items-center justify-start gap-2">
                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                        @include('custom.icons.navbar-icons', [
                            'icon' => 'award',
                        ])
                    </div>
                    <p>Partners</p>
                </div>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Account Settings --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Reports --}}
        <div class="w-full" role="none">
            <a href="{{route('filament.admin.pages.reports')}}" class="w-full block px-1 py-2 hover:bg-gray-100">
                <div class="w-full flex items-center justify-start gap-2">
                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                        @include('custom.icons.navbar-icons', [
                            'icon' => 'log-out',
                        ])
                    </div>
                    <p>Reports</p>
                </div>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Pages --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
                <div class="w-full flex items-center justify-start gap-2">
                    <div class="w-[28px] h-[28px] flex items-center justify-center">
                        @include('custom.icons.navbar-icons', [
                            'icon' => 'log-out',
                        ])
                    </div>
                    <p>Pages</p>
                </div>
            </a>
        </div>

        <div class="w-full border-t border-[#E1E1E1]"></div>

        {{-- Logout --}}
        <div class="w-full" role="none">
            <a href="#" class="w-full block px-1 py-2 hover:bg-gray-100">
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
@endif