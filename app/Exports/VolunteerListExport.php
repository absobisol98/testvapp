<?php

namespace App\Exports;

use App\Models\Volunteer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class VolunteerListExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private ?array $ids = null) {}

    public function collection()
    {
        $query = Volunteer::with(['company']);

        if ($this->ids) {
            $query->whereIn('id', $this->ids);
        }

        return $query->orderBy('volunteer_id')->get();
    }

    public function headings(): array
    {
        return [
            'Volunteer ID',
            'First Name',
            'Last Name',
            'Email',
            'Company / Affiliate',
            'Program Interests',
            'Skills',
            'Emergency Contact',
            'Emergency Contact Number',
            'Verified At',
            'Registered At',
        ];
    }

    public function map($volunteer): array
    {
        return [
            $volunteer->volunteer_id,
            $volunteer->firstname,
            $volunteer->lastname,
            $volunteer->email,
            $volunteer->company_name ?? $volunteer->external_company_name ?? '—',
            is_array($volunteer->program_interests)
                ? implode(', ', $volunteer->program_interests)
                : ($volunteer->program_interests ?? '—'),
            is_array($volunteer->skills)
                ? implode(', ', $volunteer->skills)
                : ($volunteer->skills ?? '—'),
            $volunteer->emergency_contact_name ?? '—',
            $volunteer->emergency_contact_number ?? '—',
            $volunteer->email_verified_at?->format('Y-m-d') ?? '—',
            $volunteer->created_at?->format('Y-m-d') ?? '—',
        ];
    }
}
