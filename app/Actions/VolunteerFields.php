<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\Company;
use App\Models\Program;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

final class VolunteerFields
{
    public function execute()
    {
        return [
            ToggleButtons::make('is_company')
                ->label('')
                ->default(1)
                ->options([
                    '1' => 'Company',
                    '0' => 'School',
                ])
                ->icons([
                    '1' => 'heroicon-o-building-office',
                    '0' => 'heroicon-o-academic-cap',
                ])
                ->colors([
                    '1' => 'secondary',
                    '0' => 'secondary',
                ])
                ->live()
                ->grouped(),
            Fieldset::make('School')
                ->visible(fn (Get $get) => !$get('is_company'))
                ->schema([
                    TextInput::make('school')->label('School name')->required()->columnSpanFull(),

                    TextInput::make('school_address')->label('Address')->columnSpanFull(),
                ]),

            Fieldset::make('Company')
                ->visible(fn (Get $get) => $get('is_company'))
                ->schema([
                    Select::make('affiliate_type_id')
                        ->columnSpanFull()
                        ->default(1)
                        ->label('')
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

                    Select::make('company_name')
                        ->required()
                        ->columnSpanFull()
                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 2)
                        ->options(fn () => Company::all()->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->live(),

                    TextInput::make('company_name')
                        ->required()
                        ->columnSpanFull()
                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 3),

                    Select::make('company_id')
                        ->required()
                        ->prefixIcon('heroicon-o-building-office')
                        ->prefixIconColor('primary')
                        ->visible(fn (Get $get) => $get('affiliate_type_id') == 1) // Changed visibility condition
                        ->columnSpanFull()
                        ->label('')
                        ->searchable()
                        ->options(function($get) {
                            if (!$get('affiliate_type_id') || $get('affiliate_type_id') != 1) {
                                return [];
                            }

                            // Only show companies for Ayala type and selected cluster
                            if (!$get('cluster_id')) {
                                return [];
                            }
                            return Company::query()
                                ->where('cluster_id', $get('cluster_id'))
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->live()
                        ->afterStateUpdated(fn (callable $set) => $set('company_name', null)),

                    TextInput::make('company_address')->label('Address')->columnSpanFull(),


                    TextInput::make('company_representative')->label('HR Representative')->columnSpanFull(),

                    PhoneInput::make('company_contact_number')
                        ->label('Contact number')
                        ->default('PH')
                        ->Placeholder('+63(xxx) xxx xxxx'),

                    TextInput::make('company_email')
                        ->email()
                        ->maxLength(255),

                ]),

            Fieldset::make('In Case of Emergency')
                ->schema([
                    TextInput::make('emergency_contact_name')->label('Contact name'),
                    TextInput::make('emergency_contact_relationship')->label(' Relationship'),

                    PhoneInput::make('emergency_contact_number')
                        ->label('Contact number')
                        ->default('PH')
                        ->Placeholder('+63(xxx) xxx xxxx'),
                ]),

            Select::make('program_id')
                ->columnSpanFull()
                ->label('Interests')
                ->options(Program::all()->pluck('name', 'id')->toArray()),
        ];

    }
}
