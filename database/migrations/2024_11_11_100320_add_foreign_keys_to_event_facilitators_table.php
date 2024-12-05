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
        Schema::table('event_facilitators', function (Blueprint $table) {
            $table->foreign(['event_id'], 'event_facilitators_ibfk_1')->references(['id'])->on('events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['facilitator_id'], 'event_facilitators_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_facilitators', function (Blueprint $table) {
            $table->dropForeign('event_facilitators_ibfk_1');
            $table->dropForeign('event_facilitators_ibfk_2');
        });
    }
};
