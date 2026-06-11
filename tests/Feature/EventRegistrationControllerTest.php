<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Models\EventRegistration;
use App\Models\EventSlot;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventRegistrationControllerTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function makeUser(array $overrides = []): User
    {
        return User::factory()->create($overrides);
    }

    private function makeAdultUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'birthday' => Carbon::now()->subYears(25)->toDateString(),
        ], $overrides));
    }

    private function makeMinorUser(array $overrides = []): User
    {
        return User::factory()->minor()->create($overrides);
    }

    /**
     * Create an event without triggering the EventObserver side effects
     * (MessageRoom, Survey creation).
     */
    private function makeEvent(array $overrides = []): Event
    {
        return Event::withoutEvents(function () use ($overrides) {
            return Event::create(array_merge([
                'title' => 'Test Event',
                'is_published' => true,
                'approval_type' => 'Requires Approval',
                'attachment_required' => false,
                'registration_end_date' => Carbon::now()->addDays(7),
            ], $overrides));
        });
    }

    private function makeSlot(Event $event, int $totalSlots = 10): EventSlot
    {
        return EventSlot::create([
            'event_id' => $event->id,
            'slot_type_id' => 1,
            'total_slots' => $totalSlots,
            'shift_name' => 'Morning Shift',
        ]);
    }

    private function registerUrl(Event $event, EventSlot $slot): string
    {
        return route('event.register-slot', [$event, $slot]);
    }

    // -------------------------------------------------------------------------
    // Registration: access control
    // -------------------------------------------------------------------------

    public function test_unauthenticated_user_is_redirected_away_from_registration(): void
    {
        $event = $this->makeEvent();
        $slot  = $this->makeSlot($event);

        $this->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id]);
    }

    // -------------------------------------------------------------------------
    // Registration: validation
    // -------------------------------------------------------------------------

    public function test_privacy_policy_is_required(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent();
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), [])
             ->assertSessionHasErrors('privacy_policy');

        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id]);
    }

    public function test_minor_must_upload_parental_consent(): void
    {
        $minor = $this->makeMinorUser();
        $event = $this->makeEvent(['attachment_required' => false]);
        $slot  = $this->makeSlot($event);

        $this->actingAs($minor)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertSessionHasErrors('media');

        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id]);
    }

    public function test_event_with_attachment_required_rejects_registration_without_file(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent(['attachment_required' => true]);
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertSessionHasErrors('media');

        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id]);
    }

    public function test_disallowed_file_type_is_rejected(): void
    {
        Storage::fake('public');

        $user  = $this->makeAdultUser();
        $event = $this->makeEvent(['attachment_required' => true]);
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), [
                 'privacy_policy' => '1',
                 'media' => [
                     UploadedFile::fake()->create('exploit.exe', 100, 'application/x-msdownload'),
                 ],
             ])
             ->assertSessionHasErrors('media.*');

        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id]);
    }

    public function test_file_exceeding_10mb_is_rejected(): void
    {
        Storage::fake('public');

        $user  = $this->makeAdultUser();
        $event = $this->makeEvent(['attachment_required' => true]);
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), [
                 'privacy_policy' => '1',
                 'media' => [
                     UploadedFile::fake()->create('large.pdf', 11_000, 'application/pdf'), // 11 MB
                 ],
             ])
             ->assertSessionHasErrors('media.*');

        $this->assertDatabaseMissing('event_registrations', ['event_id' => $event->id]);
    }

    // -------------------------------------------------------------------------
    // Registration: happy path
    // -------------------------------------------------------------------------

    public function test_authenticated_adult_can_register_for_an_open_slot(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent(['approval_type' => 'Requires Approval']);
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseHas('event_registrations', [
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 1, // Pending
        ]);
    }

    public function test_automatic_approval_creates_attendee_record(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent(['approval_type' => 'Automatic']);
        $slot  = $this->makeSlot($event);

        // GenerateEventQRCode reads a real logo file; skip QR assertion, focus on DB state.
        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseHas('event_registrations', [
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
            'status_id'    => 2, // Approved
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id'    => $event->id,
            'attendee_id' => $user->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Registration: business rules
    // -------------------------------------------------------------------------

    public function test_duplicate_registration_is_rejected(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent();
        $slot  = $this->makeSlot($event);

        EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 1, // Pending
        ]);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseCount('event_registrations', 1);
    }

    public function test_approved_registration_also_blocks_re_registration(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent();
        $slot  = $this->makeSlot($event);

        EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 2, // Approved
        ]);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseCount('event_registrations', 1);
    }

    public function test_registration_is_rejected_after_deadline(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent([
            'registration_end_date' => Carbon::now()->subDay(), // deadline passed
        ]);
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseMissing('event_registrations', [
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
        ]);
    }

    public function test_registration_succeeds_when_deadline_is_in_the_future(): void
    {
        $user  = $this->makeAdultUser();
        $event = $this->makeEvent([
            'registration_end_date' => Carbon::now()->addDay(),
        ]);
        $slot  = $this->makeSlot($event);

        $this->actingAs($user)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseHas('event_registrations', [
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
        ]);
    }

    public function test_full_slot_blocks_further_registrations(): void
    {
        $existingUser = $this->makeAdultUser(['email' => 'existing@example.com']);
        $newUser      = $this->makeAdultUser(['email' => 'new@example.com']);
        $event        = $this->makeEvent();
        $slot         = $this->makeSlot($event, 1); // capacity of 1

        EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $existingUser->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 1, // Pending (counts toward capacity)
        ]);

        $this->actingAs($newUser)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseMissing('event_registrations', [
            'event_id'     => $event->id,
            'volunteer_id' => $newUser->id,
        ]);
    }

    public function test_rejected_registration_does_not_count_toward_slot_capacity(): void
    {
        $rejectedUser = $this->makeAdultUser(['email' => 'rejected@example.com']);
        $newUser      = $this->makeAdultUser(['email' => 'new@example.com']);
        $event        = $this->makeEvent();
        $slot         = $this->makeSlot($event, 1); // capacity of 1

        // A rejected registration (status_id = 3) should NOT count toward capacity
        EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $rejectedUser->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 3, // Rejected
        ]);

        $this->actingAs($newUser)
             ->post($this->registerUrl($event, $slot), ['privacy_policy' => '1'])
             ->assertRedirect();

        $this->assertDatabaseHas('event_registrations', [
            'event_id'     => $event->id,
            'volunteer_id' => $newUser->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // Cancellation
    // -------------------------------------------------------------------------

    public function test_user_can_cancel_their_own_pending_registration(): void
    {
        $user         = $this->makeAdultUser();
        $event        = $this->makeEvent();
        $slot         = $this->makeSlot($event);
        $registration = EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 1,
        ]);

        $this->actingAs($user)
             ->delete(route('event.cancel-registration', $registration))
             ->assertRedirect();

        $this->assertDatabaseMissing('event_registrations', ['id' => $registration->id]);
    }

    public function test_user_cannot_cancel_another_users_registration(): void
    {
        $owner      = $this->makeAdultUser(['email' => 'owner@example.com']);
        $intruder   = $this->makeAdultUser(['email' => 'intruder@example.com']);
        $event      = $this->makeEvent();
        $slot       = $this->makeSlot($event);
        $registration = EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $owner->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 1,
        ]);

        $this->actingAs($intruder)
             ->delete(route('event.cancel-registration', $registration))
             ->assertRedirect();

        $this->assertDatabaseHas('event_registrations', ['id' => $registration->id]);
    }

    public function test_rejected_registration_cannot_be_cancelled(): void
    {
        $user         = $this->makeAdultUser();
        $event        = $this->makeEvent();
        $slot         = $this->makeSlot($event);
        $registration = EventRegistration::create([
            'event_id'     => $event->id,
            'volunteer_id' => $user->id,
            'slot_type_id' => $slot->id,
            'status_id'    => 3, // Rejected
        ]);

        $this->actingAs($user)
             ->delete(route('event.cancel-registration', $registration))
             ->assertRedirect();

        $this->assertDatabaseHas('event_registrations', ['id' => $registration->id]);
    }
}
