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
        Schema::create('affiliate_types', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name');
        });

        DB::table('affiliate_types')->insert([
            ['name' => 'Ayala'],
            ['name' => 'Accredited External Partner Volunteer'],
            ['name' => 'Non-Ayala'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_types');
    }
};
