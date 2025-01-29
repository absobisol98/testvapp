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
        Schema::create('business_unit_has_external_admin', function (Blueprint $table) {
            $table->id();
            $table->char('user_id')->nullable();
            $table->unsignedBigInteger('business_unit_id')->nullable();
            $table->foreign(['business_unit_id'], 'business_unit_has_external_admin_business_unit_fk')->references(['id'])->on('business_units')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['user_id'], 'business_unit_socials_users_fk')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_unit_has_external_admin');
    }
};
