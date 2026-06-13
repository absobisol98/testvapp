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
use App\Models\EventTag;
use App\Models\EventType;
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
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Maatwebsite\Excel\Facades\Excel;

class EventResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';

    protected static ?string $label = 'Opportunity';

    public static function getNavigationLabel(): string
    {
        if (auth()->user()->hasActiveRole('Volunteer')) {
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                                            ->label('Start Date & Time')
                                            ->required()
                                            ->live()
                                            ->seconds(false)
                                            ->default(now()->setTime(8, 0))
                                            ->minDate(now()->startOfDay()),
                                        Forms\Components\DateTimePicker::make('end_date')
                                            ->label('End Date & Time')
                                            ->required()
                                            ->seconds(false)
                                            ->default(now()->setTime(17, 0))
                                            ->afterOrEqual('start_date'),
                                    ]),

                                Forms\Components\DateTimePicker::make('registration_end_date')
                                    ->label('Registration Deadline')
                                    ->seconds(false)
                                    ->helperText('Leave empty for no registration deadline.')
                                    ->beforeOrEqual('start_date'),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('recurrence_type_id')
                                            ->label('Recurrence Type')
                                            ->options([
                                                1 => 'One Time',
                                                2 => 'Recurring',
                                            ])
                                            ->required()
                                            ->live()
                                            ->default(1),
                                        Forms\Components\Select::make('frequency')
                                            ->label('Frequency')
                                            ->options([
                                                'daily'   => 'Daily',
                                                'weekly'  => 'Weekly',
                                                'monthly' => 'Monthly',
                                                'yearly'  => 'Yearly',
                                            ])
                                            ->required()
                                            ->live()
                                            ->visible(fn ($get) => $get('recurrence_type_id') == 2),
                                        Forms\Components\DatePicker::make('repeat_until')
                                            ->label('Repeat Until')
                                            ->minDate(fn ($get) => $get('start_date'))
                                            ->maxDate(now()->addYears(5))
                                            ->default(now())
                                            ->required()
                                            ->visible(fn ($get) => $get('recurrence_type_id') == 2),
                                    ]),

                                Forms\Components\CheckboxList::make('selected_days')
                                    ->label('Repeat on These Days')
                                    ->default([\Carbon\Carbon::now()->dayOfWeek])
                                    ->options([
                                        0 => 'Sunday',
                                        1 => 'Monday',
                                        2 => 'Tuesday',
                                        3 => 'Wednesday',
                                        4 => 'Thursday',
                                        5 => 'Friday',
                                        6 => 'Saturday',
                                    ])
                                    ->columnSpanFull()
                                    ->columns(7)
                                    ->visible(fn ($get) =>
                                        $get('recurrence_type_id') == 2 &&
                                        $get('frequency') == 'weekly'
                                    ),
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
                                            ->live()
                                            ->inline(),

                                        // ── Onsite fields ──
                                        Forms\Components\TextInput::make('location')
                                            ->label('Location / Address')
                                            ->placeholder('Enter the full address')
                                            ->columnSpanFull()
                                            ->visible(fn ($get) => $get('event_format') !== 'virtual'),

                                        Forms\Components\TextInput::make('location_details')
                                            ->label('Secondary Location Details')
                                            ->placeholder('Room number, building name, or landmark')
                                            ->visible(fn ($get) => $get('event_format') !== 'virtual'),

                                        // ── Virtual fields ──
                                        Forms\Components\TextInput::make('meeting_link')
                                            ->label('Meeting Link / URL')
                                            ->url()
                                            ->placeholder('https://meet.google.com/...')
                                            ->helperText('Shown to volunteers once their registration is approved.')
                                            ->visible(fn ($get) => $get('event_format') === 'virtual'),
                                    ]),
                            ]),

                        // ── Tab 4: Shifts ────────────────────────────────────────
                        Forms\Components\Tabs\Tab::make('Shifts')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Forms\Components\Repeater::make('slots')
                                    ->label('Slots')
                                    ->required()
                                    ->cloneable()
                                    ->schema([
                                        // Row 1: Shift name | Number of volunteers
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('shift_name')
                                                    ->required()
                                                    ->label('Shift Name'),
                                                Forms\Components\TextInput::make('total_slots')
                                                    ->required()
                                                    ->label('Number of Volunteers')
                                                    ->minValue(0)
                                                    ->numeric(),
                                            ]),

                                        // Row 2: Type | Shift Date | Start Time | End Date | End Time
                                        Forms\Components\Grid::make(5)
                                            ->schema([
                                                Forms\Components\Select::make('slot_type_id')
                                                    ->label('Type')
                                                    ->default(1)
                                                    ->required()
                                                    ->options(EventSlotType::orderBy('id')->pluck('name', 'id')->toArray()),
                                                Forms\Components\DatePicker::make('shift_date')
                                                    ->label('Shift Date')
                                                    ->helperText('Must be within the event date range.'),
                                                Forms\Components\TimePicker::make('start_time')
                                                    ->required()
                                                    ->label('Start Time')
                                                    ->default('08:00')
                                                    ->seconds(false),
                                                Forms\Components\DatePicker::make('shift_end_date')
                                                    ->label('End Date')
                                                    ->helperText('Leave blank if same day. Set for overnight or multi-day shifts.'),
                                                Forms\Components\TimePicker::make('end_time')
                                                    ->required()
                                                    ->label('End Time')
                                                    ->default('17:00')
                                                    ->seconds(false),
                                            ]),

                                        // Row 3: Slot Type override (Inherit / Onsite / Virtual)
                                        Forms\Components\Select::make('slot_format')
                                            ->label('Slot Type (override)')
                                            ->options([
                                                ''        => 'Inherit from event',
                                                'onsite'  => 'Onsite',
                                                'virtual' => 'Virtual',
                                            ])
                                            ->default('')
                                            ->live()
                                            ->helperText("Leave blank to use the event's type. Set only to override for this specific shift."),

                                        // Row 4: Per-shift meeting link (virtual override only)
                                        Forms\Components\TextInput::make('meeting_link')
                                            ->label('Shift Meeting Link')
                                            ->url()
                                            ->placeholder('https://meet.google.com/...')
                                            ->helperText('Optional. Overrides the event-level meeting link for this shift.')
                                            ->visible(fn ($get) => $get('slot_format') === 'virtual'),

                                        // Row 5: Responsibilities
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

                                Forms\Components\Section::make('Attachments')
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


    public static function getWidgets(): array
    {
        return [
            EventResource\Widgets\EventsToApprove::class,
        ];
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // 1 — Status badge (computed from dates + is_published)
                Tables\Columns\TextColumn::make('computed_status')
                    ->label('Status')
                    ->badge()
                    ->getStateUsing(function (Event $record): string {
                        if (! $record->is_published) return 'Draft';
                        if ($record->start_date > now()) return 'Upcoming';
                        if ($record->end_date && $record->end_date >= now()) return 'Ongoing';
                        return 'Completed';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Draft'     => 'gray',
                        'Upcoming'  => 'info',
                        'Ongoing'   => 'success',
                        'Completed' => 'warning',
                        default     => 'gray',
                    }),

                // 2 — Title
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->wrap(),

                // 3 — Date
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                // 4 — Recurrence Type
                Tables\Columns\TextColumn::make('recurrence_type_id')
                    ->label('Recurrence')
                    ->formatStateUsing(function (Event $record, string $state): string {
                        if ($state == 2) return ucfirst($record->frequency);
                        return $record->event_recurrence_type->name;
                    })
                    ->sortable(),

                // 5 — Program
                Tables\Columns\TextColumn::make('program.name')
                    ->label('Program')
                    ->sortable(),

                // 6 — Type badge (Onsite / Virtual / Hybrid)
                Tables\Columns\TextColumn::make('event_format')
                    ->label('Type')
                    ->badge()
                    ->getStateUsing(function (Event $record): string {
                        // Auto-detect hybrid: any slot has a different format from the event level
                        $eventFormat = $record->event_format ?? 'onsite';
                        $hasConflict = $record->slots->contains(fn ($slot) =>
                            $slot->slot_format && $slot->slot_format !== $eventFormat
                        );
                        if ($hasConflict) return 'Hybrid';
                        return ucfirst($eventFormat);
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Virtual' => 'info',
                        'Hybrid'  => 'warning',
                        default   => 'success', // Onsite
                    }),
            ])
            ->filters([
                // Opportunity Status
                Filter::make('opportunity_status')
                    ->label('Status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('Opportunity Status')
                            ->placeholder('All')
                            ->options([
                                'draft'     => 'Draft',
                                'upcoming'  => 'Upcoming',
                                'ongoing'   => 'Ongoing',
                                'completed' => 'Completed',
                                'joined'    => 'Joined (mine)',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['status'] ?? null) {
                            'draft'     => $query->where('is_published', false),
                            'upcoming'  => $query->where('is_published', true)->where('start_date', '>', now()),
                            'ongoing'   => $query->where('is_published', true)->where('start_date', '<=', now())->where('end_date', '>=', now()),
                            'completed' => $query->where('is_published', true)->where('end_date', '<', now()),
                            'joined'    => $query->whereHas('attendees', fn ($q) => $q->where('attendee_id', auth()->id())),
                            default     => $query,
                        };
                    }),

                // Tags
                Filter::make('tags')
                    ->label('Tags')
                    ->form([
                        Forms\Components\Select::make('tags')
                            ->label('Tags')
                            ->placeholder('All Tags')
                            ->multiple()
                            ->options(fn () => TagsEvent::orderBy('name')->pluck('name', 'id')->toArray()),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! empty($data['tags'])) {
                            $query->whereHas('tags', fn ($q) => $q->whereIn('tag_id', $data['tags']));
                        }
                        return $query;
                    }),

                // Program
                Tables\Filters\SelectFilter::make('program_id')
                    ->label('Program')
                    ->relationship('program', 'name')
                    ->placeholder('All Programs'),

                // Opportunity Type (Virtual / Onsite)
                Tables\Filters\SelectFilter::make('event_format')
                    ->label('Type')
                    ->options([
                        'onsite'  => 'Onsite',
                        'virtual' => 'Virtual',
                    ])
                    ->placeholder('All Types')
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) return $query;
                        if ($data['value'] === 'onsite') {
                            // Treat null event_format as onsite (legacy records)
                            return $query->where(fn ($q) =>
                                $q->where('event_format', 'onsite')->orWhereNull('event_format')
                            );
                        }
                        return $query->where('event_format', $data['value']);
                    }),

            ], layout: FiltersLayout::AboveContent)
            ->actions((new EventRegistrationTableAction())->execute())
            ->headerActions([
                Tables\Actions\Action::make('export_registrants')
                    ->label('Export Registrants')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn () => auth()->user()->isAdminRole())
                    ->action(fn () => Excel::download(new EventRegistrantsExport(), 'event-registrants-' . now()->format('Y-m-d') . '.xlsx')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('export_registrants')
                        ->label('Export Registrants')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->visible(fn () => auth()->user()->isAdminRole())
                        ->action(fn (\Illuminate\Support\Collection $records) =>
                            Excel::download(
                                new EventRegistrantsExport($records->pluck('id')->toArray()),
                                'event-registrants-selected-' . now()->format('Y-m-d') . '.xlsx'
                            )
                        ),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
