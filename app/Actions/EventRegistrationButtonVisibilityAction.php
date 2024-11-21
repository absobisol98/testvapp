<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\EventCompany;
use App\Models\EventSlot;
use App\Models\EventTag;
use App\Models\Program;
use App\Models\TagsEvent;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Get;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

final class EventRegistrationButtonVisibilityAction
{
    public function execute($record)
    {
        switch ($record->event_type_id) {
            case 1: // Exclusive for Ayala Employees

                if(auth()->user()->company?->cluster->name == "Ayala Corporation Group"){
                    return true;
                }
                break;

            case 2: // Exclusive for Business Units/Partner

                if(auth()->user()->company?->cluster->name != "Ayala Corporation Group"){
                    return true;
                }
                break;

            case 3: // Hybrid events

                $company_ids = $record->companies->pluck('id')->toArray();
                if(in_array(auth()->user()->company_id, $company_ids)){
                    return true;
                }
                break;

            case 4: // Public events

                return true;

            default:
                return false;
        }
    }
}
