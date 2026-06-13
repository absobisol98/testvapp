<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_slots', function (Blueprint $table) {
            // Remove the boolean toggle approach in favour of explicit date columns
            if (Schema::hasColumn('event_slots', 'ends_next_day')) {
                $table->dropColumn('ends_next_day');
            }

            // Start date of this specific shift (must be within event date range)
            $table->date('shift_date')->nullable()->after('end_time');
            // End date of this shift — null means same day as shift_date
            $table->date('shift_end_date')->nullable()->after('shift_date');
            // Per-shift delivery format override: null = inherit from event, onsite, virtual
            $table->string('slot_format', 50)->nullable()->after('shift_end_date');
            // Per-shift meeting link (used when slot_format = virtual)
            $table->string('meeting_link')->nullable()->after('slot_format');
        });
    }

    public function down(): void
    {
        Schema::table('event_slots', function (Blueprint $table) {
            $table->dropColumn(['shift_date', 'shift_end_date', 'slot_format', 'meeting_link']);
            $table->boolean('ends_next_day')->default(false)->after('end_time');
        });
    }
};
