<?php

namespace App\Console\Commands;

use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDailyDigest extends Command
{
    protected $signature   = 'events:send-daily-digest';
    protected $description = 'Send daily registration digest to admins';

    public function handle(): int
    {
        $yesterday = Carbon::yesterday();

        $registrations = EventRegistration::with(['volunteer', 'event', 'event_slot'])
            ->whereDate('created_at', $yesterday)
            ->get();

        if ($registrations->isEmpty()) {
            $this->info('No new registrations yesterday — digest skipped.');
            return self::SUCCESS;
        }

        // Group by event for the digest body
        $grouped = $registrations->groupBy('event_id');

        $admins = User::role(['Ayala Super Admin', 'admin'])->get();

        $sentCount = 0;
        foreach ($admins as $admin) {
            try {
                Mail::send([], [], function ($message) use ($admin, $grouped, $registrations, $yesterday) {
                    $date = $yesterday->format('F d, Y');
                    $total = $registrations->count();

                    $lines = ["<h2>Volunteer Registration Digest — {$date}</h2>"];
                    $lines[] = "<p>Total new registrations yesterday: <strong>{$total}</strong></p>";
                    $lines[] = '<hr>';

                    foreach ($grouped as $eventId => $regs) {
                        $eventTitle = $regs->first()->event?->title ?? "Event #{$eventId}";
                        $lines[] = "<h3>{$eventTitle} ({$regs->count()} registrant(s))</h3><ul>";
                        foreach ($regs as $reg) {
                            $name  = optional($reg->volunteer)->firstname . ' ' . optional($reg->volunteer)->lastname;
                            $shift = optional($reg->event_slot)->shift_name ?? 'N/A';
                            $lines[] = "<li>{$name} — Shift: {$shift}</li>";
                        }
                        $lines[] = '</ul>';
                    }

                    $body = implode("\n", $lines);

                    $message
                        ->to($admin->email, $admin->firstname . ' ' . $admin->lastname)
                        ->subject("Daily Digest: {$total} new registration(s) on {$date}")
                        ->html($body);
                });

                $sentCount++;
            } catch (\Exception $e) {
                Log::error('Daily digest failed', [
                    'admin_id' => $admin->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        $this->info("Daily digest sent to {$sentCount} admin(s). Total registrations: {$registrations->count()}");

        return self::SUCCESS;
    }
}
