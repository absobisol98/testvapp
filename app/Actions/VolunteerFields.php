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
use libphonenumber\PhoneNumberType as libPhoneNumberType;

final class VolunteerFields
{
    public function execute()
    {
        return [
            ToggleButtons::make('is_company')
                ->label(''),
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
                        ->afterStateUpdated(fn (callable $set) => $set('external_company_name', null)),

                    TextInput::make('company_address')->label('Address')->columnSpanFull(),


                    TextInput::make('company_representative')->label('HR Representative')->columnSpanFull(),

                    PhoneInput::make('company_contact_number')
                        ->label('Contact number')
                        ->defaultCountry('PH')
                        ->showFlags(false)
                        ->validateFor(
                            type: libPhoneNumberType::MOBILE | libPhoneNumberType::FIXED_LINE
                        ),

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
                        ->defaultCountry('PH')
                        ->showFlags(false)
                        ->validateFor(
                            type: libPhoneNumberType::MOBILE | libPhoneNumberType::FIXED_LINE
                        ),

                ]),

            Select::make('program_id')
                ->columnSpanFull()
                ->label('Interests')
                ->options(Program::all()->pluck('name', 'id')->toArray()),

            // Select::make('program_interests')
            //     ->relationship('')
            //     ->columnSpanFull()
            //     ->label('Interests')
            //     ->multiple()
            //     ->searchable()
            //     ->preload()
            //     ->placeholder('Select your volunteering interests')
            //     ->helperText('Choose one or more program areas that interest you')
        ];

    }
}
