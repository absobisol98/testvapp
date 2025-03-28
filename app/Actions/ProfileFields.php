<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;

final class ProfileFields
{
    /**
     * @param bool $isCreating Whether the form is being used for creating a new user
     * @param bool $isAdmin Whether the current user is an admin
     */
    public function execute(bool $isCreating = true, bool $isAdmin = false)
    {
        return [
            Grid::make([
                'default' => 1,
                'md' => 2,
            ])->schema([
                // Left column - Personal Information
                Group::make([
                    Section::make('Personal Information')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('avatar')
                                ->collection('avatars')
                                ->avatar()
                                ->image()
                                ->imageEditor()
                                ->alignCenter()
                                ->columnSpanFull(),

                            TextInput::make('firstname')
                                ->label('First name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('lastname')
                                ->label('Last name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('middle_name')
                                ->label('Middle name')
                                ->maxLength(255),

                            TextInput::make('nickname')
                                ->label('Nickname')
                                ->maxLength(255),

                            TextInput::make('email')
                                ->email()
                                ->required()
                                ->maxLength(255)
                                ->disabled(!$isCreating && !$isAdmin)
                                ->unique(table: 'users', column: 'email', ignorable: fn ($record) => $record),

                            TextInput::make('password')
                                ->password()
                                ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                                ->dehydrated(fn (?string $state): bool => filled($state))
                                ->required($isCreating)
                                ->maxLength(255),

                            TextInput::make('mobilenumber')
                                ->label('Mobile number')
                                ->tel()
                                ->placeholder('09XXXXXXXXX')
                                ->regex('/^(09|\+639)\d{9}$/')
                                ->validationAttribute('mobile number')
                                ->helperText('Format: 09XXXXXXXXX or +639XXXXXXXXX'),

                            DatePicker::make('birthday')
                                ->label('Birthday')
                                ->maxDate(now()->subYears(10)),

                            Select::make('gender')
                                ->options([
                                    'Male' => 'Male',
                                    'Female' => 'Female',
                                    'Others' => 'Others',
                                    'Prefer not to say' => 'Prefer not to say',
                                ]),

                            // Only administrators can edit roles
                            Select::make('roles')
                                ->label('User Role')
                                ->multiple()
                                ->relationship('roles', 'name')
                                ->preload()
                                ->columnSpanFull()
                                ->visible($isAdmin),

                            // Only visible for administrators editing volunteers
                            // Toggle field for the volunteer flag
                            Toggle::make('volunteer')
                                ->label('Has Volunteer Access')
                                ->helperText('Allow this user to access volunteer features')
                                ->visible($isAdmin)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])->columnSpan(['default' => 1, 'md' => 1]),

                // Right column - Tabbed content
                Group::make([
                    Tabs::make('Additional Information')
                        ->tabs([
                            Tabs\Tab::make('Company Information')
                                ->icon('heroicon-o-building-office')
                                ->schema([
                                    Select::make('affiliate_type_id')
                                        ->columnSpanFull()
                                        ->default(1)
                                        ->label('Affiliation Type')
                                        ->live()
                                        ->options(AffiliateType::all()->pluck('name', 'id')->toArray()),

                                    Select::make('cluster_id')
                                        ->columnSpanFull()
                                        ->label('Cluster')
                                        ->required()
                                        ->searchable()
                                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 1)
                                        ->options(fn () => Cluster::all()->pluck('name', 'id')->toArray())
                                        ->live(),

                                    TextInput::make('external_company_name')
                                        ->label('Company name')
                                        ->required()
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 2)
                                        ->afterStateUpdated(fn (callable $set) => $set('company_id', null)),

                                    Select::make('company_id')
                                        ->required()
                                        ->prefixIcon('heroicon-o-building-office')
                                        ->prefixIconColor('primary')
                                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 1)
                                        ->columnSpanFull()
                                        ->label('Company')
                                        ->searchable()
                                        ->options(function($get) {
                                            if (!$get('cluster_id')) {
                                                return [];
                                            }
                                            return Company::query()
                                                ->where('cluster_id', $get('cluster_id'))
                                                ->pluck('name', 'id')
                                                ->toArray();
                                        })
                                        ->live()
                                        ->afterStateUpdated(fn (callable $set) => $set('external_company_name', null)),

                                    TextInput::make('company_address')
                                        ->label('Company Address')
                                        ->columnSpanFull(),

                                    TextInput::make('company_representative')
                                        ->label('HR Representative')
                                        ->columnSpanFull(),

                                    TextInput::make('company_contact_number')
                                        ->label('Company Contact Number')
                                        ->tel()
                                        ->placeholder('09XXXXXXXXX')
                                        ->regex('/^(09|\+639)\d{9}$/')
                                        ->validationAttribute('contact number')
                                        ->helperText('Format: 09XXXXXXXXX or +639XXXXXXXXX'),

                                    TextInput::make('company_email')
                                        ->label('Company Email')
                                        ->email()
                                        ->maxLength(255),
                                ])
                                ->columns(2),

                            Tabs\Tab::make('Emergency Contact')
                                ->icon('heroicon-o-exclamation-circle')
                                ->schema([
                                    TextInput::make('emergency_contact_name')
                                        ->label('Contact name'),

                                    TextInput::make('emergency_contact_relationship')
                                        ->label('Relationship'),

                                    TextInput::make('emergency_contact_number')
                                        ->label('Contact number')
                                        ->tel()
                                        ->placeholder('09XXXXXXXXX')
                                        ->regex('/^(09|\+639)\d{9}$/')
                                        ->validationAttribute('emergency contact number')
                                        ->helperText('Format: 09XXXXXXXXX or +639XXXXXXXXX'),
                                ])
                                ->columns(2),

                            Tabs\Tab::make('Interests & Preferences')
                                ->icon('heroicon-o-heart')
                                ->schema([
                                    Select::make('programs')
                                        ->label('Interests (Select all that apply)')
                                        ->helperText('The first selected interest will be set as your primary interest')
                                        ->multiple()
                                        ->relationship('programs', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->columnSpanFull(),
                ])->columnSpan(['default' => 1, 'md' => 1]),
            ]),
        ];
    }
}
