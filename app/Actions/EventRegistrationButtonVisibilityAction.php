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
        $user = auth()->user();

        switch ($record->event_type_id) {
            case 1: // Exclusive to Ayala Employees
                return $user->affiliate_type_id == 1;

            case 2: // Exclusive to Business Unit
                // Volunteer's company must be in the event's nominated companies list
                if ($user->company_id) {
                    return $record->companies->contains('id', $user->company_id);
                }
                return false;

            case 3: // Hybrid — nominated companies list
                if ($user->company_id) {
                    return $record->companies->contains('id', $user->company_id);
                }
                return false;

            case 4: // Public
                return true;

            default:
                return false;
        }
    }
}
