<div>
@if($availableRoles->count() > 1)
<div class="flex items-center px-3 py-1" x-data="{ open: false }">
    <div class="relative">
        <button
            @click="open = !open"
            class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
        >
            <x-heroicon-o-user-circle class="w-4 h-4 text-gray-500" />
            <span>{{ $currentRole }}</span>
            <x-heroicon-o-chevron-down class="w-3 h-3 text-gray-400" />
        </button>

        <div
            x-show="open"
            x-cloak
            @click.away="open = false"
            class="absolute right-0 mt-1 w-52 rounded-xl bg-white dark:bg-gray-800 shadow-lg ring-1 ring-gray-200 dark:ring-gray-700 z-50 overflow-hidden"
        >
            <p class="px-3 pt-2 pb-1 text-xs text-gray-400 uppercase tracking-wide font-semibold">Switch Role</p>
            @foreach($availableRoles as $role)
            <button
                wire:click="switchRole('{{ $role }}')"
                @click="open = false"
                class="flex items-center justify-between w-full px-3 py-2 text-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ $role === $currentRole ? 'text-primary-600 font-semibold' : 'text-gray-700 dark:text-gray-200' }}"
            >
                <span>{{ $role }}</span>
                @if($role === $currentRole)
                    <x-heroicon-o-check class="w-4 h-4 text-primary-600" />
                @endif
            </button>
            @endforeach
        </div>
    </div>
</div>
@endif
</div>
