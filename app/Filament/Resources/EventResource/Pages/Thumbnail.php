<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventRegistrationTableAction;
use App\Actions\EventsGetTableQueryAction;
use App\Filament\Resources\EventResource;
use App\Models\Company;
use App\Models\Event;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Thumbnail extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected static ?string $title = 'Thumbnail';

    protected static string $view = 'filament.resources.event-resource.pages.thumbnail';

    protected function getTableQuery(): ?Builder
    {
        $events = (new EventsGetTableQueryAction())->execute(auth()->user());

        return $events;
    }


    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                View::make('filament.tables.columns.event-thumbnail'),
            ])
            ->filters([
                // Replace existing status filter with the same one from EventResource
                Filter::make('event_status')
                    ->label('Category')
                    ->form([
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
                            ->selectablePlaceholder(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $now = now();

                        return match($data['status'] ?? 'upcoming') {
                            'active' => $query->where('start_date', '<=', $now)
                                ->where('end_date', '>=', $now),
                            'upcoming' => $query->where('start_date', '>', $now),
                            'finished' => $query->where('end_date', '<', $now),
                            'joined' => $query->whereHas('attendees', function (Builder $query) {
                                $query->where('attendee_id', auth()->id());
                            }),
                            default => $query,
                        };
                    }),

                // Keep the rest of your filters
                Filter::make('search_title')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('search_title')
                            ->label('Title')
                            ->placeholder('Search by event title'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $data['search_title'] ?
                            $query->where('title', 'like', "%{$data['search_title']}%") :
                            $query;
                    }),

                Filter::make('date_range')
                    ->label('Date')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from_date')
                            ->label('Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['from_date'],
                            fn (Builder $query) => $query->whereDate('start_date', '>=', $data['from_date'])
                        );
                    }),

                Filter::make('location')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->placeholder('Search by location'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $data['location'] ?
                            $query->where('location', 'like', "%{$data['location']}%") :
                            $query;
                    }),
            ],layout: FiltersLayout::AboveContent)
            ->actions((new EventRegistrationTableAction())->execute())
            ->bulkActions([
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginated([12, 24, 48, 120, 'all'])
            ->defaultSort('start_date');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('List')
                ->icon('heroicon-o-list-bullet')
                ->url(route('filament.admin.resources.events.list')),
            Action::make('Calendar')
                ->icon('heroicon-o-calendar-date-range')
                ->url(route('filament.admin.resources.events.calendar')),
            CreateAction::make()
                ->label('Event')
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.events.create')),
        ];
    }
}
