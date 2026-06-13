<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Vite assets are not compiled in the test environment
        $this->withoutVite();
        // Prevent real mail/notifications from firing (e.g. EmailTemplates plugin)
        Mail::fake();
        Notification::fake();
    }

    public function test_login_page_loads(): void
    {
        $this->get('/admin/login')->assertStatus(200);
    }

    public function test_valid_credentials_redirect_to_dashboard(): void
    {
        $user = User::factory()->create();

        Livewire::test(\Filament\Pages\Auth\Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors()
            ->assertRedirect('/admin');
    }

    public function test_invalid_password_is_rejected(): void
    {
        $user = User::factory()->create();

        Livewire::test(\Filament\Pages\Auth\Login::class)
            ->set('data.email', $user->email)
            ->set('data.password', 'wrong-password')
            ->call('authenticate')
            ->assertHasErrors();
    }

    public function test_nonexistent_email_is_rejected(): void
    {
        Livewire::test(\Filament\Pages\Auth\Login::class)
            ->set('data.email', 'nobody@example.com')
            ->set('data.password', 'password')
            ->call('authenticate')
            ->assertHasErrors();
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get('/admin/login')
             ->assertRedirect('/admin');
    }

    public function test_unauthenticated_user_cannot_access_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }
}
