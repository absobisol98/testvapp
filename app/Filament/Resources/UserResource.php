<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Settings\MailSettings;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use App\Actions\UserCreateField;
use Illuminate\Support\Facades\Log;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static int $globalSearchResultsLimit = 20;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'heroicon-s-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema((new UserCreateField())->execute(false))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                ->label('Avatar')
                ->collection('avatars')
                ->circular()
                ->conversion('thumb')
                ->size(40),
                Tables\Columns\TextColumn::make('roles.name')->label('Role')
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge(),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->email;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['email', 'firstname', 'lastname'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'name' => $record->firstname . ' ' . $record->lastname,
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.access");
    }

    public static function doResendEmailVerification($settings = null, $user): void
    {
        Log::info("Starting email verification for user: {$user->email}");

        try {
            if (!method_exists($user, 'notify')) {
                $userClass = $user::class;
                Log::error("Model [{$userClass}] does not have a [notify()] method.");
                throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
            }

            if ($settings->isMailSettingsConfigured()) {
                Log::info("Mail settings configured properly.");

                $settings->loadMailSettingsToConfig();

                // Use your custom notification that works in VolunteerRegistrationController
                $notification = new \App\Notifications\VerifyEmailNotification();
                $user->notify($notification);

                Log::info("Custom notification sent successfully to {$user->email}");

                Notification::make()
                    ->title(__('resource.user.notifications.verify_sent.title'))
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title(__('resource.user.notifications.verify_warning.title'))
                    ->body(__('resource.user.notifications.verify_warning.description'))
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            // Log error and show notification
            Log::error("Error during email verification: {$e->getMessage()}");
            Notification::make()
                ->title(__('resource.user.notifications.verify_error.title'))
                ->body(__('resource.user.notifications.verify_error.description'))
                ->danger()
                ->send();
        }
    }
}
