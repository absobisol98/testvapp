<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use App\Models\User;
use Filament\Forms;
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
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\RichEditor::make('description')
                            ->required(),

                        Forms\Components\Select::make('program_id')
                            ->relationship('program', 'name')
                            ->required(),

                        Forms\Components\Select::make('access_type')
                            ->options([
                                'exclusive_employees' => 'Exclusive to Ayala Employees',
                                'exclusive_business_unit' => 'Exclusive to Business Unit',
                                'hybrid' => 'Hybrid (By Selection)',
                                'public' => 'Public Event',
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('business_units')
                            ->multiple()
                            ->relationship('businessUnits', 'name')
                            ->visible(fn ($get) => $get('access_type') === 'hybrid'),

                        Forms\Components\Select::make('event_type')
                            ->options([
                                'one_time' => 'One-time Event',
                                'recurring' => 'Recurring Event',
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),

                        Forms\Components\DateTimePicker::make('end_date')
                            ->required()
                            ->after('start_date'),

                        Forms\Components\Repeater::make('recurring_pattern')
                            ->schema([
                                Forms\Components\Select::make('frequency')
                                    ->options([
                                        'daily' => 'Daily',
                                        'weekly' => 'Weekly',
                                        'monthly' => 'Monthly',
                                    ]),
                                Forms\Components\DatePicker::make('until'),
                            ])
                            ->visible(fn ($get) => $get('event_type') === 'recurring'),

                        Forms\Components\TextInput::make('location')
                            ->required(),

                        Forms\Components\TagsInput::make('tags'),

                        Forms\Components\Select::make('approval_type')
                            ->options([
                                'automatic' => 'Automatic',
                                'facilitator_approval' => 'Requires Facilitator Approval',
                                'with_attachment' => 'Requires Additional Documents',
                            ])
                            ->required(),

                        Forms\Components\Repeater::make('slots')
                            ->relationship('slots')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\TextInput::make('capacity')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TimePicker::make('start_time'),
                                Forms\Components\TimePicker::make('end_time'),
                            ]),

                        Forms\Components\Select::make('facilitators')
                            ->multiple()
                            ->getOptionLabelFromRecordUsing(fn (User $record) => "{$record->firstname} {$record->middle_name} {$record->lastname}")
                            ->relationship(
                                name: 'facilitators',
                                modifyQueryUsing: fn (Builder $query) => $query->orderBy('firstname')->orderBy('lastname'),
                            )
                            ->searchable(['firstname','middle_name', 'lastname']),

                        Forms\Components\Toggle::make('requires_documents')
                            ->reactive(),

                        Forms\Components\TagsInput::make('required_document_types')
                            ->visible(fn ($get) => $get('requires_documents')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('recurrence_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('point_of_contact_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approval_status_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('sign_up_approval_required')
                    ->boolean(),
                Tables\Columns\IconColumn::make('attachment_required')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageEvents::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
