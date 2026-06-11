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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->foreign(['event_id'], 'event_registrations_ibfk_1')->references(['id'])->on('events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['slot_type_id'], 'event_registrations_ibfk_2')->references(['id'])->on('event_slots')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['volunteer_id'], 'event_registrations_ibfk_3')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropForeign('event_registrations_ibfk_1');
            $table->dropForeign('event_registrations_ibfk_2');
            $table->dropForeign('event_registrations_ibfk_3');
        });
    }
};
