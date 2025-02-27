<x-filament-widgets::widget>
    <div
        x-data="{
            calendar: null,

            initCalendar() {
                // Initialize the calendar
                this.calendar = new FullCalendar.Calendar($refs.calendar, {
                    // Get configuration from PHP
                    ...{{ json_encode($this->getViewData()['config']) }},

                    // Events fetching
                    events: (info, successCallback) => {
                        const start = info.startStr
                        const end = info.endStr

                        // Get events from server
                        $wire.fetchEvents({ start, end })
                            .then((events) => {
                                // Again ensure all events are non-draggable
                                events.forEach(event => {
                                    event.editable = false;
                                    event.startEditable = false;
                                    event.durationEditable = false;
                                    event.resourceEditable = false;
                                });
                                successCallback(events)
                            })
                    },

                    // When calendar is fully rendered
                    eventDidMount: function(info) {
                        // Add tooltips for events
                        if (info.event.extendedProps?.description) {
                            tippy(info.el, {
                                content: info.event.title + '<br>' +
                                         'Status: ' + info.event.extendedProps.status + '<br>' +
                                         'Location: ' + info.event.extendedProps.location,
                                allowHTML: true,
                                theme: 'light-border',
                            });
                        }

                        // Force non-draggable
                        info.el.setAttribute('draggable', 'false');
                        info.el.classList.add('non-draggable-event');
                    },

                    // Explicitly prevent any drag operations
                    eventDragStart: function() { return false; },
                    eventDragStop: function() { return false; },
                    eventResizeStart: function() { return false; },
                    eventResizeStop: function() { return false; }
                });

                this.calendar.render();

                // Add listeners to ensure no element becomes draggable
                document.addEventListener('dragstart', function(e) {
                    if (e.target.closest('.fc-event')) {
                        e.preventDefault();
                    }
                }, true);
            }
        }"
        x-init="initCalendar"
        wire:ignore
        {{
            $attributes
                ->merge($getExtraAttributes())
                ->class(['filament-fullcalendar'])
        }}
    >
        <div class="flex items-center justify-between gap-4 p-2">
            <h2 class="text-xl font-semibold tracking-tight filament-fullcalendar-heading">
                {{ $this->getHeading() }}
            </h2>

            <div class="flex items-center justify-end gap-2 shrink-0">
                @foreach ($this->getFilters() as $filter)
                    {{ $filter }}
                @endforeach
            </div>

            <div class="flex items-center justify-end gap-2 shrink-0">
                @foreach ($this->headerActions as $action)
                    {{ $action }}
                @endforeach
            </div>
        </div>

        <div class="p-4">
            <div
                x-ref="calendar"
                wire:ignore
                class="overflow-x-auto filament-fullcalendar-container"
            ></div>
        </div>
    </div>

    <style>
        /* Remove any drag styling */
        .non-draggable-event,
        .fc-event,
        .fc-event-draggable {
            cursor: pointer !important;
            user-select: none !important;
        }

        /* Hide drag handles */
        .fc-event-resizer {
            display: none !important;
        }
    </style>
</x-filament-widgets::widget>
