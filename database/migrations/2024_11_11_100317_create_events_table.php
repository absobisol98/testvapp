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
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('event_type_id')->nullable()->index('event_type_id');
            $table->unsignedInteger('recurrence_type_id')->nullable()->index('recurrence_type_id');
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('point_of_contact_id', 36)->nullable()->index('point_of_contact_id');
            $table->integer('program_id')->nullable()->index('events_ibfk_4');
            $table->text('tags')->nullable();
            $table->text('location')->nullable();
            $table->unsignedInteger('approval_status_id')->nullable()->index('approval_status_id');
            $table->boolean('sign_up_approval_required')->nullable()->default(false);
            $table->boolean('attachment_required')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
