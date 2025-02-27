<x-filament::page>
    <form method="POST" action="{{ route('change-password.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        <x-filament::card>
            <div class="space-y-6">
                <!-- Current Password -->
                <div>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="password"
                            name="current_password"
                            label="Current Password"
                            required
                        />
                    </x-filament::input.wrapper>
                </div>

                <!-- New Password -->
                <div>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="password"
                            name="password"
                            label="New Password"
                            required
                        />
                    </x-filament::input.wrapper>
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-filament::input.wrapper>
                        <x-filament::input
                            type="password"
                            name="password_confirmation"
                            label="Confirm New Password"
                            required
                        />
                    </x-filament::input.wrapper>
                </div>
            </div>

            <div class="mt-6">
                <x-filament::button type="submit">
                    Change Password
                </x-filament::button>
            </div>
        </x-filament::card>
    </form>
</x-filament::page>
