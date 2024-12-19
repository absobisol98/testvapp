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
            $table->boolean('is_approve')->default(false);
            $table->dateTime('updated_at')->nullable();
            $table->char('updated_by', 36)->nullable()->index('updated_by');
            $table->foreign(['updated_by'], 'event_attendees_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            $table->dropForeign('event_attendees_ibfk_1');
            $table->dropColumn('is_approve');
            $table->dropColumn('updated_at');
            $table->dropColumn('updated_by');
        });
    }
};
