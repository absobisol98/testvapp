<?php

namespace App\Filament\Resources;

use App\Actions\EventRegistrationTableAction;
use App\Exports\EventRegistrantsExport;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Filament\Resources\EventResource\RelationManagers\AttendeesRelationManager;
use App\Models\Event;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;

class EventResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';

    protected static ?string $label = 'Opportunity';

    public static function getNavigationIcon(): string
    {
        if (auth()->check() && auth()->user()->hasActiveRole('Volunteer')) {
            return 'heroicon-o-hand-raised';
        }
        return 'heroicon-s-calendar-date-range';
    }

    public static function getNavigationLabel(): string
    {
        if (auth()->check() && auth()->user()->hasActiveRole('Volunteer')) {
            return 'My Volunteer Opportunities';
        }
        return 'Volunteer Opportunities';
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish',
            'export',
            'manage_attendees',
            'manage_registrations',
            'set_featured',
            'register',
        ];
    }

    public static function canCreate(): bool
    {
        return ! auth()->user()->hasActiveRole('Volunteer');
    }

    public static function canView(Model $record): bool
    {
        return true;
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        if ($user->hasActiveRole('Volunteer')) {
            return false;
        }

        if ($user->hasActiveRole('Facilitator')) {
            return $record->facilitators()
                ->where('facilitator_id', $user->id)
                ->exists();
        }

        return parent::canEdit($record);
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        if ($user->hasActiveRole('Volunteer') || $user->hasActiveRole('Facilitator')) {
            return false;
        }

        return parent::canDelete($record);
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();

        if ($user->hasActiveRole('Volunteer') || $user->hasActiveRole('Facilitator')) {
            return false;
        }

        return parent::canDeleteAny();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Event')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Basic Info')->schema([]),
                    Forms\Components\Tabs\Tab::make('Schedule')->schema([]),
                    Forms\Components\Tabs\Tab::make('Location')->schema([]),
                    Forms\Components\Tabs\Tab::make('Shifts')->schema([]),
                    Forms\Components\Tabs\Tab::make('Settings')->schema([]),
                    Forms\Components\Tabs\Tab::make('Media')->schema([]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // ✅ STATUS BADGE (already safe from Step 2)
                Tables\Columns\TextColumn::make('computed_status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Event $record): string {

                        $now = now();

                        if (! $record->is_published) {
                            return 'Draft';
                        }

                        if (! $record->start_date) {
                            return 'Upcoming';
                        }

                        if ($now->lt($record->start_date)) {
                            return 'Upcoming';
                        }

                        if (
                            $now->gte($record->start_date) &&
                            (! $record->end_date || $now->lte($record->end_date))
                        ) {
                            return 'Ongoing';
                        }

                        if ($record->end_date && $now->gt($record->end_date)) {
                            return 'Completed';
                        }

                        return 'Ongoing';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Draft'     => 'gray',
                        'Upcoming'  => 'info',
                        'Ongoing'   => 'success',
                        'Completed' => 'warning',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('title')->searchable()->wrap(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recurrence_type_id')
                    ->label('Recurrence')
                    ->formatStateUsing(function (Event $record, string $state): string {
                        if ($state == 2) {
                            return ucfirst($record->frequency);
                        }
                        return $record->event_recurrence_type->name;
                    }),

                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->sortable(),

                // ✅ FIXED TYPE BADGE (Hybrid-safe + production stable)
                Tables\Columns\TextColumn::make('event_format')
                    ->label('Type')
                    ->badge()
                    ->getStateUsing(function (Event $record): string {

                        $eventFormat = strtolower($record->event_format ?? 'onsite');

                        // Safe load (prevents lazy loading issues)
                        $slots = $record->relationLoaded('slots')
                            ? $record->slots
                            : $record->slots()->get();

                        if ($slots->isEmpty()) {
                            return ucfirst($eventFormat);
                        }

                        $slotFormats = $slots
                            ->pluck('slot_format')
                            ->filter()
                            ->map(fn ($f) => strtolower($f))
                            ->unique()
                            ->values();

                        if ($slotFormats->isEmpty()) {
                            return ucfirst($eventFormat);
                        }

                        if (
                            $slotFormats->count() > 1 ||
                            $slotFormats->first() !== $eventFormat
                        ) {
                            return 'Hybrid';
                        }

                        return ucfirst($slotFormats->first());
                    })
                    ->color(fn (string $state): string => match (strtolower($state)) {
                        'virtual' => 'info',
                        'onsite'  => 'success',
                        'hybrid'  => 'warning',
                        default   => 'gray',
                    }),

            ])

            ->filters([])
            ->actions((new EventRegistrationTableAction())->execute())
            ->headerActions([
                Tables\Actions\Action::make('export_registrants')
                    ->label('Export Registrants')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn () => auth()->user()->isAdminRole())
                    ->action(fn () =>
                        Excel::download(
                            new EventRegistrantsExport(),
                            'event-registrants-' . now()->format('Y-m-d') . '.xlsx'
                        )
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('export_registrants')
                        ->label('Export Registrants')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn () => auth()->user()->isAdminRole())
                        ->action(fn ($records) =>
                            Excel::download(
                                new EventRegistrantsExport($records->pluck('id')->toArray()),
                                'event-registrants-selected-' . now()->format('Y-m-d') . '.xlsx'
                            )
                        ),
                ]),
            ])
            ->defaultSort('start_date');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RegistrationsRelationManager::class,
            AttendeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'list' => Pages\ListEvents::route('/list'),
            'calendar' => Pages\Calendar::route('/calendar'),
            'index' => Pages\Thumbnail::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\EventPage::route('/view/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'manage-volunteers' => Pages\ManageVolunteers::route('/{record}/manage-volunteers'),
        ];
    }
}