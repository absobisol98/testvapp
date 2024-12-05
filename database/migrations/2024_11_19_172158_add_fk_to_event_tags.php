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
        Schema::table('event_tags', function (Blueprint $table) {
            $table->foreign(['event_id'], 'event_tags_fk')->references(['id'])->on('events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tag_id'], 'event_tag_ibfk_2')->references(['id'])->on('tags_event')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_tags', function (Blueprint $table) {
            //
        });
    }
};
