<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\Program;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
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
                    TextInput::make('company_name')->required()->columnSpanFull(),

                    TextInput::make('company_address')->label('Address')->columnSpanFull(),

                    TextInput::make('company_representative')->label('HR Representative')->columnSpanFull(),

                    PhoneInput::make('company_contact_number')
                        ->label('Contact number'),

                    TextInput::make('company_email')
                        ->email()
                        ->maxLength(255),


                    Select::make('affiliate_type_id')
                        ->columnSpanFull()
                        ->default(1)
                        ->label('')
                        ->live()
                        ->options(AffiliateType::all()->pluck('name', 'id')->toArray()),

                    Select::make('company_id')
                        ->required()
                        ->prefixIcon('heroicon-o-building-office')
                        ->prefixIconColor('primary')
                        ->visible(fn (Get $get) => $get('affiliate_type_id') != 3)
                        ->columnSpanFull()
                        ->label('')
                        ->searchable()
                        ->options(function($get){

                            $options = [];

                            $clusters = Cluster::with(['companies'])->get();

                            if($get && $get('affiliate_type_id') == 1){
                                $clusters = Cluster::with(['companies'])->where('id',1)->get();
                            }else if($get && $get('affiliate_type_id') == 2){
                                $clusters = Cluster::with(['companies'])->where('id','!=',1)->get();
                            }

                            foreach ($clusters as $cluster) {
                                $options[$cluster->name] = collect($cluster->companies)->mapWithKeys(function ($company) {
                                    return [$company->id => $company->name];
                                })->toArray();
                            }

                            return $options;
                        }),
                ]),

            Fieldset::make('In Case of Emergency')
                ->schema([
                    TextInput::make('emergency_contact_name')->label('Contact name'),
                    TextInput::make('emergency_contact_relationship')->label(' Relationship'),

                    PhoneInput::make('emergency_contact_number')
                        ->label('Contact number'),
                ]),

            Select::make('program_id')
                ->columnSpanFull()
                ->label('Interests')
                ->options(Program::all()->pluck('name', 'id')->toArray()),
        ];

    }
}
