<?php

namespace App\Exports;

use App\Models\Event;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class EventRegistrantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
{
    public function __construct(private ?array $eventIds = null) {}

    public function title(): string
    {
        return 'Registrants';
    }

    public function collection()
    {
        $query = \App\Models\EventRegistration::with(['volunteer', 'event', 'event_slot', 'status']);

        if ($this->eventIds) {
            $query->whereIn('event_id', $this->eventIds);
        }

        return $query->orderBy('event_id')->orderBy('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Volunteer ID',
            'First Name',
            'Last Name',
            'Email',
            'Event',
            'Shift',
            'Status',
            'Company / Affiliate',
            'Emergency Contact',
            'Emergency Contact Number',
            'Registered At',
        ];
    }

    public function map($reg): array
    {
        return [
            $reg->volunteer?->volunteer_id ?? '—',
            $reg->volunteer?->firstname ?? '—',
            $reg->volunteer?->lastname ?? '—',
            $reg->volunteer?->email ?? '—',
            $reg->event?->title ?? '—',
            $reg->event_slot?->shift_name ?? '—',
            $reg->status?->name ?? '—',
            $reg->volunteer?->company_name ?? $reg->volunteer?->external_company_name ?? '—',
            $reg->volunteer?->emergency_contact_name ?? '—',
            $reg->volunteer?->emergency_contact_number ?? '—',
            $reg->created_at?->format('Y-m-d H:i') ?? '—',
        ];
    }
}
