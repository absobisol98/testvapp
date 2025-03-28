<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Notification;
use Filament\Pages\SimplePage;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;

class OtpVerification extends SimplePage
{
    protected static string $view = 'filament.pages.auth.otp-verification';

    public ?array $data = [];

    public function mount(): void
    {
        if (!session()->has('pending_login_user_id')) {
            redirect()->route('filament.admin.auth.login');
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('otp')
                    ->label('One-Time Password')
                    ->placeholder('Enter the 6-digit code sent to your email')
                    ->required()
                    ->length(6)
                    ->autofocus(),
            ])
            ->statePath('data');
    }

    public function verifyOtp()
    {
        try {
            $data = $this->form->getState();
            $otp = $data['otp'];

            $userId = session('pending_login_user_id');
            $user = User::find($userId);

            if (!$user) {
                throw new Halt('Invalid session. Please try logging in again.');
            }

            // Verify OTP
            if ($user->otp !== $otp || now()->isAfter($user->otp_expires_at)) {
                throw new Halt('Invalid or expired OTP. Please try again.');
            }

            // Clear OTP
            $user->update([
                'otp' => null,
                'otp_expires_at' => null
            ]);

            // Get stored credentials and authenticate
            $credentials = session('pending_login_credentials');
            Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ], isset($credentials['remember']));

            // Clear session data
            session()->forget(['pending_login_user_id', 'pending_login_credentials']);

            // Redirect to dashboard
            return app(LoginResponse::class);

        } catch (Halt $e) {
            Notification::make()
                ->title($e->getMessage())
                ->danger()
                ->send();

            $this->form->fill();
        }
    }

    public function resendOtp()
    {
        $userId = session('pending_login_user_id');
        $user = User::find($userId);

        if (!$user) {
            Notification::make()
                ->title('Invalid session. Please try logging in again.')
                ->danger()
                ->send();

            return redirect()->route('filament.admin.auth.login');
        }

        // Generate a new OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update OTP and expiration
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send new OTP
        $user->notify(new \App\Notifications\OtpLoginNotification($otp));

        Notification::make()
            ->title('A new verification code has been sent to your email')
            ->success()
            ->send();
    }
}
