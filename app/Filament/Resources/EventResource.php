<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Cluster;
use App\Models\Event;
use App\Models\EventTag;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-s-calendar-date-range';

    protected static ?string $navigationLabel = 'Volunteer Events';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('description')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Select::make('program_id')
                            ->relationship('program', 'name')
                            ->required(),
                        Forms\Components\Select::make('point_of_contact_id')
                            ->label('Point-of-Contact')
                            ->required()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                            ->relationship(
                                name: 'point_of_contact',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                            )
                            ->searchable(['firstname','middle_name', 'lastname']),
                    ]),

                Forms\Components\Select::make('event_type_id')
                    ->relationship('event_type', 'name')
                    ->required()
                    ->reactive(),

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
                                    ->minDate(now()->startOfDay())
                                    ->default(now()),
                                Forms\Components\TimePicker::make('start_time')

                                    ->label('Start')
                                    ->default('08:00')
                                    ->seconds(false),
                                Forms\Components\TimePicker::make('end_time')
                                    ->label('End')
                                    ->default('17:00')
                                    ->seconds(false),
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
                                    ->default(1),

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
                    ]),


                Forms\Components\TextInput::make('location')
                    ->required(),

                Forms\Components\TagsInput::make('tags')
                    ->suggestions(fn() => EventTag::orderBy('id')->pluck('name')->toArray()),

                Forms\Components\Section::make('Slots')
                    ->schema([
                        Forms\Components\Fieldset::make('AM')
                            ->schema([
                                Forms\Components\TextInput::make('am_slot_number')
                                    ->label('Slot')
                                    ->numeric(),
                                Forms\Components\TimePicker::make('am_start_time')
                                    ->label('Start time')
                                    ->default('8:00')
                                    ->seconds(false),
                                Forms\Components\TimePicker::make('am_end_time')
                                    ->label('End time')
                                    ->default('11:00')
                                    ->seconds(false),
                            ])->columns(3),
                        Forms\Components\Fieldset::make('PM')
                            ->schema([
                                Forms\Components\TextInput::make('pm_slot_number')
                                    ->label('Slot')
                                    ->numeric(),
                                Forms\Components\TimePicker::make('pm_start_time')
                                    ->label('Start time')
                                    ->default('13:00')
                                    ->seconds(false),
                                Forms\Components\TimePicker::make('pm_end_time')
                                    ->label('End time')
                                    ->default('18:00')
                                    ->seconds(false),
                            ])->columns(3),
                    ]),

                Forms\Components\Select::make('facilitators')
                    ->preload()
                    ->multiple()
                    ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                    ->relationship(
                        name: 'facilitators',
                        modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                    )
                    ->searchable(['firstname','middle_name', 'lastname']),

                Forms\Components\Toggle::make('attachment_required')
                    ->columnSpanFull()
                    ->reactive(),

                Forms\Components\Radio::make('approval_type')
                    ->options([
                        1 => 'Automatic',
                        2 => 'Requires Facilitator Approval',
                    ])
                    ->default(2)
                    ->required(),
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
//                        Forms\Components\TagsInput::make('required_document_types')
//                            ->visible(fn ($get) => $get('requires_documents')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
//                Tables\Columns\TextColumn::make('event_type_id')
//                    ->numeric()
//                    ->sortable(),
                Tables\Columns\TextColumn::make('recurrence_type_id')
                    ->label('Recurrence type')
                    ->formatStateUsing(function (Event $record,string $state){
                        if($state == 2){
                            return ucfirst($record->frequency);

                        }
                        return $record->event_recurrence_type->name;
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('point_of_contact_id')
                    ->label('Point-of-Contact')
                    ->formatStateUsing(function (Event $record,string $state){
                        $user = User::find($state);
                        return $user->firstname.''.$user->lastname;
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('program.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approval_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('sign_up_approval_required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('attachment_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_by_user.firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_by_user.firstname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->mountUsing(function (Event $record,ComponentContainer $form){
                        $media = [];
                        foreach ($record->getMedia('event-attachments') as $media_item) {
                            $index = strlen(storage_path('app/public/'));
                            $media[] = substr($media_item->getPath(), $index);
                        }
                        $data['media'] = $media;

                        $form->fill($data);

                    }),
                Tables\Actions\EditAction::make()
                    ->mountUsing(function (Event $record,ComponentContainer $form){
                        $media = [];
                        foreach ($record->getMedia('event-attachments') as $media_item) {
                            $index = strlen(storage_path('app/public/'));
                            $media[] = substr($media_item->getPath(), $index);
                        }
                        $data['media'] = $media;

                        $form->fill($data);

                    }),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_date','asc');
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'calendar' => Pages\Calendar::route('/calendar'),
            'thumbnail' => Pages\Thumbnail::route('/thumbnail'),
            'create' => Pages\CreateEvent::route('/create'),
            'view' => Pages\ViewEvent::route('/{record}'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
