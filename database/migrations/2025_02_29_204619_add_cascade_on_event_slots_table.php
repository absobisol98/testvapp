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
        Schema::table('event_slots', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign('event_slots_ibfk_1');

            // Add the constraint back with ON DELETE CASCADE
            $table->foreign('event_id')
                  ->references('id')->on('events')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_slots', function (Blueprint $table) {
            //
        });
    }
};
