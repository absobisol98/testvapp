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
        Schema::table('event_attendees', function (Blueprint $table) {
            //
            $table->unsignedInteger('slot_type_id')
            ->nullable()
            ->after('facilitator_id');

            // Add foreign key constraint
            $table->foreign('slot_type_id')
                    ->references('id')
                    ->on('event_slots')
                    ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            //
        });
    }
};
