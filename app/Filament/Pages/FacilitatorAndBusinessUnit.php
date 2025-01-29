<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\Facilitators;
use App\Filament\Widgets\BusinessUnit;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class FacilitatorAndBusinessUnit extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.facilitator-and-business-unit';

    protected static ?string $slug = 'business-unit';

    protected static ?string $title = 'Business Partners';

    protected static ?string $navigationLabel = 'Business Partners';


    protected function getHeaderWidgets(): array
    {
        return [
            BusinessUnit::class,
        ];
    }

}
