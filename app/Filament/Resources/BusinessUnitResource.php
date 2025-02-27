<?php

namespace App\Filament\Resources;

use App\Actions\UserCreateField;
use App\Filament\Resources\BusinessUnitResource\Pages;
use App\Filament\Resources\BusinessUnitResource\RelationManagers;
use App\Filament\Resources\BusinessUnitResource\RelationManagers\AdminsRelationManager;
use App\Models\BusinessUnit;
use App\Models\User;
use App\Settings\MailSettings;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Spatie\Permission\Models\Role;

class BusinessUnitResource extends Resource
{
    protected static ?string $model = BusinessUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        if(auth()->user()->hasRole('External Partner')){
            return 'My Business Unit';
        }
        return 'Business Units';

    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('cluster_id')
                            ->label('Cluster')
                            ->relationship('cluster', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live(),

                        Select::make('company_id')
                            ->label('Company')
                            ->relationship('company', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $company = \App\Models\Company::find($state);
                                    if ($company) {
                                        $set('name', $company->name);
                                        $set('show_name', $company->name);
                                        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $company->name);
                                        $set('slug', strtolower($slug));
                                        $set('show_slug', strtolower($slug));
                                    }
                                }
                            }),

                            Forms\Components\Hidden::make('name')

                            ->live(), // Make it reactive
                            Forms\Components\Hidden::make('slug')

                            ->live(), // Make it reactive
                    ])
                    ->columnSpanFull(),

                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('manage_admins')
                        ->label('Manage Admins')
                        ->icon('heroicon-o-users')
                        ->color('warning')
                        ->url(fn ($record) => $record ? static::getUrl('manage-admins', ['record' => $record]) : null)
                        ->visible(fn ($record) => $record && auth()->user()->can('update', $record))
                        ->hidden(fn ($operation) => $operation === 'create'),
                ])->columnSpanFull(),

                SpatieMediaLibraryFileUpload::make('media')
                    ->label('Company Logo')
                    ->collection('bu_logo')
                    ->required()
                    ->conversion('preview')
                    ->maxSize(10000)
                    ->acceptedFileTypes([
                            'image/apng',
                            'image/avif',
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/svg',
                            'image/webp',
                    ])
                    ->imageResizeMode('cover')
                    ->hint('Please upload a high resolution image for better quality with no background.')
                    ->helperText('Accepted File types (WebP, JPG, PNG, Avif, SVG and APng) Only. Max. 10MB')
                    ->image()
                    ->openable()
                    ->downloadable()
                    ->columnSpan(3),

                Forms\Components\TextInput::make('show_name')
                    ->label('Name')
                    ->required()
                    ->disabled()
                    ->columnSpan(3)
                    ->maxLength(50),

                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('nickname')
                            ->required()
                            ->label('Nickname / Shortname')
                            ->maxLength(100),

                        Forms\Components\TextInput::make('show_slug')
                            ->label('Slug')
                            ->disabled()
                            ->required()
                            ->unique(column: 'slug',ignoreRecord: true)
                            ->maxLength(100),
                    ]),


                Forms\Components\Textarea::make('about')
                    ->rows(5)
                    ->columnSpanFull(),

                    Fieldset::make('Header')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Cover')
                            ->collection('bu_eventcover')
                            ->required()
                            ->conversion('preview')
                            ->maxSize(5000)
                            ->acceptedFileTypes([
                                    'image/apng',
                                    'image/avif',
                                    'image/png',
                                    'image/jpg',
                                    'image/jpeg',
                                    'image/svg',
                                    'image/webp',
                            ])
                            ->imageResizeMode('cover')
                            ->helperText('Accepted File types (WebP, JPG, PNG, Avif, SVG and APng) Only.')
                            ->image()
                            ->openable()
                            ->downloadable()
                            ->columnSpan(3),

                        Forms\Components\TextInput::make('header_tagline')
                            ->label('Tagline')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(100),

                        Forms\Components\Textarea::make('header_description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                ]),

                Repeater::make('socials')
                    ->relationship()
                    ->addActionLabel('Add Socials')
                    ->schema([
                        Select::make('social')
                            ->options([
                                'instagram' => 'Instagram',
                                'facebook' => 'Facebook',
                                'linkedin' => 'Linked In',
                                'website' => 'Website',
                                'youtube' => 'Youtube',
                                'tiktok' => 'Tiktok',
                            ])
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                            ->required(),
                        TextInput::make('link')->placeholder('https://ayala.com/')->required(),
                    ])
                    ->defaultItems(3)
                    ->columns(2)
                    ->columnSpanFull(),


                Fieldset::make('Gallery')
                    ->schema([

                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('bu_galleries')
                            ->image()
                            ->acceptedFileTypes([
                                    'image/apng',
                                    'image/avif',
                                    'image/png',
                                    'image/jpg',
                                    'image/jpeg',
                                    'image/svg',
                                    'image/webp',
                            ])
                            ->helperText('Accepted File types (WebP, JPG, PNG, Avif, SVG and APng) Only.')
                            ->multiple()
                            ->required()
                            ->minFiles(2)
                            ->maxFiles(20)
                            ->maxSize(5000)
                            ->reorderable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ]),


            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('header_tagline')
                    ->searchable(),
                Tables\Columns\TextColumn::make('header_tagline')
                    ->searchable(),
                Tables\Columns\TextColumn::make('header_description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([

                Action::make('visit_page')
                    ->label('Visit Page')
                    ->icon('fas-eye')
                    ->color('warning')
                    ->url(fn (BusinessUnit $record): string  => route('businessunit.homepage.view',['slug' => $record->slug]),shouldOpenInNewTab:true),
                Action::make('manage_admins')
                    ->label('Manage Admins')
                    ->icon('heroicon-o-users')
                    ->url(fn (BusinessUnit $record): string => static::getUrl('manage-admins', ['record' => $record]))
                    ->visible(fn (BusinessUnit $record): bool => auth()->user()->can('update', $record)),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AdminsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessUnits::route('/'),
            'create' => Pages\CreateBusinessUnit::route('/create'),
            'edit' => Pages\EditBusinessUnit::route('/{record}/edit'),
            'manage-admins' => Pages\ManageBusinessUnitAdmins::route('/{record}/admins'),
        ];
    }
}
