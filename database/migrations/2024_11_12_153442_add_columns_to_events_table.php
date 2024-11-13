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
            $table->dateTime('repeat_until')->nullable()->after('recurrence_type_id');
            $table->string('frequency')->nullable()->after('repeat_until');
            $table->text('selected_days')->nullable()->after('frequency');
            $table->text('monthly_days')->nullable()->after('selected_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            //
        });
    }
};
