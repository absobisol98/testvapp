@auth
<button
    onclick="localStorage.removeItem('vapp_tour_done_v1'); window.__vappTour && window.__vappTour.restart()"
    title="Help & Tour"
    class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-sm font-medium text-white bg-white/20 hover:bg-white/30 transition-colors"
>
    <x-heroicon-o-question-mark-circle class="w-4 h-4 text-white" />
    <span class="hidden sm:inline text-xs">Help</span>
</button>
@endauth
