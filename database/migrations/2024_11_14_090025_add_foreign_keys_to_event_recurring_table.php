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
        Schema::table('event_recurring', function (Blueprint $table) {
            $table->foreign(['created_by'], 'event_recurring_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['updated_by'], 'event_recurring_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_recurring', function (Blueprint $table) {
            $table->dropForeign('event_recurring_ibfk_1');
            $table->dropForeign('event_recurring_ibfk_2');
        });
    }
};
