<?php

namespace App\Filament\Resources;

use App\Actions\EventRegistrationTableAction;
use App\Exports\EventRegistrantsExport;
use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Filament\Resources\EventResource\RelationManagers\AttendeesRelationManager;
use App\Models\Cluster;
use App\Models\Event;
use App\Models\EventSlotType;
use App\Models\TagsEvent;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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

                    // ── Tab 1: Basic Info ────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Basic Info')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->columnSpanFull()
                                ->maxLength(255),

                            Forms\Components\RichEditor::make('description')
                                ->columnSpanFull()
                                ->required(),

                            Forms\Components\Grid::make(3)
                                ->schema([
                                    Forms\Components\Select::make('program_id')
                                        ->relationship('program', 'name')
                                        ->required(),
                                    Forms\Components\Select::make('point_of_contact_id')
                                        ->label('HR Representative (Point-of-contact)')
                                        ->required()
                                        ->preload()
                                        ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                                        ->relationship(
                                            name: 'point_of_contact',
                                            modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                                        )
                                        ->searchable(['firstname', 'middle_name', 'lastname']),
                                    Forms\Components\Select::make('event_type_id')
                                        ->label('Audience Type')
                                        ->relationship('event_type', 'name')
                                        ->required()
                                        ->live(),
                                ]),


                            Forms\Components\Select::make('companies')
                                ->label('Companies')
                                ->required()
                                ->multiple()
                                ->options(function () {
                                    $options = [];
                                    $clusters = Cluster::with(['companies'])->get();
                                    foreach ($clusters as $cluster) {
                                        $options[$cluster->name] = collect($cluster->companies)
                                            ->mapWithKeys(fn ($company) => [$company->id => $company->name])
                                            ->toArray();
                                    }
                                    return $options;
                                })
                                ->visible(fn ($get) => $get('event_type_id') == 3),

                            Forms\Components\TagsInput::make('tags')
                                ->suggestions(fn () => TagsEvent::orderBy('id')->pluck('name')->toArray()),

                            Forms\Components\Toggle::make('is_public')
                                ->label('Open to All Business Units')
                                ->helperText('Allows volunteers from other Business Units to see and join this opportunity.')
                                ->visible(fn () => auth()->user()->isAdminRole()),
                        ]),

                    // ── Tab 2: Schedule ──────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Schedule')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\DateTimePicker::make('start_date')
                                        ->label('Event Start')
                                        ->helperText('The first day of the opportunity.')
                                        ->required()
                                        ->live()
                                        ->seconds(false)
                                        ->default(now()->setTime(8, 0))
                                        ->minDate(now()->startOfDay()),
                                    Forms\Components\DateTimePicker::make('end_date')
                                        ->label('Event End')
                                        ->helperText('The last day of the opportunity. All shift dates must fall within this range.')
                                        ->required()
                                        ->live()
                                        ->seconds(false)
                                        ->default(now()->setTime(17, 0))
                                        ->afterOrEqual('start_date'),
                                ]),

                        ]),

                    // ── Tab 3: Location ──────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Location')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Forms\Components\Section::make('Location & Type')
                                ->schema([
                                    Forms\Components\Radio::make('event_format')
                                        ->label('Opportunity Type')
                                        ->options([
                                            'onsite'  => 'Onsite',
                                            'virtual' => 'Virtual',
                                        ])
                                        ->default('onsite')
                                        ->required()
                                        ->inline()
                                        ->live(),

                                    Forms\Components\TextInput::make('meeting_link')
                                        ->label('Meeting Link / URL')
                                        ->url()
                                        ->placeholder('https://meet.google.com/...')
                                        ->helperText('Shown to volunteers once registered or approved.')
                                        ->visible(fn ($get) => $get('event_format') === 'virtual')
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('location')
                                        ->label('Location (Address)')
                                        ->placeholder('Start typing an address...')
                                        ->visible(fn ($get) => $get('event_format') !== 'virtual')
                                        ->columnSpanFull(),

                                    Forms\Components\TextInput::make('location_details')
                                        ->label('Building / Room / Landmark')
                                        ->placeholder('e.g. 3rd Floor, Room 301, near main lobby')
                                        ->visible(fn ($get) => $get('event_format') !== 'virtual')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ── Tab 4: Shifts ────────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Shifts')
                        ->icon('heroicon-o-clock')
                        ->schema([
                            Forms\Components\Repeater::make('slots')
                                ->required()
                                ->cloneable()
                                ->schema([
                                    // Row 1: Shift Name + Number of Volunteers
                                    Forms\Components\Grid::make(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('shift_name')
                                                ->label('Shift Name')
                                                ->required(),
                                            Forms\Components\TextInput::make('total_slots')
                                                ->label('Number of Volunteers')
                                                ->required()
                                                ->numeric()
                                                ->minValue(0),
                                        ]),

                                    // Row 2: Type (full width)
                                    Forms\Components\Select::make('slot_type_id')
                                        ->label('Type')
                                        ->required()
                                        ->default(1)
                                        ->options(EventSlotType::orderBy('id')->pluck('name', 'id')->toArray())
                                        ->columnSpanFull(),

                                    // Row 3: Shift Date | Start Time | End Date | End Time
                                    Forms\Components\Grid::make(4)
                                        ->schema([
                                            Forms\Components\DatePicker::make('shift_date')
                                                ->label('Shift Date')
                                                ->required()
                                                ->minDate(fn ($get) => $get('../../start_date') ? \Carbon\Carbon::parse($get('../../start_date'))->startOfDay() : null)
                                                ->maxDate(fn ($get) => $get('../../end_date') ? \Carbon\Carbon::parse($get('../../end_date'))->endOfDay() : null)
                                                ->helperText('Must be within the event date range.'),
                                            Forms\Components\TimePicker::make('start_time')
                                                ->label('Start Time')
                                                ->required()
                                                ->default('08:00')
                                                ->seconds(false),
                                            Forms\Components\DatePicker::make('shift_end_date')
                                                ->label('End Date')
                                                ->minDate(fn ($get) => $get('shift_date') ? \Carbon\Carbon::parse($get('shift_date'))->startOfDay() : null)
                                                ->maxDate(fn ($get) => $get('../../end_date') ? \Carbon\Carbon::parse($get('../../end_date'))->endOfDay() : null)
                                                ->helperText('Leave blank if same day. Set for overnight or multi-day shifts.'),
                                            Forms\Components\TimePicker::make('end_time')
                                                ->label('End Time')
                                                ->required()
                                                ->default('17:00')
                                                ->seconds(false)
                                                ->after('start_time')
                                                ->helperText('Must be after start time.'),
                                        ]),

                                    // Row 4: Slot Type override (full width)
                                    Forms\Components\Select::make('slot_format')
                                        ->label('Slot Type (override)')
                                        ->options([
                                            '' => 'Inherit from event',
                                            'onsite' => 'Onsite',
                                            'virtual' => 'Virtual',
                                        ])
                                        ->default('')
                                        ->live()
                                        ->helperText('Leave blank to use the event\'s type. Set only to override for this specific shift.')
                                        ->columnSpanFull(),

                                    // Shift-level meeting link — only when slot overrides to virtual
                                    Forms\Components\TextInput::make('meeting_link')
                                        ->label('Shift Meeting Link')
                                        ->url()
                                        ->placeholder('https://meet.google.com/...')
                                        ->helperText('Optional. Overrides the event-level meeting link for this shift.')
                                        ->visible(fn ($get) => $get('slot_format') === 'virtual')
                                        ->columnSpanFull(),

                                    // Row 5: Key Responsibilities (full width)
                                    Forms\Components\Textarea::make('responsibilities')
                                        ->label('Key Responsibilities')
                                        ->required()
                                        ->columnSpanFull(),
                                ])
                                ->columnSpanFull(),
                        ]),

                    // ── Tab 5: Settings ──────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Settings')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Select::make('facilitators')
                                ->preload()
                                ->multiple()
                                ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                                ->relationship(
                                    name: 'facilitators',
                                    modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                                )
                                ->searchable(['firstname', 'middle_name', 'lastname']),

                            Forms\Components\Radio::make('approval_type')
                                ->options([
                                    'Automatic'         => 'Automatic',
                                    'Requires Approval' => 'Requires Facilitator Approval',
                                ])
                                ->default('Requires Approval')
                                ->required(),

                            Forms\Components\Toggle::make('attachment_required')
                                ->reactive(),

                            Repeater::make('other_fields')
                                ->relationship()
                                ->columnSpanFull()
                                ->defaultItems(0)
                                ->schema([
                                    Grid::make(2)->schema([
                                        TextInput::make('label')->label('Field Label')->required(),
                                        TextInput::make('text')->required(),
                                    ]),
                                ]),
                        ]),

                    // ── Tab 6: Media ─────────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('Media')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make('Event Banner')
                                ->schema([
                                    Forms\Components\FileUpload::make('media_banner')
                                        ->directory('event-banner-attachments')
                                        ->maxFiles(1)
                                        ->multiple()
                                        ->label('')
                                        ->openable()
                                        ->downloadable()
                                        ->helperText('Recommended: 1200x500px, Max: 10MB.'),
                                ])
                                ->collapsible(),

                            Forms\Components\Section::make('Event Attachments')
                                ->schema([
                                    Forms\Components\FileUpload::make('media')
                                        ->directory('event-attachments')
                                        ->multiple()
                                        ->maxFiles(5)
                                        ->label('')
                                        ->openable()
                                        ->downloadable(),
                                ])
                                ->collapsible(),

                            Forms\Components\Section::make('Event Certificate')
                                ->schema([
                                    Forms\Components\FileUpload::make('certificate_background')
                                        ->directory('certificate_background')
                                        ->multiple()
                                        ->maxFiles(1)
                                        ->label('')
                                        ->openable()
                                        ->downloadable()
                                        ->helperText('Recommended: 1200x500px, Max: 10MB.'),
                                ])
                                ->collapsible(),
                        ]),

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