<div id="fi-preloader" aria-hidden="true">
    <div id="fi-preloader-spinner"></div>
</div>

<style>
    #fi-preloader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(2px);
        transition: opacity 0.25s ease;
    }

    #fi-preloader.fi-preloader--hidden {
        opacity: 0;
        pointer-events: none;
    }

    #fi-preloader-spinner {
        width: 44px;
        height: 44px;
        border: 4px solid #f3f4f6;
        border-top-color: var(--primary-600, #ea580c);
        border-radius: 50%;
        animation: fi-spin 0.7s linear infinite;
    }

    @keyframes fi-spin {
        to { transform: rotate(360deg); }
    }
</style>

<script>
    (function () {
        var loader = document.getElementById('fi-preloader');

        function hide() {
            if (!loader) return;
            loader.classList.add('fi-preloader--hidden');
            setTimeout(function () {
                if (loader) loader.style.display = 'none';
            }, 260);
        }

        function show() {
            if (!loader) return;
            loader.style.display = 'flex';
            // Force reflow so the transition plays
            loader.offsetHeight;
            loader.classList.remove('fi-preloader--hidden');
        }

        // Initial page load
        if (document.readyState === 'complete') {
            hide();
        } else {
            window.addEventListener('load', hide);
        }

        // Livewire SPA navigation (Filament v3 uses wire:navigate)
        document.addEventListener('livewire:navigating', show);
        document.addEventListener('livewire:navigated', hide);
    })();
</script>
