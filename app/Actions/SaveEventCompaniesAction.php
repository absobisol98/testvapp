<?php

namespace App\Actions;

use App\Models\AffiliateType;
use App\Models\Cluster;
use App\Models\EventCompany;
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

final class SaveEventCompaniesAction
{
    public function execute($event,$data,$edit)
    {
        if($edit){
            EventCompany::where('event_id',$event->id)->delete();
        }

        
        if(isset($data['companies']) && $data['companies']){ // If company exists, insert companies into event_companies table

            foreach ($data['companies'] as $company) {
                EventCompany::create([
                    'event_id' => $event->id,
                    'company_id' => $company,
                ]);
            }
        }
    }
}
