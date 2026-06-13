<?php

namespace App\Filament\Resources;

use App\Actions\UserCreateField;
use App\Actions\VolunteerFields;
use App\Filament\Resources\VolunteerResource\Pages;
use App\Filament\Resources\VolunteerResource\RelationManagers\EventsRelationManager;
use App\Models\User;
use App\Models\Volunteer;
use App\Settings\MailSettings;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationUrl(): string
    {
        if (auth()->user()?->hasActiveRole('Volunteer')) {
            $volunteerId = auth()->id();
            return static::getUrl('view', ['record' => $volunteerId]);
        }

        return static::getUrl('index');
    }

    public static function getNavigationLabel(): string
    {
        if (auth()->user()?->hasActiveRole('Volunteer')) {
            return 'My Volunteer Profile';
        }
        return 'Volunteers';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema( (new UserCreateField())->execute(false))
                    ->columnSpan(1),

                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Details')
                            ->icon('heroicon-o-information-circle')
                            ->schema((new VolunteerFields())->execute())
                            ->columns(),

                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'lg' => 2
                    ]),
            ])
            ->columns(3);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $activeRole = auth()->user()->activeRole();
                if (! in_array($activeRole, ['Ayala Super Admin', 'admin', 'Ayala Super Admin'])) {
                    $query = $query->where('id', auth()->id());
                }
                return $query;
            })
            ->columns([
                Tables\Columns\TextColumn::make('volunteer_id')
                    ->label('Volunteer ID')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Name')
                    ->formatStateUsing(fn (Model $record) => $record->firstname . ' ' . $record->lastname)
                    ->description(fn (Model $record) => $record->email)
                    ->searchable(['firstname', 'lastname']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verified At')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(function (Volunteer $record) {
                        if($record->id == auth()->user()->id){
                            return true;
                        }
                        return false;
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Volunteer $record) =>
                        auth()->user()->hasRole(['Ayala Super Admin', 'admin']) ||
                        $record->id === auth()->id()
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole(['Ayala Super Admin', 'admin'])),

                ]),
            ]);
    }

    // public static function getRelations(): array
    // {
    //     return [
    //         EventsRelationManager::class
    //     ];
    // }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVolunteers::route('/'),
            'create' => Pages\CreateVolunteer::route('/create'),
            'edit' => Pages\EditVolunteer::route('/{record}/edit'),
            'view' => Pages\ViewVolunteer::route('/{record}'),
        ];
    }
}
