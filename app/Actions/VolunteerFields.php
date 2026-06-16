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

final class VolunteerFields
{
    public function execute()
    {
        return [
            Select::make('age_range')
                ->label('Age Range')
                ->placeholder('Not specified')
                ->options([
                    '10-17' => '10-17 years old',
                    '18-24' => '18-24 years old',
                    '25-34' => '25-34 years old',
                    '35-44' => '35-44 years old',
                    '45-54' => '45-54 years old',
                    '55-64' => '55-64 years old',
                    '65+'   => '65 years and above',
                ]),

            ToggleButtons::make('is_company')
                ->label(''),
            //     ->default(1)
            //     ->options([
            //         '1' => 'Company',
            //         '0' => 'School',
            //     ])
            //     ->icons([
            //         '1' => 'heroicon-o-building-office',
            //         '0' => 'heroicon-o-academic-cap',
            //     ])
            //     ->colors([
            //         '1' => 'secondary',
            //         '0' => 'secondary',
            //     ])
            //     ->live()
            //     ->grouped(),
            // Fieldset::make('School')
            //     ->visible(fn (Get $get) => !$get('is_company'))
            //     ->schema([
            //         TextInput::make('school')->label('School name')->required()->columnSpanFull(),

            //         TextInput::make('school_address')->label('Address')->columnSpanFull(),
            //     ]),

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

                    // Select::make('company_name')
                    //     ->required()
                    //     ->columnSpanFull()
                    //     ->visible(fn (Get $get) => $get('affiliate_type_id') == 2)
                    //     ->options(fn () => Company::all()->pluck('name', 'id')->toArray())
                    //     ->searchable()
                    //     ->live(),

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

                    TextInput::make('company_contact_number')
                        ->label('Contact number')
                        ->maxLength(20),

                    TextInput::make('company_email')
                        ->email()
                        ->maxLength(255),

                ]),

            Fieldset::make('In Case of Emergency')
                ->schema([
                    TextInput::make('emergency_contact_name')->label('Contact name'),
                    TextInput::make('emergency_contact_relationship')->label(' Relationship'),

                    TextInput::make('emergency_contact_number')
                        ->label('Contact number')
                        ->maxLength(20),

                ]),

            Select::make('program_id')
                ->columnSpanFull()
                ->label('Primary Program Assignment')
                ->options(Program::all()->pluck('name', 'id')->toArray()),

            \Filament\Forms\Components\CheckboxList::make('program_interests')
                ->columnSpanFull()
                ->label('What programs are you interested in?')
                ->columns(2)
                ->options([
                    'education'            => 'Education',
                    'disaster_relief'      => 'Disaster Relief Operations',
                    'financial_literacy'   => 'Financial Literacy',
                    'health'               => 'Health',
                    'environment'          => 'Environment',
                    'report'               => 'Report',
                    'others'               => 'Others',
                ]),

            \Filament\Forms\Components\TagsInput::make('skills')
                ->columnSpanFull()
                ->label('Skills')
                ->placeholder('Type a skill and press Enter')
                ->helperText('Add skills relevant to your volunteer experience (e.g. First Aid, Teaching, Web Development).'),
        ];

    }
}
