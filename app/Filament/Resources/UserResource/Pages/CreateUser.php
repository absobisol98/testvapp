<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Settings\MailSettings;
use Exception;
use Filament\Facades\Filament;
use Filament\Notifications\Auth\VerifyEmail;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $user = $this->record;
        $settings = app(MailSettings::class);

        $volunteerRole = Role::where('name', 'Volunteer')->first();
        if ($volunteerRole) {
            $this->record->assignRole($volunteerRole);
        }

        Log::info("Creating new user: {$user->email}, attempting verification email");

        try {
            if (!method_exists($user, 'notify')) {
                $userClass = $user::class;
                Log::error("Model [{$userClass}] does not have a [notify()] method.");
                throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
            }

            if ($settings->isMailSettingsConfigured()) {
                Log::info("Mail settings configured for new user.");

                // This is a key difference - use your custom notification for consistency
                $notification = new \App\Notifications\VerifyEmailNotification();

                $settings->loadMailSettingsToConfig();

                Log::info("Mail config loaded for new user: " . json_encode([
                    'driver' => config('mail.mailer'),
                    'host' => config('mail.host'),
                    'port' => config('mail.port'),
                    'from_address' => config('mail.from.address'),
                ]));

                $user->notify($notification);
                Log::info("Verification notification sent to new user: {$user->email}");

                Notification::make()
                    ->title(__('resource.user.notifications.verify_sent.title'))
                    ->success()
                    ->send();
            } else {
                Log::warning("Mail settings not configured for new user");

                Notification::make()
                    ->title(__('resource.user.notifications.verify_warning.title'))
                    ->body(__('resource.user.notifications.verify_warning.description'))
                    ->warning()
                    ->send();
            }
        } catch (\Exception $e) {
            Log::error("Error in afterCreate sending verification: {$e->getMessage()}", [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            Notification::make()
                ->title("Error sending verification email")
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
