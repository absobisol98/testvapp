<div>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('add-honeypot-styles', ({ styles }) => {
                const styleEl = document.createElement('style');
                styleEl.textContent = styles;
                document.head.appendChild(styleEl);
            });
        });
    </script>
</div>
