<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Mail::fake();
        Notification::fake();
        RateLimiter::clear('throttle:5,1');
    }

    // -------------------------------------------------------------------------
    // Volunteer registration: throttle:5,1 → 6th request returns 429
    // -------------------------------------------------------------------------

    public function test_volunteer_registration_is_rate_limited(): void
    {
        $payload = [
            'firstname'  => 'Test',
            'lastname'   => 'User',
            'email'      => 'test@example.com',
            'password'   => 'password123',
            'birthday'   => '1990-01-01',
        ];

        // Exhaust the 5-request allowance
        for ($i = 0; $i < 5; $i++) {
            $this->post('/volunteer-registration-store', $payload);
        }

        // The 6th request must be throttled
        $response = $this->post('/volunteer-registration-store', $payload);

        $response->assertStatus(429);
    }

    // -------------------------------------------------------------------------
    // Survey submit: throttle:10,1 → 11th request returns 429
    // -------------------------------------------------------------------------

    public function test_survey_submission_is_rate_limited(): void
    {
        $survey = \App\Models\Survey::factory()->create();

        for ($i = 0; $i < 10; $i++) {
            $this->post("/survey/{$survey->id}", ['token' => 'invalid']);
        }

        // The 11th request (even with an invalid token) must be throttled before
        // the application even processes it
        $response = $this->post("/survey/{$survey->id}", ['token' => 'invalid']);

        $response->assertStatus(429);
    }
}
