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
        Schema::create('event_approval_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $data = [
            ['name' => "Pending"],
            ['name' => "Approved"],
            ['name' => "Rejected"],
        ];

        DB::table('event_approval_status')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_approval_status');
    }
};
