<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventRegistrationTableAction;
use App\Actions\EventsGetTableQueryAction;
use App\Filament\Resources\EventResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class Thumbnail extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected static ?string $title = 'Opportunities';

    protected static string $view = 'filament.resources.event-resource.pages.thumbnail';

    protected function getTableQuery(): ?Builder
    {
        $query = (new EventsGetTableQueryAction())->execute(auth()->user());

        // Volunteers only see published events
        if (auth()->user()->hasActiveRole('Volunteer')) {
            $query->where('is_published', true);
        }

        return $query->with(['slots', 'registrations', 'program']);
    }

    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        $isVolunteer = auth()->user()->hasActiveRole('Volunteer');

        return $table
            ->columns([
                View::make('filament.tables.columns.event-thumbnail'),
            ])
            ->filters([
                Filter::make('status')
                    ->label('Show')
                    ->form([
                        Select::make('status')
                            ->label('Show')
                            ->selectablePlaceholder(false)
                            ->default('upcoming')
                            ->options($isVolunteer ? [
                                'upcoming'     => 'Upcoming',
                                'available'    => 'Available (has open slots)',
                                'my_upcoming'  => 'My Upcoming Shifts',
                                'my_past'      => 'My Past Opportunities',
                                'all'          => 'All',
                            ] : [
                                'all'          => 'All Events',
                                'upcoming'     => 'Upcoming Events',
                                'joined'       => 'Joined Events',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match($data['status'] ?? 'upcoming') {
                            'upcoming'    => $query->where('start_date', '>=', now()->startOfDay()),
                            'available'   => $query->where('start_date', '>=', now()->startOfDay())
                                                   ->whereHas('slots', fn ($q) =>
                                                       $q->whereRaw('total_slots > (
                                                           SELECT COUNT(*) FROM event_registrations er
                                                           WHERE er.slot_type_id = event_slots.id AND er.status_id != 3
                                                       )')
                                                   ),
                            'my_upcoming' => $query->whereHas('registrations', fn ($q) =>
                                                $q->where('volunteer_id', auth()->id())
                                                  ->whereNotIn('status_id', [3]))
                                                ->where('end_date', '>=', now()),
                            'my_past'     => $query->whereHas('registrations', fn ($q) =>
                                                $q->where('volunteer_id', auth()->id())
                                                  ->whereNotIn('status_id', [3]))
                                                ->where('end_date', '<', now()),
                            'joined'      => $query->whereHas('attendees', fn ($q) =>
                                                $q->where('attendee_id', auth()->id())),
                            default       => $query,
                        };
                    }),

                Filter::make('search_title')
                    ->form([
                        TextInput::make('search_title')
                            ->label('Title')
                            ->placeholder('Search by title'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $data['search_title']
                            ? $query->where('title', 'like', "%{$data['search_title']}%")
                            : $query
                    ),

                Filter::make('date_range')
                    ->label('From Date')
                    ->form([
                        DatePicker::make('from_date')->label('From Date'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $data['from_date']
                            ? $query->whereDate('start_date', '>=', $data['from_date'])
                            : $query
                    ),

                Filter::make('location')
                    ->form([
                        TextInput::make('location')
                            ->label('Location')
                            ->placeholder('Search by location'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $data['location']
                            ? $query->where('location', 'like', "%{$data['location']}%")
                            : $query
                    ),

                Filter::make('type')
                    ->label('Type')
                    ->form([
                        Select::make('event_format')
                            ->label('Type')
                            ->placeholder('All types')
                            ->options([
                                'onsite'  => 'Onsite',
                                'virtual' => 'Virtual',
                            ]),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $data['event_format']
                            ? $query->where('event_format', $data['event_format'])
                            : $query
                    ),

            ], layout: FiltersLayout::AboveContent)
            ->actions((new EventRegistrationTableAction())->execute())
            ->bulkActions([])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([12, 24, 48, 'all'])
            ->defaultSort('start_date');
    }

    protected function getHeaderActions(): array
    {
        $isVolunteer = auth()->user()->hasActiveRole('Volunteer');

        $actions = [
            Action::make('List')
                ->label('List View')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(route('filament.admin.resources.events.list'))
                ->visible(! $isVolunteer),

            Action::make('Calendar')
                ->label('Calendar')
                ->icon('heroicon-o-calendar-date-range')
                ->color('gray')
                ->url(route('filament.admin.resources.events.calendar')),
        ];

        if (! $isVolunteer) {
            $actions[] = CreateAction::make()
                ->label('New Event')
                ->icon('heroicon-o-plus');
        }

        return $actions;
    }
}
