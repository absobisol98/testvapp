<x-filament-panels::page>
    <style>
        /* ── Strip the Filament grid record/cell wrappers ── */

        /* Record row — the ghost "card behind" */
        .fi-ta-record {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            outline: none !important;
            ring: none !important;
        }
        /* Filament v3 adds ring via these utility classes */
        .fi-ta-record:focus,
        .fi-ta-record:focus-within,
        .fi-ta-record:hover {
            box-shadow: none !important;
            outline: none !important;
        }

        /* Column cell inside the record */
        .fi-ta-col {
            padding: 0 !important;
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            margin: 0 !important;
        }
        .fi-ta-col > div {
            padding: 0 !important;
            margin: 0 !important;
            height: 100%;
        }

        /* The grid container — restore our own gap */
        .fi-ta-content-grid {
            gap: 20px !important;
        }

        /* Actions row (Add to Calendar etc.) that appears below the card */
        .fi-ta-record .fi-ta-actions {
            display: none !important;
        }
    </style>
    {{ $this->table }}
</x-filament-panels::page>
