<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            if (!Schema::hasColumn('event_attendees', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('event_attendees', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
        });

        \DB::table('event_attendees')->update([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            //
        });
    }
};
