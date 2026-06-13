<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Unique volunteer identifier in V-00x format
            $table->string('volunteer_id', 20)->nullable()->unique()->after('id');
            // Multi-select program interest categories (not the same as program_id FK)
            $table->json('program_interests')->nullable()->after('other_program');
            // Free-text skill tags; will be migrated to pivot table when Skills table is built
            $table->json('skills')->nullable()->after('program_interests');
        });

        // Backfill existing users with sequential V-IDs ordered by registration date
        $users = DB::table('users')->orderBy('created_at')->pluck('id');
        $counter = 1;
        foreach ($users as $id) {
            DB::table('users')->where('id', $id)->update([
                'volunteer_id' => 'V' . str_pad($counter++, 3, '0', STR_PAD_LEFT),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['volunteer_id', 'program_interests', 'skills']);
        });
    }
};
