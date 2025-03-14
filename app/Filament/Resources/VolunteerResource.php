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
use App\Notifications\VerifyEmailNotification;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class VolunteerResource extends Resource
{
    protected static ?string $model = Volunteer::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    public static function getNavigationUrl(): string
    {
        if (auth()->user()?->hasRole('Volunteer')) {
            $volunteerId = auth()->id();
            return static::getUrl('view', ['record' => $volunteerId]);
        }

        return static::getUrl('index');
    }

    public static function getNavigationLabel(): string
    {
        if(auth()->user()?->hasRole('Volunteer')){
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
                if (!auth()->user()->hasRole(['super_admin', 'admin', 'Ayala Super Admin'])) {
                    $query = $query->where('id', auth()->id());
                }
                return $query;
            })
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->wrap(),
                // Tables\Columns\TextColumn::make('username')->label('Username')
                //     ->description(fn(Model $record) => $record->firstname . ' ' . $record->lastname)
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('roles.name')->label('Role')
                //     ->formatStateUsing(fn($state): string => Str::headline($state))
                //     ->colors(['info'])
                //     ->badge(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')->label('Verified at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('status')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'active' => 'success',
                //         'inactive' => 'danger',
                //         'pending' => 'warning',
                //         default => 'secondary',
                //     }),
                // Tables\Columns\TextColumn::make('total_hours')
                //     ->label('Volunteer Hours')
                //     ->numeric()
                //     ->sortable(),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Volunteer $record) =>
                        auth()->user()->hasRole(['super_admin', 'admin']) ||
                        $record->id === auth()->id()
                    ),
                     // Add the resend verification email action
                Tables\Actions\Action::make('resendVerificationEmail')
                ->label('Resend Verification Email')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->visible(fn (Volunteer $record) => 
                    is_null($record->email_verified_at) && 
                    (auth()->user()->hasRole(['super_admin','Ayala Super Admin', 'admin']) || $record->id === auth()->id())
                )
                ->action(function (Volunteer $record) {
                    try {
                        // Load mail settings
                        $settings = app(MailSettings::class);
                        $settings->loadMailSettingsToConfig();

                        // Send verification email
                        $record->notify(new VerifyEmailNotification());

                        Log::info('Verification email resent to volunteer', ['email' => $record->email]);
                        
                        Notification::make()
                            ->title('Verification email sent successfully')
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Log::error('Failed to resend verification email', [
                            'email' => $record->email,
                            'error' => $e->getMessage()
                        ]);
                        
                        Notification::make()
                            ->title('Failed to send verification email')
                            ->body('Please try again later or contact support.')
                            ->danger()
                            ->send();
                    }
                }),
                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin'])),

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
