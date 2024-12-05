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

final class EventFillFormAction
{
    public function execute($record,$data)
    {
        $data['date'] = $record->start_date;
        $data['start_time'] = $record->start_date;
        $data['end_time'] = $record->end_date;

        // Tags
        $data['tags'] = $record->tags->pluck('name')->toArray();

        // Companies
        $data['companies'] = $record->companies->pluck('id')->toArray();

        // Slots
        $data['slots'] = $record->slots->toArray();

        // attachments
        $media = [];
        $media_banner = [];

        foreach ($record->getMedia('event-attachments') as $media_item) {
            $index = strlen(storage_path('app/public/'));
            $media[] = substr($media_item->getPath(), $index);
        }
        foreach ($record->getMedia('event-banner-attachments') as $media_item) {
            $index = strlen(storage_path('app/public/'));
            $media_banner[] = substr($media_item->getPath(), $index);
        }

        $data['media'] = $media;
        $data['media_banner'] = $media_banner;

        return $data;
    }
}
