<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BasePage;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Placeholder;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Illuminate\Support\HtmlString;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Actions\Action;

class Login extends BasePage
{

    use HasCustomLayout;

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => '',
            'password' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()->label('Email Address'),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Placeholder::make('data_privacy_notice')
                ->content(new HtmlString('<div class="text-sm text-gray-600 mt-1">By logging in, you accept the <a class="text-primary-600 hover:text-primary-700 hover:underline" href="' . route('data-privacy-policy') . '" target="_blank">Data Privacy of Ayala Foundation Inc.</a></div>'))
                ->disableLabel(),
                Placeholder::make('register_link')
                ->content(new HtmlString('<div class="text-center mt-4">Not a Volunteer yet? <a class="text-primary-500 font-bold hover:underline" href="' . route('volunteer.form.view') . '">Register Now</a></div>'))
                ->disableLabel(),
            ]);
    }
    protected function getCredentialsFromForm(): array
    {
        return [
            'email' => $this->form->getState()['email'],
            'password' => $this->form->getState()['password'],
            'remember' => $this->form->getState()['remember'] ?? false,
        ];
    }
    public function authenticate(): ?LoginResponse
    {
        try {
            $credentials = $this->getCredentialsFromForm();
            $user = User::where('email', $credentials['email'])->first();

            // Check if user exists and requires OTP (for high-privileged accounts)
            if ($user && $this->isHighPrivilegedAccount($user) && !app()->environment('local', 'development')) {
                // Store credentials temporarily and redirect to OTP verification
                session([
                    'pending_login_credentials' => $credentials,
                    'pending_login_user_id' => $user->id
                ]);

                // Generate and send OTP
                $this->sendOTP($user);

                // Return null and handle the redirect separately
                return null;
            }

            // Regular authentication flow for non-privileged accounts
            // or when in development environment
            return parent::authenticate();

        } catch (\Exception $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    /**
     * Override the default method to handle redirect after authenticate returns null
     */
    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('Login'))
            ->submit('authenticate')
            ->action(function () {
                $response = $this->authenticate();

                if ($response === null && session()->has('pending_login_user_id')) {
                    // If OTP flow is activated, redirect to OTP page
                    redirect()->route('filament.admin.auth.otp');
                    return;
                }

                return $response;
            });
    }


    private function isHighPrivilegedAccount(User $user): bool
    {
        // Check if user has high-privileged roles
        return $user->hasAnyRole(['super_admin', 'Ayala Super Admin', 'admin']);
    }

    private function sendOTP(User $user): void
    {
        // Generate a 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store OTP with expiration (10 minutes)
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP via email
        $user->notify(new \App\Notifications\OtpLoginNotification($otp));
    }



    public function getHeading(): string | Htmlable
    {
        return '';
    }
}
