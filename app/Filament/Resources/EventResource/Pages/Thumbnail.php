<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventRegistrationTableAction;
use App\Filament\Resources\EventResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class Thumbnail extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected static ?string $title = 'Thumbnail';

    protected static string $view = 'filament.resources.event-resource.pages.thumbnail';

    protected function getTableQuery(): ?Builder
    {
        $events = (new (static::$resource::getModel()))->where('start_date', '>', now()->subDay());

        return $events;
    }


    public function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                View::make('filament.tables.columns.event-thumbnail'),
            ])
            ->filters([
                Filter::make('status')
                    ->label('')
                    ->form([
                        Select::make('status')
                            ->label('')
                            ->selectablePlaceholder(false)
                            ->default('all')
                            ->options([
                                'all' => 'All Events',
                                'joined' => 'Joined Events',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        if($data['status'] === 'joined') {
                            $query->whereHas('attendees', function (Builder $query) {
                                $query->where('attendee_id', auth()->id());
                            });
                        }

                        return $query;
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
                ->url(route('filament.admin.resources.events.index')),
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
