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
        Schema::create('event_slots', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('event_id')->index('event_id');
            $table->unsignedInteger('slot_type_id')->index('slot_type_id');
            $table->unsignedInteger('total_slots');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_slots');
    }
};
