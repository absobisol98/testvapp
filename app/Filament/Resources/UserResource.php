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
use App\Models\Cluster;
use App\Models\Company;
use App\Models\Program;
use Filament\Forms\Get;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static int $globalSearchResultsLimit = 20;

    protected static ?int $navigationSort = -1;
    protected static ?string $navigationIcon = 'heroicon-s-users';

    public static function shouldRegisterNavigation(): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    public static function canViewAny(): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    public static function canCreate(): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();
        return $user && ($user->isAdminRole() || $user->id === $record->id);
    }

    public static function canDelete(Model $record): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    public static function canDeleteAny(): bool
    {
        return (bool) auth()->user()?->isAdminRole();
    }

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        $volunteerFields = [
            Forms\Components\Section::make('Volunteer Details')
                ->description('Volunteer-specific information')
                ->icon('heroicon-o-hand-raised')
                ->collapsible()
                ->columns(2)
                ->columnSpanFull()
                ->visible(fn ($record) => $record?->hasRole('Volunteer'))
                ->schema([
                    Select::make('affiliate_type_id')
                        ->label('Affiliation Type')
                        ->options([1 => 'Ayala Employee', 2 => 'External Partner'])
                        ->live()
                        ->columnSpanFull(),

                    Select::make('cluster_id')
                        ->label('Cluster')
                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 1)
                        ->options(fn () => Cluster::all()->pluck('name', 'id')->toArray())
                        ->searchable(),

                    Select::make('company_id')
                        ->label('Company')
                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 1)
                        ->options(fn (Get $get) => $get('cluster_id')
                            ? Company::where('cluster_id', $get('cluster_id'))->pluck('name', 'id')->toArray()
                            : Company::all()->pluck('name', 'id')->toArray()
                        )
                        ->searchable(),

                    Forms\Components\TextInput::make('external_company_name')
                        ->label('External Company Name')
                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 2)
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('company_address')
                        ->label('Address')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('company_contact_number')
                        ->label('Contact Number')
                        ->maxLength(20),

                    Forms\Components\TextInput::make('company_email')
                        ->label('Company Email')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\Section::make('Emergency Contact')
                        ->columns(2)
                        ->columnSpanFull()
                        ->schema([
                            Forms\Components\TextInput::make('emergency_contact_name')
                                ->label('Contact Name'),
                            Forms\Components\TextInput::make('emergency_contact_relationship')
                                ->label('Relationship'),
                            Forms\Components\TextInput::make('emergency_contact_number')
                                ->label('Contact Number')
                                ->maxLength(20),
                        ]),

                    Select::make('program_id')
                        ->label('Primary Program')
                        ->options(fn () => Program::all()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->columnSpanFull(),

                    Forms\Components\TagsInput::make('skills')
                        ->label('Skills')
                        ->placeholder('Type a skill and press Enter')
                        ->columnSpanFull(),
                ]),
        ];

        return $form
            ->schema((new UserCreateField())->execute(false, $volunteerFields))
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->wrap(),
                Tables\Columns\TextColumn::make('username')->label('Username')
                    ->description(fn(Model $record) => $record->firstname . ' ' . $record->lastname)
                    ->searchable(),
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
        if (!method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        if ($settings->isMailSettingsConfigured()) {
            $notification = new VerifyEmail();
            $notification->url = Filament::getVerifyEmailUrl($user);

            $settings->loadMailSettingsToConfig();

            $user->notify($notification);


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
    }
}
