<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreign(['event_type_id'], 'events_ibfk_1')->references(['id'])->on('event_types')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['approval_status_id'], 'events_ibfk_2')->references(['id'])->on('event_approval_status')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['point_of_contact_id'], 'events_ibfk_3')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['program_id'], 'events_ibfk_4')->references(['id'])->on('programs')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['recurrence_type_id'], 'events_ibfk_5')->references(['id'])->on('event_recurrence_types')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_ibfk_1');
            $table->dropForeign('events_ibfk_2');
            $table->dropForeign('events_ibfk_3');
            $table->dropForeign('events_ibfk_4');
            $table->dropForeign('events_ibfk_5');
        });
    }
};
