{{-- Guided Tour — Alpine.js modal walkthrough --}}
{{-- Shown on first visit (tracked via localStorage key "vapp_tour_done"). --}}
{{-- Re-launched by any element with onclick="window.__vappTour && window.__vappTour.restart()" --}}

@auth
<div
    x-data="vappTour()"
    x-init="init()"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-[9999] flex items-center justify-center"
    style="background: rgba(0,0,0,0.55);"
>
    {{-- Card --}}
    <div
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden"
        @click.stop
    >
        {{-- Progress bar --}}
        <div class="h-1 bg-gray-100">
            <div
                class="h-1 bg-primary-500 transition-all duration-300"
                :style="`width:${((step+1)/steps.length)*100}%`"
            ></div>
        </div>

        {{-- Header image / icon --}}
        <div class="flex items-center justify-center pt-8 pb-4">
            <span
                class="flex items-center justify-center w-16 h-16 rounded-full bg-primary-50"
            >
                <x-dynamic-component
                    :component="steps[step].icon"
                    class="w-8 h-8 text-primary-600"
                />
            </span>
        </div>

        {{-- Body --}}
        <div class="px-8 pb-2 text-center">
            <h2 class="text-xl font-bold text-gray-800 mb-2" x-text="steps[step].title"></h2>
            <p class="text-sm text-gray-500 leading-relaxed" x-text="steps[step].body"></p>
        </div>

        {{-- Step dots --}}
        <div class="flex justify-center gap-2 py-4">
            <template x-for="(s, i) in steps" :key="i">
                <button
                    @click="step = i"
                    :class="i === step
                        ? 'w-6 h-2 bg-primary-500 rounded-full'
                        : 'w-2 h-2 bg-gray-300 rounded-full hover:bg-gray-400'"
                    class="transition-all duration-200"
                ></button>
            </template>
        </div>

        {{-- Footer actions --}}
        <div class="flex items-center justify-between px-8 pb-6 gap-3">
            <button
                @click="done()"
                class="text-xs text-gray-400 hover:text-gray-600 underline"
            >Skip tour</button>

            <div class="flex gap-2">
                <button
                    x-show="step > 0"
                    @click="step--"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50"
                >Back</button>

                <button
                    x-show="step < steps.length - 1"
                    @click="step++"
                    class="px-5 py-2 text-sm rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium"
                >Next</button>

                <button
                    x-show="step === steps.length - 1"
                    @click="done()"
                    class="px-5 py-2 text-sm rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium"
                >Get started</button>
            </div>
        </div>
    </div>
</div>

<script>
function vappTour() {
    const STORAGE_KEY = 'vapp_tour_done_v1';

    return {
        open: false,
        step: 0,
        steps: [
            {
                icon: 'heroicon-o-hand-raised',
                title: 'Welcome to VAPP!',
                body: 'This is the Ayala Foundation Volunteer Activity Platform. Let us walk you through the key areas so you can get started quickly.',
            },
            {
                icon: 'heroicon-o-calendar-days',
                title: 'Opportunities',
                body: 'Browse upcoming volunteer events in card or list view. Admins can create new events, manage slots, and track registrations — all from one place.',
            },
            {
                icon: 'heroicon-o-users',
                title: 'Volunteers',
                body: 'View the full list of volunteers, export records to Excel, or bulk-import new volunteers from a CSV file. Each profile shows hours logged, badges, and certificates.',
            },
            {
                icon: 'heroicon-o-document-text',
                title: 'Reports',
                body: 'The Reports page gives you a live snapshot: total hours, attendance rate, top volunteers, and a month-by-month registration chart. Switch tabs to explore opportunities and leaderboard data.',
            },
            {
                icon: 'heroicon-o-trophy',
                title: 'Your Profile',
                body: 'Volunteers earn badges and rank up as they log hours. Certificates are generated automatically once attendance is confirmed — available to download any time from the profile page.',
            },
            {
                icon: 'heroicon-o-check-circle',
                title: "You're all set!",
                body: 'You can relaunch this tour any time by clicking the Help (?) button in the top bar. Enjoy volunteering!',
            },
        ],
        init() {
            // Register global restart handle
            window.__vappTour = this;
            if (! localStorage.getItem(STORAGE_KEY)) {
                this.open = true;
            }
        },
        done() {
            this.open = false;
            localStorage.setItem(STORAGE_KEY, '1');
        },
        restart() {
            this.step = 0;
            this.open = true;
        },
    };
}
</script>
@endauth
