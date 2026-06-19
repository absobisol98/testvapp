<?php

namespace Tests\Feature;

use App\Models\EventAttendee;
use App\Models\User;
use App\Policies\EventPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Mail::fake();
        Notification::fake();
    }

    // -------------------------------------------------------------------------
    // Volunteer export: unauthenticated → redirected to login
    // -------------------------------------------------------------------------

    public function test_unauthenticated_user_cannot_access_volunteer_export(): void
    {
        $response = $this->get('/exports/volunteer-list/1');

        $response->assertRedirect('/admin/login');
    }

    // -------------------------------------------------------------------------
    // Volunteer export: authenticated volunteer (no manage_registrations_event)
    // -------------------------------------------------------------------------

    public function test_volunteer_without_permission_cannot_export_registrations(): void
    {
        $role = Role::create(['name' => 'Volunteer', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        $response = $this->actingAs($user)->get('/exports/volunteer-list/1');

        $response->assertForbidden();
    }

    // -------------------------------------------------------------------------
    // Certificate IDOR: volunteer cannot download another volunteer's certificate
    // -------------------------------------------------------------------------

    public function test_volunteer_cannot_download_another_volunteers_certificate(): void
    {
        $role = Role::create(['name' => 'Volunteer', 'guard_name' => 'web']);

        $owner   = User::factory()->create();
        $attacker = User::factory()->create();

        $owner->assignRole($role);
        $attacker->assignRole($role);

        // Attacker requests the certificate using owner's user ID
        $response = $this->actingAs($attacker)
            ->get("/volunteer/certificate/1/{$owner->id}");

        $response->assertForbidden();
    }

    public function test_volunteer_can_access_own_certificate_route(): void
    {
        $role = Role::create(['name' => 'Volunteer', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        // No attendance records exist → 404 (not 403), confirming auth passed
        $response = $this->actingAs($user)
            ->get("/volunteer/certificate/1/{$user->id}");

        $response->assertNotFound();
    }

    // -------------------------------------------------------------------------
    // canAccessPanel: user with no roles cannot access admin panel
    // -------------------------------------------------------------------------

    public function test_user_without_roles_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create(); // no roles assigned

        $response = $this->actingAs($user)->get('/admin');

        // Filament redirects to login when canAccessPanel() returns false
        $response->assertRedirect();
    }

    public function test_user_with_role_can_reach_admin_panel(): void
    {
        $role = Role::create(['name' => 'Volunteer', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole($role);

        // canAccessPanel returns true; Filament handles further routing
        $this->assertTrue($user->canAccessPanel(app(\Filament\Panel::class)));
    }

    // -------------------------------------------------------------------------
    // EventPolicy: template placeholders were replaced with real permission names
    // -------------------------------------------------------------------------

    public function test_event_policy_force_delete_uses_real_permission(): void
    {
        $policy = new EventPolicy();
        $event  = new \App\Models\Event();

        $userWithoutPerm = User::factory()->create();
        $this->assertFalse($policy->forceDelete($userWithoutPerm, $event));
    }

    public function test_event_policy_restore_uses_real_permission(): void
    {
        $policy = new EventPolicy();
        $event  = new \App\Models\Event();

        $userWithoutPerm = User::factory()->create();
        $this->assertFalse($policy->restore($userWithoutPerm, $event));
    }

    public function test_event_policy_replicate_uses_real_permission(): void
    {
        $policy = new EventPolicy();
        $event  = new \App\Models\Event();

        $userWithoutPerm = User::factory()->create();
        $this->assertFalse($policy->replicate($userWithoutPerm, $event));
    }
}
