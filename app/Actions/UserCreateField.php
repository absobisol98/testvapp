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
    public function execute($not_from_user_resorce, array $extraDetailsFields = [])
    {
        return [
            Group::make()
                ->schema([
                    SpatieMediaLibraryFileUpload::make('media')
                        ->hiddenLabel()
                        ->avatar()
                        ->collection('avatars')
                        ->alignCenter()
                        ->columnSpanFull(),

                    Actions::make([
                        Action::make('resend_verification')
                            ->label(__('resource.user.actions.resend_verification'))
                            ->color('info')
                            ->action(function (Model $record) {
                                static::doResendEmailVerification($record);
                            }),
                    ])
                        ->hiddenOn('create')
                        ->fullWidth(),

                    Section::make()
                        ->schema([
                            TextInput::make('password')
                                ->password()
                                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->revealable()
                                ->helperText(fn(string $context) =>
                                    $context === 'edit'
                                        ? 'Leave it blank if the password is same as before'
                                        : null
                                )
                                ->required(fn(string $context) => $context === 'create'),

                            TextInput::make('passwordConfirmation')
                                ->password()
                                ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                ->dehydrated(fn(?string $state): bool => filled($state))
                                ->revealable()
                                ->same('password')
                                ->required(fn(string $context) => $context === 'create'),
                        ])
                        ->compact()
                        ->visible(fn(): bool => auth()->user()->can('update_user')),

                    Section::make()
                        ->schema([
                            Placeholder::make('email_verified_at')
                                ->label(__('resource.general.email_verified_at'))
                                ->content(fn($record): ?string => new HtmlString(
                                    (string) ($record?->email_verified_at ?? 'Not Verified')
                                )),

                            Placeholder::make('created_at')
                                ->label(__('resource.general.created_at'))
                                ->content(fn($record): ?string => $record?->created_at?->diffForHumans()),

                            Placeholder::make('updated_at')
                                ->label(__('resource.general.updated_at'))
                                ->content(fn($record): ?string => $record?->updated_at?->diffForHumans()),
                        ])
                        ->compact()
                        ->hidden(function (string $operation) use ($not_from_user_resorce) {
                            return $operation === 'create' || $not_from_user_resorce;
                        }),
                ])
                ->columnSpan(1),

            Tabs::make()
                ->schema([
                    Tab::make('Details')
                        ->icon('heroicon-o-information-circle')
                        ->schema(array_merge([
                            TextInput::make('username')
                                ->required(fn() => auth()->user()?->isAdminRole())
                                ->maxLength(255)
                                ->live()
                                ->rules(function ($record) {
                                    $userId = $record?->id;

                                    return $userId
                                        ? ['unique:users,username,' . $userId]
                                        : ['unique:users,username'];
                                })
                                ->visible(fn() => auth()->user()?->isAdminRole()),

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
                        ], $extraDetailsFields))
                        ->columns(2),

                    Tab::make('Roles')
                        ->visible(fn() => auth()->user()->can('update_shield::role'))
                        ->hidden(fn() => $not_from_user_resorce)
                        ->icon('fluentui-shield-task-48')
                        ->schema([
                            Select::make('roles')
                                ->hiddenLabel()
                                ->relationship('roles', 'name')
                                ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                                ->multiple()
                                ->preload()
                                ->searchable()
                                ->optionsLimit(5)
                                ->columnSpanFull(),
                        ]),
                ])
                ->columnSpan([
                    'sm' => 1,
                    'lg' => 2,
                ]),
        ];
    }

    /**
     * FIX: Missing method that caused crash
     */
    private static function doResendEmailVerification(Model $record): void
    {
        if (! $record instanceof User) {
            return;
        }

        if ($record->hasVerifiedEmail()) {
            return;
        }

        $record->sendEmailVerificationNotification();
    }
}