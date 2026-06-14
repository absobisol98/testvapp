<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove duplicate rows before adding constraint (keep earliest)
        \DB::statement("
            DELETE er1 FROM event_registrations er1
            INNER JOIN event_registrations er2
            WHERE er1.id > er2.id
              AND er1.event_id = er2.event_id
              AND er1.volunteer_id = er2.volunteer_id
              AND er1.slot_type_id = er2.slot_type_id
        ");

        Schema::table('event_registrations', function (Blueprint $table) {
            $table->unique(['event_id', 'volunteer_id', 'slot_type_id'], 'event_reg_unique');
        });
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropUnique('event_reg_unique');
        });
    }
};
