<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VolunteerHoursExport implements FromCollection, WithHeadings, WithMapping
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'Opportunity',
            'Business Unit',
            'Volunteer',
            'Slot Type',
            'Total Hours',
            'Time In',
            'Time Out',
        ];
    }

    public function map($record): array
    {
        return [
            $record->event->title ?? 'N/A',
            $record->event->companies->first()->name ?? 'N/A',
            $record->attendee->name ?? 'N/A',
            $record->slot->name ?? 'N/A',
            $record->get_totalHrs(),
            $record->time_in ? date('Y-m-d H:i:s', strtotime($record->time_in)) : 'N/A',
            $record->time_out ? date('Y-m-d H:i:s', strtotime($record->time_out)) : 'N/A',
        ];
    }
}
