<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\VolunteerResource;
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

    protected function authorizeAccess(): void
    {
        // Skip canViewAny() — volunteers must be able to edit their own profile
        // even though they cannot list all users.
        abort_unless(static::getResource()::canEdit($this->getRecord()), 403);
    }

    protected function getHeaderActions(): array
    {
        $isAdmin = auth()->user()?->isAdminRole();

        $actions = [
            Actions\ActionGroup::make(array_filter([
                Actions\EditAction::make()
                    ->label('Change password')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->required(),
                        Forms\Components\TextInput::make('passwordConfirmation')
                            ->password()
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->revealable()
                            ->same('password')
                            ->required(),
                    ])
                    ->modalWidth(Support\Enums\MaxWidth::Medium)
                    ->modalHeading('Update Password')
                    ->modalDescription(fn($record) => $record->email)
                    ->modalAlignment(Alignment::Center)
                    ->modalCloseButton(false)
                    ->modalSubmitActionLabel('Submit')
                    ->modalCancelActionLabel('Cancel'),

                $isAdmin ? Actions\DeleteAction::make()
                    ->extraAttributes(["class" => "border-b"]) : null,

                $isAdmin ? Actions\CreateAction::make()
                    ->label('Create new user')
                    ->url(fn(): string => static::$resource::getNavigationUrl() . '/create') : null,
            ]))
            ->icon('heroicon-m-ellipsis-horizontal')
            ->hiddenLabel()
            ->button()
            ->tooltip('More Actions')
            ->color('gray')
        ];

        return $isAdmin
            ? array_merge($this->getNavigationActions(), $actions)
            : $actions;
    }

    protected function getRedirectUrl(): string
    {
        $user = auth()->user();

        if ($user?->isAdminRole()) {
            return $this->getResource()::getUrl('index');
        }

        // Non-admins return to their own volunteer profile
        return VolunteerResource::getUrl('view', ['record' => $user->id]);
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
}
