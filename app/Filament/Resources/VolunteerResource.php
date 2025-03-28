<?php

namespace App\Filament\Resources;

use App\Actions\ProfileFields;
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
        $user = auth()->user();

        // Check if user is in volunteer mode or actually has the Volunteer role
        if ($user?->is_volunteer || $user?->hasRole('Volunteer')) {
            $volunteerId = auth()->id();
            return static::getUrl('view', ['record' => $volunteerId]);
        }

        return static::getUrl('index');
    }

    public static function getNavigationLabel(): string
    {
        if (auth()->user()->isInVolunteerMode()) {
            return 'My Volunteer Profile';
        }
        return 'Volunteers';
    }

    public static function form(Form $form): Form
    {
        $isAdmin = auth()->user()->hasRole(['super_admin', 'admin', 'Ayala Super Admin']);

        return $form
            ->schema((new ProfileFields())->execute(false, $isAdmin))
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
                Tables\Columns\TextColumn::make('firstname')
                    ->label('Name')
                    ->formatStateUsing(fn(Model $record) => $record->firstname . ' ' . $record->lastname)
                    ->searchable(),
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
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'Ayala Super Admin'])),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Volunteer $record) =>
                        auth()->user()->hasRole(['super_admin', 'admin', 'Ayala Super Admin']) ||
                        $record->id === auth()->id()
                    ),
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
                        ->visible(fn () => auth()->user()->hasRole(['super_admin', 'admin', 'Ayala Super Admin'])),
                ]),
            ]);
    }

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
