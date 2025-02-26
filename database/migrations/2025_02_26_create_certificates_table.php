<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->unsignedInteger('event_id');
            $table->foreignUuid('attendee_id')->constrained('users');
            $table->unsignedInteger('slot_type_id');  // Changed to match event_slots table
            $table->integer('hours_served');
            $table->timestamp('issued_at');
            $table->timestamps();

            // Add foreign key constraints separately
            $table->foreign('event_id')
                  ->references('id')
                  ->on('events')
                  ->onDelete('cascade');

            $table->foreign('slot_type_id')
                  ->references('id')
                  ->on('event_slots')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
