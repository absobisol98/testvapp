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
            $table->text('shift_name')->after('id')->nullable();
            $table->text('responsibilities')->after('total_slots')->nullable();

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
