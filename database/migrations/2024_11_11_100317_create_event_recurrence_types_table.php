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
        Schema::create('event_recurrence_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $data = [
            ['name' => "One-time"],
            ['name' => "Recurring"],
        ];

        DB::table('event_recurrence_types')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_recurrence_types');
    }
};
