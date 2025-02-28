<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use Filament\Support;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use JoseEspinal\RecordNavigation\Traits\HasRecordNavigation;

class EditUser extends EditRecord
{
    use HasRecordNavigation;

    protected static string $resource = UserResource::class;



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string|Htmlable
    {
        $title = $this->record->name;
        $badge = $this->getBadgeStatus();

        return new HtmlString("
            <div class='flex items-center space-x-2'>
                <div>$title</div>
                $badge
            </div>
        ");
    }

    public function getBadgeStatus(): string|Htmlable
    {
        if (empty($this->record->email_verified_at)) {
            $badge = "<span class='inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md text-danger-700 bg-danger-50 ring-1 ring-inset ring-danger-600/20'>Unverified</span>";
        } else {
            $badge = "<span class='inline-flex items-center px-2 py-1 text-xs font-semibold rounded-md text-success-700 bg-success-50 ring-1 ring-inset ring-success-600/20'>Verified</span>";
        }

        return $badge;
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

}
