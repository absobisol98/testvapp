<?php

namespace App\Actions;

use App\Models\User;
use App\Settings\MailSettings;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
final class UserCreateField
{
    public function execute($not_from_user_resorce)
    {
        return [
            Group::make()
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar')
                ->hiddenLabel()
                ->avatar()
                ->collection('avatars')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                ->maxSize(2048)  // 2MB file size limit
                ->imageResizeMode('cover')
                ->imageResizeTargetWidth('400')
                ->imageResizeTargetHeight('400')
                ->alignCenter()
                ->columnSpanFull(),

                Actions::make([
                    Action::make('resend_verification')
                        ->label(__('resource.user.actions.resend_verification'))
                        ->color('info')
                        ->action(fn(MailSettings $settings, Model $record) => static::doResendEmailVerification($settings, $record)),
                ])
                    ->hidden(fn (User $user) => $user->email_verified_at != null)
                    ->hiddenOn('create')
                    ->hiddenOn('edit')
                    ->fullWidth(),

               Section::make()
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->helperText(fn (string $context) => ($context === 'edit') ? 'Leave it blank if the password is same as before'  : null )
                            ->required(fn (string $context) => $context === 'create'),
                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->same('password')->required(fn (string $context) => $context === 'create'),
                    ])
                    ->compact()
                    ->visible(fn(): bool => auth()->user()->can('update_user')),

                Section::make()
                    ->schema([
                        Placeholder::make('email_verified_at')
                            ->label(__('resource.general.email_verified_at'))
                            ->content(fn($record): ?string => new HtmlString("$record?->email_verified_at")),
                        Placeholder::make('created_at')
                            ->label(__('resource.general.created_at'))
                            ->content(fn($record): ?string => $record?->created_at?->diffForHumans()),
                        Placeholder::make('updated_at')
                            ->label(__('resource.general.updated_at'))
                        ->content(fn($record): ?string => $record?->updated_at?->diffForHumans()),
                    ])
                    ->compact()
                    ->hidden(function(string $operation) use ($not_from_user_resorce){
                        if($operation === 'create' || $not_from_user_resorce){
                            return  true;
                        }
                        return false;
                    } ),
            ])
            ->columnSpan(1),

        Tabs::make()
            ->schema([
                Tab::make('Details')
                    ->icon('heroicon-o-information-circle')
                    ->schema([

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->rules(function ($record) {
                                $userId = $record?->id;
                                return $userId
                                    ? ['unique:users,email,' . $userId]
                                    : ['unique:users,email'];
                            }),

                        TextInput::make('firstname')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('lastname')
                            ->required()
                            ->maxLength(255),
                        
                            Select::make('age_range')
                            ->options([
                                '10-17' => '10-17 years old',
                                '18-24' => '18-24 years old',
                                '25-34' => '25-34 years old',
                                '35-44' => '35-44 years old',
                                '45-54' => '45-54 years old',
                                '55-64' => '55-64 years old',
                                '65+' => '65 years and above',
                            ])
                            ->placeholder('Select age range')
                            ->required(),

                    ])
                    ->columns(2),

                    Tab::make('Roles')
                    ->visible( fn  () => auth()->user()->can('update_shield::role'))
                    ->hidden( function () use  ($not_from_user_resorce) {
                        return ($not_from_user_resorce) ? true : false;
                    })
                    ->icon('fluentui-shield-task-48')
                    ->schema([
                        Select::make('roles')
                            ->hiddenLabel()
                            ->relationship(
                                'roles',
                                'name',
                                modifyQueryUsing: function ($query) {
                                    // If current user is not a super_admin, exclude the super_admin role
                                    if (!auth()->user()->hasRole('super_admin')) {
                                        return $query->where('name', '!=', 'super_admin');
                                    }

                                    return $query;
                                }
                            )
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->optionsLimit(5)
                            ->columnSpanFull(),
                    ])
            ])
            ->columnSpan([
                'sm' => 1,
                'lg' => 2
            ]),

        ];
    }
}
