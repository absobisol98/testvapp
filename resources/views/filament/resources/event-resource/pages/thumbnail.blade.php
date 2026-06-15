<x-filament-panels::page>
    <style>
        /* Remove grid cell wrapper padding and styling */
        .fi-ta-col {
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            margin: 0 !important;
        }

        /* Remove grid gap spacing */
        .fi-ta-content {
            gap: 0 !important;
            padding: 0 !important;
        }

        /* Remove table wrapper padding */
        .fi-ta {
            padding: 0 !important;
            gap: 0 !important;
        }

        /* Grid layout - no padding */
        .fi-ta-content > div {
            padding: 0 !important;
        }

        /* Card container spacing */
        .fi-ta-col > div {
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Ensure grid spacing is removed */
        [class*="grid"] {
            gap: 0 !important;
        }

        /* Page content spacing */
        .fi-page-content {
            padding: 0 !important;
        }
    </style>
    {{ $this->table }}
</x-filament-panels::page>
