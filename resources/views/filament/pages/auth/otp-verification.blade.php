<x-filament-panels::page.simple :heading="'OTP Verification'">
    <x-filament-panels::form wire:submit="verifyOtp">
        <div class="space-y-6">
            <div class="space-y-2 text-center">
                <h1 class="text-2xl font-bold tracking-tight">
                    Two-factor verification
                </h1>
                <p class="text-gray-500">
                    To continue, please enter the verification code we sent to your email.
                </p>
            </div>

            {{ $this->form }}

            <x-filament::button
                type="submit"
                class="w-full"
            >
                Verify and continue
            </x-filament::button>

            <div class="text-center">
                <x-filament::link
                    wire:click="resendOtp"
                    color="gray"
                    tag="button"
                    type="button"
                >
                    Didn't receive the code? Resend
                </x-filament::link>
            </div>

            <div class="text-center">
                <x-filament::link
                    href="{{ route('filament.admin.auth.login') }}"
                    color="gray"
                >
                    Back to login
                </x-filament::link>
            </div>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page.simple>
