@auth
<button
    onclick="localStorage.removeItem('vapp_tour_done_v1'); window.__vappTour && window.__vappTour.restart()"
    title="Take a tour"
    class="flex items-center justify-center w-8 h-8 rounded-full text-gray-400 hover:text-primary-600 hover:bg-primary-50 transition-colors"
>
    <x-heroicon-o-question-mark-circle class="w-5 h-5" />
</button>
@endauth
