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
        Schema::create('message_room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_room_id')->constrained()->cascadeOnDelete();
            $table->uuid('user_id'); // Use uuid() to define the column for UUID
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete(); // Foreign key reference for UUID
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_room_participants');
    }
};
