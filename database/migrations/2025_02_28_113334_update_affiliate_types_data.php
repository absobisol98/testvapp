<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update users with affiliate_type_id = 2 to 3 (temporary to avoid conflicts)
        DB::table('users')
            ->where('affiliate_type_id', 2)
            ->update(['affiliate_type_id' => 3]);

        // Delete 'Accredited External Partner Volunteer'
        DB::table('affiliate_types')
            ->where('name', 'Accredited External Partner Volunteer')
            ->delete();

        // Update Non-Ayala ID to 2
        DB::table('affiliate_types')
            ->where('name', 'Non-Ayala')
            ->update(['id' => 2]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore original state
        DB::table('affiliate_types')->insert([
            'id' => 2,
            'name' => 'Accredited External Partner Volunteer'
        ]);

        DB::table('affiliate_types')
            ->where('name', 'Non-Ayala')
            ->update(['id' => 3]);

        // Restore user affiliations
        DB::table('users')
            ->where('affiliate_type_id', 3)
            ->update(['affiliate_type_id' => 2]);
    }
};
