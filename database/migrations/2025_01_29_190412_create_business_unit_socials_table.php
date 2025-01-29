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
        Schema::create('business_unit_socials', function (Blueprint $table) {
            $table->id();
            $table->string('social');
            $table->string('link');
            $table->unsignedBigInteger('business_unit_id')->nullable();
            $table->foreign(['business_unit_id'], 'business_unit_socials_business_unit_fk')->references(['id'])->on('business_units')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_unit_socials');
    }
};
