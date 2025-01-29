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
        Schema::create('message_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('event_id'); // foreign key column as an integer
            $table->foreign('event_id')->references('id')->on('events')->cascadeOnDelete(); // foreign key
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_rooms');
    }
};
