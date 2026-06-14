<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use App\Models\Event;

class EventPage extends Page
{
    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.event';

    public $record;

    public function mount($record): void
    {
        $this->record = Event::findOrFail($record);

        // dd($this->record->point_of_contact);
        // dd($this->record->event_recurring);
    }

    /**
     * ------------------------------------------------------------
     * EVENT COMPLETENESS CHECK (SAFE ADD-ON)
     * ------------------------------------------------------------
     */
    public function getDataCompleteness(): array
    {
        $record = $this->record;

        $issues = [];

        // Core fields
        if (!$record->title) {
            $issues[] = 'Missing event title';
        }

        if (!$record->description) {
            $issues[] = 'Missing event description';
        }

        if (!$record->start_date) {
            $issues[] = 'Missing start date';
        }

        if (!$record->end_date) {
            $issues[] = 'Missing end date';
        }

        // Relationships (safe checks)
        if (!$record->program) {
            $issues[] = 'No program assigned';
        }

        if (!$record->point_of_contact) {
            $issues[] = 'Missing point of contact';
        }

        if (isset($record->facilitators) && $record->facilitators->isEmpty()) {
            $issues[] = 'No facilitators assigned';
        }

        if (isset($record->slots) && $record->slots->isEmpty()) {
            $issues[] = 'No volunteer slots created';
        }

        if (method_exists($record, 'getMedia') &&
            $record->getMedia('event-attachments')->count() === 0) {
            $issues[] = 'No attachments uploaded';
        }

        return $issues;
    }

    /**
     * ------------------------------------------------------------
     * VIEW DATA (EXTENDED ONLY, NO BREAKING CHANGES)
     * ------------------------------------------------------------
     */
    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'completenessIssues' => $this->getDataCompleteness(),
        ];
    }
}