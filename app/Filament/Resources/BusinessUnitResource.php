<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessUnitResource\Pages;
use App\Filament\Resources\BusinessUnitResource\RelationManagers;
use App\Models\BusinessUnit;
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
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessUnitResource extends Resource
{
    protected static ?string $model = BusinessUnit::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                    SpatieMediaLibraryFileUpload::make('media')
                        ->label('Company Logo')
                        ->collection('bu_logo')
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


                Forms\Components\TextInput::make('name')
                    ->required()
                    ->afterStateUpdated( function (Set $set,$state){
                        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $state);
                        $set('slug',strtolower($slug));
                    })
                    ->columnSpan(3)
                    ->live(onBlur: true)
                    ->maxLength(50),
                Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('nickname')
                            ->required()
                            ->label('Nickname / Shortname')
                            ->maxLength(100),
        
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(column: 'slug',ignoreRecord: true)
                            ->maxLength(100),
                    ]),
              

                Forms\Components\TextInput::make('address')
                    ->required()
                    ->columnSpan(3)
                    ->maxLength(255),
   
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


                Fieldset::make('Events')
                    ->schema([
                        Forms\Components\TextInput::make('event_heading')
                            ->label('Heading')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('event_description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->maxLength(255),

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
                            ->maxFiles(50)
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
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('header_tagline')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_heading')
                    ->searchable(),
                Tables\Columns\TextColumn::make('event_description')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBusinessUnits::route('/'),
            'create' => Pages\CreateBusinessUnit::route('/create'),
            'edit' => Pages\EditBusinessUnit::route('/{record}/edit'),
        ];
    }
}
