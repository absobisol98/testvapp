<x-filament-panels::page>
    <style>
        /* Remove grid cell wrapper padding and styling */
        .fi-ta-col {
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
        }

        /* Remove grid gap */
        .fi-ta-content {
            gap: 0 !important;
        }

        /* Remove table wrapper padding */
        .fi-ta {
            padding: 0 !important;
        }
    </style>
    {{ $this->table }}
</x-filament-panels::page>
