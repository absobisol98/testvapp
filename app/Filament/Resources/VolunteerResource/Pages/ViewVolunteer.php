<?php

namespace App\Filament\Resources\VolunteerResource\Pages;

use App\Filament\Resources\VolunteerResource;
use Filament\Resources\Pages\Page;

class ViewVolunteer extends Page
{
    protected static string $resource = VolunteerResource::class;

    protected static string $view = 'filament.resources.volunteer-resource.pages.view-volunteer';
}
