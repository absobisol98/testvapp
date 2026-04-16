<?php

namespace App\Filament\Resources;

use App\Actions\EventRegistrationTableAction;
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
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\Action;
use Tapp\FilamentGoogleAutocomplete\Forms\Components\GoogleAutocomplete;
use Filament\Infolists\Components\TextEntry;

class EventResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';

    protected static ?string $label = 'Opportunity';

    public static function getNavigationLabel(): string
    {
        if(auth()->user()->hasRole('Volunteer')){
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
                Forms\Components\Section::make('Event Banner')
                    ->schema([
                        Forms\Components\FileUpload::make('media_banner')
                            ->directory('event-banner-attachments')
                            ->maxFiles(1)
                            ->multiple()
                            ->label('')
                            ->openable()
                            ->downloadable()
                            ->helpertext('Upload an event banner (Recommended: 1200x500px, Max: 10MB). Drag & drop or click Browse.')
                            ->extraAttributes([
                                'title' => 'Upload event banner here'
                            ]),

                    ])
                    ->collapsible(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255)
                    ->extraAttributes([
                        'title' => 'Enter event title'
                    ]),

                Forms\Components\Textarea::make('description')
                    ->columnSpanFull()
                    ->required()
                    ->extraAttributes([
                        'title' => 'Enter event description'
                    ]),

                Forms\Components\Grid::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('program_id')
                            ->relationship('program', 'name')
                            ->required()
                            ->extraAttributes([
                                'title' => 'Please select program in the list'
                            ]),
                        Forms\Components\Select::make('point_of_contact_id')
                            ->label('HR Representative (Point-of-contact)')
                            ->required()
                            ->preload()
                            ->extraAttributes([
                                'title' => 'Please select representative in the list'
                            ])
                            ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                            ->relationship(
                                name: 'point_of_contact',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                            )
                            ->searchable(['firstname','middle_name', 'lastname']),
                        Forms\Components\Select::make('event_type_id')
                        ->relationship('event_type', 'name')
                        ->required()
                        ->extraAttributes([
                            'title' => 'Please select event in the list'
                        ])
                        ->reactive(),
                            ]),

                Forms\Components\Select::make('companies')
                    ->label('Companies')
                    ->required()
                    ->multiple()
                    ->options(function (){
                        $options = [];

                        $clusters = Cluster::with(['companies'])->get();

                        foreach ($clusters as $cluster) {
                            $options[$cluster->name] = collect($cluster->companies)->mapWithKeys(function ($company) {
                                return [$company->id => $company->name];
                            })->toArray();
                        }

                        return $options;
                    })
                    ->visible(fn ($get) => $get('event_type_id') == 3), // Hybrid

                Forms\Components\Fieldset::make('Schedule')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->label('Date')
                                    ->required()
                                    ->live()
                                    ->default(now())
                                    ->extraAttributes([
                                        'title' => 'Please select event date'
                                    ]),
                                Forms\Components\TimePicker::make('start_time')

                                    ->label('Start')
                                    ->default('08:00')
                                    ->seconds(false)
                                    ->extraAttributes([
                                        'title' => 'Event start schedule'
                                    ]),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('End')
                                    ->default('17:00')
                                    ->seconds(false)
                                    ->extraAttributes([
                                        'title' => 'Event end schedule'
                                    ]),
                            ]),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('recurrence_type_id')
                                    ->label('Recurrence type')
                                    ->options([
                                        1 => 'One Time',
                                        2 => 'Recurring',
                                    ])
                                    ->required()
                                    ->live()
                                    ->default(1)
                                    ->extraAttributes([
                                        'title' => 'Select event recurrence type'
                                    ]),

                                Forms\Components\Select::make('frequency')
                                    ->label('Frequency')
                                    ->options([
                                        'daily' => 'Daily',
                                        'weekly' => 'Weekly',
                                        'monthly' => 'Monthly',
                                        'yearly' => 'Yearly',
                                    ])
                                    ->required()
                                    ->live()
                                    ->visible(fn ($get) =>
                                        $get('recurrence_type_id') == 2
                                    ),

                                Forms\Components\DatePicker::make('repeat_until')
                                    ->label('Repeat Until')
                                    ->minDate(fn ($get) => $get('date'))
                                    ->maxDate(now()->addYears(5))
                                    ->default(now())
                                    ->required()
                                    ->visible(fn ($get) =>
                                        $get('recurrence_type_id') == 2
                                    ),

//                                        Forms\Components\CheckboxList::make('selected_days')
//                                            ->label('On These Days')
//                                            ->default([\Carbon\Carbon::now()->dayOfWeek]) // Get current day index
//                                            ->options([
//                                                0 => 'Sunday',
//                                                1 => 'Monday',
//                                                2 => 'Tuesday',
//                                                3 => 'Wednesday',
//                                                4 => 'Thursday',
//                                                5 => 'Friday',
//                                                6 => 'Saturday',
//                                            ])
//                                            ->columnSpanFull()
//                                            ->columns(7)
//                                            ->visible(fn ($get) =>
//                                                $get('recurrence_type_id') == 2 &&
//                                                $get('frequency') == 'weekly'
//                                            ),
//
//                                        Forms\Components\Select::make('monthly_days')
//                                            ->label('On These Days')
//                                            ->multiple()
//                                            ->options(array_combine(
//                                                range(1, 31),
//                                                range(1, 31)
//                                            ))
//                                            ->visible(fn ($get) =>
//                                                $get('recurrence_type_id') == 2 &&
//                                                $get('frequency') == 'monthly'
//                                            ),
                            ]),
                            GoogleAutocomplete::make('google_search')
                            ->label('Search Location')
                            ->extraAttributes([
                                'title' => 'Search event location'
                            ])
                            ->countries([
                                'PH',
                            ])
                            ->withFields([
                                Forms\Components\TextInput::make('location')
                                    ->extraInputAttributes([
                                        'data-google-field' => '{formatted_address}',
                                    ])->columnSpan('full')
                                    ->readOnly(),

                            ]),

                            Forms\Components\TagsInput::make('tags')
                            ->suggestions(fn() => TagsEvent::orderBy('id')->pluck('name')->toArray())
                            ->extraAttributes([
                                'title' => 'Input event tags'
                            ]),

                    ]),


                Forms\Components\Repeater::make('slots')
                    ->required()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([

                                Forms\Components\TextInput::make('shift_name')
                                    ->required()
                                    ->minValue(0)
                                    ->label('Shift name')
                                    ->extraAttributes([
                                        'title' => 'Enter shift name'
                                    ]),
                                Forms\Components\TextInput::make('total_slots')
                                    ->required()
                                    ->label('Number of Volunteer')
                                    ->minValue(0)
                                    ->numeric()
                                    ->extraAttributes([
                                        'title' => 'Enter number of volunteer'
                                    ]),
                            ]),
                        Forms\Components\Select::make('slot_type_id')
                            ->label('Type')
                            ->default(1)
                            ->required()
                            ->options(EventSlotType::orderBy('id')->pluck('name', 'id')->toArray()),
                        Forms\Components\TimePicker::make('start_time')
                            ->required()
                            ->label('Start time')
                            ->default('8:00')
                            ->seconds(false),
                        Forms\Components\TimePicker::make('end_time')
                            ->required()
                            ->label('End time')
                            ->default('11:00')
                            ->seconds(false),
                        Forms\Components\Textarea::make('responsibilities')
                            ->required()
                            ->columnSpanFull()
                            ->extraAttributes([
                                'title' => 'Enter volunteer responsibilities'
                            ]),
                    ])
                    ->columnSpanFull()
                    ->columns(3),


                Forms\Components\Section::make('')
                    ->schema([
                        Forms\Components\Select::make('facilitators')
                        ->preload()
                        ->multiple()
                        ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                        ->relationship(
                            name: 'facilitators',
                            modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                        )
                        ->extraAttributes([
                            'title' => 'Please select facilitator in the list'
                        ])
                        ->searchable(['firstname','middle_name', 'lastname']),

                        Forms\Components\Toggle::make('attachment_required')
                        ->reactive()
                        ->extraAttributes([
                            'title' => 'toggle button if attachment is required'
                        ]),
                    ])->columnSpan(1),

                    Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Radio::make('approval_type')
                        ->options([
                            'Automatic' => 'Automatic',
                            'Requires Approval' => 'Requires Facilitator Approval',
                        ])
                        ->default(2)
                        ->required(),
                    ])->columnSpan(1),

                Repeater::make('other_fields')
                    ->relationship()
                    ->columnSpanFull()
                    ->defaultItems(0)
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('label')->label('Field Label')->required(),
                            TextInput::make('text')->required('Value')
                        ]),
                    ]),

                Forms\Components\Section::make('Attachments')
                    ->schema([
                        Forms\Components\FileUpload::make('media')
                            ->directory('event-attachments')
                            ->multiple()
                            ->maxFiles(5)
                            ->label('')
                            ->openable()
                            ->downloadable()
                            ->extraAttributes([
                                'title' => 'Upload attachments here'
                            ]),
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
                            ->helpertext('Upload an event certificate background (Recommended: 1200x500px, Max: 10MB). Drag & drop or click Browse.')
                            ->extraAttributes([
                                'title' => 'Upload certificate background here'
                            ]),
                    ])
                    ->collapsible(),



//                        Forms\Components\TagsInput::make('required_document_types')
//                            ->visible(fn ($get) => $get('requires_documents')),
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
                Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->getStateUsing(function (Event $record) {
                    return $record->getStatus()['text'];
                })
                ->badge()
                ->color(fn (Event $record) => $record->getStatus()['color'])
                ->icon(fn (Event $record) => $record->getStatus()['icon'])
                ->searchable(false)
                ->sortable(false),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
//                Tables\Columns\TextColumn::make('event_type_id')
//                    ->numeric()
//                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('recurrence_type_id')
                    ->label('Recurrence type')
                    ->formatStateUsing(function (Event $record,string $state){
                        if($state == 2){
                            return ucfirst($record->frequency);

                        }
                        return $record->event_recurrence_type->name;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('point_of_contact_id')
                    ->label('HR Representative (Point-of-contact)')
                    ->formatStateUsing(function (Event $record,string $state){
                        $user = User::find($state);
                        return $user->firstname.' '.$user->lastname;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('program.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('sign_up_approval_required')
                    ->label('Approval Required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('attachment_required')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->visible(auth()->user()->can('set_featured_event'))
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_by_user.firstname')
                    ->label('Opportunity By')
                    ->searchable(),
            ])
            ->filters([
                Filter::make('event_status')
                    ->label('Event Status')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('')
                            ->options([
                                'all' => 'All Events',
                                'active' => 'Active Events',
                                'upcoming' => 'Upcoming Events',
                                'finished' => 'Finished Events',
                                'joined' => 'My Registered Events',
                            ])
                            ->default('all')
                            ->selectablePlaceholder(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $now = now();

                        return match($data['status'] ?? 'all') {
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


                Filter::make('tags')
                    ->label('Tags')
                    ->form([
                        Forms\Components\Select::make('tags')
                            ->selectablePlaceholder(false)
                            ->label('')
                            ->placeholder('All Tags')
                            ->selectablePlaceholder(false)
                            ->multiple()
                            ->options( function(){
                                $option = array();
                                foreach(TagsEvent::orderBy('name')->get() as $tag){
                                    $option[$tag->id] = $tag->name;
                                }
                                return $option;
                            } ),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if(!empty($data['tags'])) {
                            $query->whereHas('tags', function (Builder $query) use($data) {
                                $query->whereIn('tag_id', $data['tags']);
                            });
                            return $query;
                        }
                        // dd($query->get());
                        return $query;
                    }),
            ],layout: FiltersLayout::AboveContent)
            ->actions((new EventRegistrationTableAction())->execute())
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date');
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
