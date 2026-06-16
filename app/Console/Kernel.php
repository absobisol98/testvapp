<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Shift reminders — runs daily at 8 AM, covers shifts starting the next day
        $schedule->command('events:send-shift-reminders')->dailyAt('08:00');

        // Completion summaries — runs every hour to catch shifts that ended ~1hr ago
        $schedule->command('events:send-completion-summaries')->hourly();

        // Daily registration digest — sent to admins at 7 AM each morning
        $schedule->command('events:send-daily-digest')->dailyAt('07:00');

        // Re-cache Filament components nightly to prevent discovery timeouts
        $schedule->command('filament:optimize')->dailyAt('03:00');

        // Process queued emails/notifications every minute. Exits as soon as the
        // queue is empty (no persistent daemon) and is capped at 50s so it never
        // overlaps the next run or competes with PHP's max_execution_time.
        $schedule->command('queue:work --stop-when-empty --max-time=50 --tries=3')
            ->everyMinute()
            ->withoutOverlapping()
            ->runInBackground();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
