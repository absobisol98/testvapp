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
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('active_role')->nullable()->after('volunteer');
        });

        // Set default active_role based on highest priority role
        DB::statement("
            UPDATE users
            SET active_role =
                CASE
                    WHEN EXISTS (SELECT * FROM model_has_roles WHERE model_id = users.id AND role_id IN
                        (SELECT id FROM roles WHERE name = 'super_admin')) THEN 'super_admin'
                    WHEN EXISTS (SELECT * FROM model_has_roles WHERE model_id = users.id AND role_id IN
                        (SELECT id FROM roles WHERE name = 'admin')) THEN 'admin'
                    WHEN EXISTS (SELECT * FROM model_has_roles WHERE model_id = users.id AND role_id IN
                        (SELECT id FROM roles WHERE name = 'Ayala Super Admin')) THEN 'Ayala Super Admin'
                    ELSE 'Volunteer'
                END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
