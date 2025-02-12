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
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('is_featured')->default(0)->after('is_published');
            //
        });

        Schema::table('business_units', function (Blueprint $table) {
            $table->dropColumn(['address', 'event_heading','event_description']);
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('business_units', function (Blueprint $table) {
            $table->string('address');
            $table->string('event_heading');
            $table->string('event_description');           
        });


        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_featured');
            //
        });
    }
};
