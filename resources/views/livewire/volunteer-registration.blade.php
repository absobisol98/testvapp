<div class="fi-layout min-h-screen max-h-auto px-8 max-w-5xl mx-auto mb-5" id="volunteer-registration-page">
    <div class="justify-center items-center mx-auto p-4">
        <h1 class="fi-header-heading text-5xl font-bold tracking-tight text-gray-950 sm:text-5xl dark:text-white my-4">
            Become a Volunteer
        </h1>

        <form wire:submit="submit" class="mb-8">
            {{ $this->form }}

            <x-filament::button wire:click="submit" style="float:right; margin-top:25px">
                Register
            </x-filament::button>
        </form>
    </div>
</div>
