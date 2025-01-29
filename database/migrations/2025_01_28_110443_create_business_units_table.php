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
        Schema::create('business_units', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('address');
            $table->string('nickname',50);
            $table->string('slug')->unique();
            $table->longText('about')->nullable();
            $table->string('header_tagline');
            $table->longText('header_description');
            $table->string('event_heading');
            $table->string('event_description');
            $table->char('created_by')->nullable();
            $table->foreign(['created_by'], 'business_nuit_createdBy')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_units');
    }
};
