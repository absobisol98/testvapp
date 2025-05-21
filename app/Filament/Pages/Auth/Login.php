<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BasePage;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use DiogoGPinto\AuthUIEnhancer\Pages\Auth\Concerns\HasCustomLayout;
use Illuminate\Support\HtmlString;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Actions\Action;
use Illuminate\Support\Str;
class Login extends BasePage
{

    use HasCustomLayout;

    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => '',
            'password' => '',
            'timestamp' => now()->timestamp,
            'honeypot' => '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent()->label('Email Address'),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Hidden::make('timestamp'),
                Hidden::make('honeypot')
                    ->dehydrated(true),
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
    protected function getAuthenticateFormAction(): Action
    {
        return Action::make('authenticate')
            ->label(__('Login'))
            ->action(function () {
                try {
                    $formData = $this->form->getState();

                    if (!isset($formData['timestamp'])) {
                        $formData['timestamp'] = now()->timestamp - 10; // Default to 10 seconds ago if missing
                    }
                    
                    if (!isset($formData['honeypot'])) {
                        $formData['honeypot'] = '';
                    }
                    
                    // Check honeypot - if filled, it's likely a bot
                    if (!empty($formData['honeypot'])) {
                        // Sleep to slow down potential attacks
                        sleep(2);
                        
                        Notification::make()
                            ->title('Invalid request')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Check submission speed - too fast means likely a bot
                    // Real humans take at least a few seconds to fill the form
                    if ((now()->timestamp - $formData['timestamp']) < 3) {
                        // Sleep to slow down potential attacks
                        sleep(2);
                        
                        Notification::make()
                            ->title('Please try again')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    $credentials = $this->getCredentialsFromForm();
                    
                    // Rate limiting: maximum 5 attempts per minute per IP
                    $ipAddress = request()->ip();
                    $throttleKey = Str::transliterate('login|' . $ipAddress);
                    
                    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                        $seconds = RateLimiter::availableIn($throttleKey);
                        
                        Notification::make()
                            ->title("Too many login attempts. Please try again in {$seconds} seconds.")
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Check if email exists
                    $user = User::where('email', $credentials['email'])->first();
                    
                    // If user not found or password is invalid, show error
                    if (!$user || !Auth::validate([
                        'email' => $credentials['email'],
                        'password' => $credentials['password'],
                    ])) {
                        // Increment the rate limiter only on failed attempts
                        RateLimiter::hit($throttleKey, 60);
                        
                        Notification::make()
                            ->title('Invalid credentials')
                            ->danger()
                            ->send();
                        return;
                    }
                    
                    // Reset the rate limiter after successful login
                    RateLimiter::clear($throttleKey);

                    // Check if user requires OTP (for high-privileged accounts)
                    if ($this->isHighPrivilegedAccount($user)) {
                        // Store credentials temporarily
                        session([
                            'pending_login_credentials' => $credentials,
                            'pending_login_user_id' => $user->id
                        ]);

                        // Generate and send OTP
                        $this->sendOTP($user);
                  
                        Notification::make()
                            ->title('OTP has been sent to your email')
                            ->success()
                            ->send();

                        // Use Livewire redirect to OTP page
                        return $this->redirect(route('filament.admin.auth.otp'));
                    }

                    // For regular users, proceed with normal login
                    // Instead of Auth::attempt which already logs the user in,
                    // use Auth::login to explicitly control the login timing
                    Auth::login($user, $credentials['remember'] ?? false);
                    session()->regenerate();
                    
                    // Redirect to dashboard
                    return $this->redirect(route('filament.admin.pages.dashboard'));
                    
                } catch (\Exception $e) {
                    Notification::make()
                        ->title($e->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }

    // This method is still required for type compatibility,
    // but we're not using it directly
    public function authenticate(): ?LoginResponse
    {
        return null;
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

    public function renderingWithForm(): void
    {
        $this->dispatch('add-honeypot-styles', [
            'styles' => '
                .fi-fo-field-wrp-honeypot {
                    position: absolute !important;
                    width: 1px !important;
                    height: 1px !important;
                    padding: 0 !important;
                    margin: -1px !important;
                    overflow: hidden !important;
                    clip: rect(0, 0, 0, 0) !important;
                    white-space: nowrap !important;
                    border: 0 !important;
                }
            ',
        ]);
    }
}
