<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;
use Illuminate\Database\Eloquent\Builder;
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
                // Basic non-editable settings
                'editable' => false,
                'dragScroll' => false,
                'droppable' => false,
                'initialView' => 'dayGridMonth',
                'selectable' => false,

                // Additional non-draggable settings
                'eventStartEditable' => false,
                'eventDurationEditable' => false,
                'eventResourceEditable' => false,
                'eventResizableFromStart' => false,

                // Explicitly disable drag-n-drop
                'eventDraggable' => false,
                'eventResizable' => false,

                // JavaScript function to prevent any event modifications
                'eventAllow' => 'function() { return false; }',

                // Force events to be non-interactive for editing
                'eventInteractive' => false,

                // Additional display options
                'eventDisplay' => 'block',
                'displayEventTime' => true,
                'displayEventEnd' => true,
            ],
        ];
    }

    protected function getFilters(): array
    {
        return [
            // Category filter (similar to Thumbnail view)
            Select::make('status')
                ->label('Category')
                ->options([
                    'all' => 'All Opportunities',
                    'active' => 'Active Opportunities',
                    'upcoming' => 'Upcoming Opportunities',
                    'finished' => 'Finished Opportunities',
                    'joined' => 'My Opportunities',
                ])
                ->default('upcoming')
                ->live(),

            // Title search
            TextInput::make('search_title')
                ->label('Title')
                ->placeholder('Search by event title')
                ->live(),

            // Date filter
            DatePicker::make('from_date')
                ->label('From Date')
                ->live(),

            // Location search
            TextInput::make('location')
                ->label('Location')
                ->placeholder('Search by location')
                ->live(),
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        $query = Event::query();

        // Apply date range filter from calendar
        $query->where('start_date', '>=', $fetchInfo['start'])
              ->where('end_date', '<=', $fetchInfo['end']);

        // Apply status filter (if selected)
        $status = $this->filters['status'] ?? 'all';
        $now = now();

        if ($status !== 'all') {
            $query = match($status) {
                'active' => $query->where('start_date', '<=', $now)
                                  ->where('end_date', '>=', $now),
                'upcoming' => $query->where('start_date', '>', $now),
                'finished' => $query->where('end_date', '<', $now),
                'joined' => $query->whereHas('attendees', function (Builder $subquery) {
                    $subquery->where('attendee_id', auth()->id());
                }),
                default => $query,
            };
        }

        // Get events and map them to calendar format
        return $query->get()
            ->map(function (Event $event) {
                // Get event status
                $now = now();
                $startDate = \Carbon\Carbon::parse($event->start_date);
                $endDate = \Carbon\Carbon::parse($event->end_date);

                // Determine event status
                $statusText = '';
                $statusColor = '';

                if ($now->isAfter($endDate)) {
                    // Finished event
                    $statusText = 'Finished';
                    $statusColor = '#9CA3AF'; // Gray
                } elseif ($now->isBefore($startDate)) {
                    // Upcoming event
                    $daysUntil = floor($now->floatDiffInDays($startDate));
                    $statusText = $daysUntil < 7
                        ? ($daysUntil > 0
                            ? "Starting in {$daysUntil} " . ($daysUntil == 1 ? "day" : "days")
                            : "Starting today")
                        : 'Upcoming';
                    $statusColor = '#F55E1D'; // Blue
                } else {
                    // Active event
                    $statusText = 'Active';
                    $statusColor = '#F55E1D'; // Ayala brand color
                }

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => date('Y-m-d', strtotime($event->start_date)),
                    'end' => date('Y-m-d', strtotime($event->end_date)),
                    'url' => EventResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true,
                    'allDay' => true,

                    // Apply status-based colors
                    'backgroundColor' => $statusColor,
                    'textColor' => '#FFFFFF', // White text for better contrast
                    'borderColor' => $statusColor,

                    // Explicitly make non-draggable
                    'editable' => false,
                    'startEditable' => false,
                    'durationEditable' => false,
                    'resourceEditable' => false,
                    'display' => 'block',
                    'classNames' => ['non-draggable-event'],

                    // Add tooltip with event details
                    'extendedProps' => [
                        'description' => $event->description,
                        'location' => $event->location,
                        'status' => $statusText,
                    ],
                ];
            })
            ->all();
    }


    protected function setUp(): void
    {
        parent::setUp();

        // Initialize filter state
        $this->filters = [
            'status' => 'upcoming',
        ];
    }
}
