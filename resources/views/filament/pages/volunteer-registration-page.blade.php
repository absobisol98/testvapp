<filament::card>

    <head>
        <base target="_blank">
    </head>

    <div class="fi-layout min-h-screen max-h-auto px-8 max-w-5xl mx-auto mb-5" id="volunteer-registration-page">
        <div class="justify-center items-center mx-auto p-4">
            <x-filament::section collapsible>
                <x-slot name="heading">
                    <div id="heading" class="grid justify-center items-center mx-auto">
                        {{--  @if ($this->project->logo)
                                        <img src="{{ $this->project->logo }}" alt="" width="50" height="50"
                                            class="rounded-full mx-auto"/>
                                    @endif --}}
                        Project: Project Name
                    </div>
                </x-slot>
                <x-slot name="description">
                    <div id="description" class="mx-auto max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                        This is all the information we hold about the Project Name.
                    </div>
                </x-slot>

                {{-- Content --}}
                <div id="project-description"
                    class="prose max-w-none border-none px-3 py-1.5 text-base text-gray-950 dark:prose-invert focus-visible:outline-none sm:text-sm sm:leading-6 dark:text-white">
                    This is a project description
                </div>
            </x-filament::section>


            <hr class="my-12 h-0.5 border-t-0 bg-neutral-100 opacity-100 dark:opacity-50"
                style="margin-top: 20px;" />

            <h1
                class="fi-header-heading text-5xl font-bold tracking-tight text-gray-950 sm:text-5xl dark:text-white my-4">
                Become a Volunteer
            </h1>

            <form wire:submit="submit" class="mb-8">
                {{ $this->form }}

                <x-filament::button wire:click="submit" style="float:right; margin-top:10px">
                    Submit
                </x-filament::button>
            </form>
        </div>
    </div>
</filament::card>
