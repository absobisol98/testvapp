<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Actions\EventRegistrationButtonVisibilityAction;
use App\Actions\EventRegistrationTableAction;
use App\Filament\Resources\EventResource;
use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\Layout\View;
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

            ])
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
