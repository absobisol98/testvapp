{{-- Tailwind --}}
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}

<a class="w-full" href="{{ route("filament.admin.resources.volunteers.index") }}">
    <div class="w-full flex flex-col items-center justify-center text-center gap-2">
        <div class="!w-[120px] !h-[120px] flex items-center justify-center overflow-hidden rounded-full relative">
            <img class="h-full w-full object-cover" src="https://cms.imgworlds.com/assets/a5366382-0c26-4726-9873-45d69d24f819.jpg?key=home-gallery" alt="User Profile Image">
        </div>
        {{-- @dd(auth()->user()) --}}
        <div>
            <p class="!text-[20px] !font-[500]">{{ auth()->user()->name }}</p>
            <p class="!text-[14px] !font-[300]">Member Since: {{ auth()->user()->created_at->format('F j, Y') }}</p>
        </div>
    </div>
</a>