{{-- Guided Tour — shown on first login after email verification, re-launchable via window.__vappTour.restart() --}}
@auth
@if(auth()->user()->hasVerifiedEmail())
@php $forceShowTour = session()->pull('vapp_show_tour', false); @endphp
<script>window.__vappForceShowTour = {{ $forceShowTour ? 'true' : 'false' }};</script>
@verbatim
<div
    x-data="vappTour()"
    x-init="init()"
    x-show="open"
    x-cloak
    class="fixed inset-0 flex items-center justify-center"
    style="background: rgba(0,0,0,0.55); z-index: 2147483647;"
>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden" @click.stop>

        <!-- Progress bar -->
        <div class="h-1 bg-gray-100">
            <div class="h-1 bg-primary-500 transition-all duration-300"
                 :style="'width:' + Math.round(((step+1)/steps.length)*100) + '%'"></div>
        </div>

        <!-- Icon -->
        <div class="flex items-center justify-center pt-8 pb-4">
            <span class="flex items-center justify-center w-16 h-16 rounded-full bg-primary-50">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          :d="{
                            hand:     'M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11',
                            calendar: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            users:    'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                            chart:    'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                            star:     'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                            check:    'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                          }[steps[step].icon]"></path>
                </svg>
            </span>
        </div>

        <!-- Body -->
        <div class="px-8 pb-2 text-center">
            <h2 class="text-xl font-bold text-gray-800 mb-2" x-text="steps[step].title"></h2>
            <p class="text-sm text-gray-500 leading-relaxed" x-text="steps[step].body"></p>
        </div>

        <!-- Step dots -->
        <div class="flex justify-center gap-2 py-4">
            <template x-for="(s, i) in steps" :key="i">
                <button
                    @click="step = i"
                    :class="i === step ? 'w-6 h-2 bg-primary-500 rounded-full' : 'w-2 h-2 bg-gray-300 rounded-full'"
                    class="transition-all duration-200"
                ></button>
            </template>
        </div>

        <!-- Footer -->
        <div class="flex items-center justify-between px-8 pb-6 gap-3">
            <button @click="done()" class="text-xs text-gray-400 hover:text-gray-600 underline">Skip tour</button>
            <div class="flex gap-2">
                <button x-show="step > 0" @click="step--"
                        class="px-4 py-2 text-sm rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">Back</button>
                <button x-show="step < steps.length - 1" @click="step++"
                        class="px-5 py-2 text-sm rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium">Next</button>
                <button x-show="step === steps.length - 1" @click="done()"
                        class="px-5 py-2 text-sm rounded-lg bg-primary-600 text-white hover:bg-primary-700 font-medium">Get started</button>
            </div>
        </div>
    </div>
</div>

<script>
function vappTour() {
    return {
        open: false,
        step: 0,
        steps: [
            { icon: 'hand', title: 'Welcome to VAPP!',        body: 'This is the Ayala Foundation Volunteer Activity Platform. Let us walk you through the key areas so you can get started quickly.' },
            { icon: 'calendar', title: 'Opportunities',        body: 'Browse upcoming volunteer events in card or list view. Admins can create events, manage slots, and track registrations — all in one place.' },
            { icon: 'users', title: 'Volunteers',              body: 'View volunteer profiles, export records to Excel, or bulk-import new volunteers from a CSV file. Each profile shows hours, badges, and certificates.' },
            { icon: 'chart', title: 'Reports',                 body: 'Live stats: total hours, attendance rate, top volunteers, and monthly registration charts. Switch tabs to explore opportunities and leaderboard data.' },
            { icon: 'star', title: 'Badges & Certificates',    body: 'Volunteers earn badges and rank up as they log hours. Certificates are generated automatically once attendance is confirmed.' },
            { icon: 'check', title: "You're all set!",         body: 'Click the Help button in the top bar any time to relaunch this tour. Enjoy volunteering!' },
        ],
        init() {
            document.body.appendChild(this.$el);
            window.__vappTour = this;
            const params = new URLSearchParams(window.location.search);
            const replay = params.get('replay_tour') === '1';
            const forceShow = window.__vappForceShowTour === true;
            if (replay || forceShow || ! localStorage.getItem('vapp_tour_done_v1')) {
                localStorage.removeItem('vapp_tour_done_v1');
                this.open = true;
                params.delete('replay_tour');
                const clean = window.location.pathname + (params.toString() ? '?' + params : '');
                window.history.replaceState({}, '', clean);
            }
        },
        done() {
            this.open = false;
            localStorage.setItem('vapp_tour_done_v1', '1');
        },
        restart() {
            this.step = 0;
            this.open = true;
        },
    };
}
</script>
@endverbatim
@endif
@endauth
