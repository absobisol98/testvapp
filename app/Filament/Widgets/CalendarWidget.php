<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Illuminate\Database\Eloquent\Model;

class CalendarWidget extends FullCalendarWidget
{
    protected static ?string $recordKey = null;

    public Model|string|int|null $record = null;



    protected function headerActions(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        return [
            'config' => [
                'editable' => false,
                'dragScroll' => false,
                'droppable' => false,
                'initialView' => 'dayGridMonth',
                'selectable' => false,
            ],
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('start_date', '>=', $fetchInfo['start'])
            ->where('end_date', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Event $event) => [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }
}
