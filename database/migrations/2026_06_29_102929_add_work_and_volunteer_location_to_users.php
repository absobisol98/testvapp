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
        Schema::table('users', function (Blueprint $table) {
            $table->string('work_location')->nullable()->after('skills');
            $table->string('interests')->nullable()->after('work_location');
            $table->string('volunteer_location')->nullable()->after('interests');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['work_location', 'interests', 'volunteer_location']);
        });
    }
};
