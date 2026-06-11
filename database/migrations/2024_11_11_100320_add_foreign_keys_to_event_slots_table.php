<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('event_slots', function (Blueprint $table) {
            $table->foreign(['event_id'], 'event_slots_ibfk_1')->references(['id'])->on('events')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['slot_type_id'], 'event_slots_ibfk_2')->references(['id'])->on('event_slot_types')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('event_slots', function (Blueprint $table) {
            $table->dropForeign('event_slots_ibfk_1');
            $table->dropForeign('event_slots_ibfk_2');
        });
    }
};
