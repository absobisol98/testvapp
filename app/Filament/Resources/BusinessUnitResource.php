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

                Select::make('admins')
                    ->label('Admins')
                    ->helperText('Create or Assigned BU Admins')
                    ->relationship('admins','firstname')
                    ->multiple()
                    ->columnSpanFull()
                    ->options(function (){
                        $has_admin_in_other_BU = DB::table('business_unit_has_external_admin')->get()->pluck('user_id');
                        return User::role('External Partner')->whereNotIn('id', $has_admin_in_other_BU)->orderBy('firstname')->get()->pluck('name','id');
                    })
                    ->createOptionUsing(function ($data, $form) { 
                        $data['email_verified_at'] = now();
                        $supplier = User::create($data);
                        $form->model($supplier)->saveRelationships($supplier);
                        $role = Role::where('name','External Partner')->first();

                        DB::table('model_has_roles')->insert([
                            'role_id' =>  $role->id,
                            'model_id' => $supplier->id,
                            'model_type' => 'App\Models\User',
                        ]);
                        
                        Notification::make()
                            ->title('User Created')
                            ->success()
                            ->send();

                        return $supplier->id;
                    })

                    ->createOptionForm( (new UserCreateField())->execute(true) ),

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
            AdminsRelationManager::class
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
