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
    {   Schema::create('program_volunteer', function (Blueprint $table) {
        $table->id();
        $table->integer('program_id');
        $table->foreign('program_id')
            ->references('id')
            ->on('programs')
            ->onDelete('cascade');
        $table->foreignUuid('volunteer_id')->constrained('users')->onDelete('cascade');
        $table->boolean('is_primary')->default(false);
        $table->timestamps();
    });

        // Migrate existing data from users table
        DB::statement('
            INSERT INTO program_volunteer (program_id, volunteer_id, is_primary, created_at, updated_at)
            SELECT program_id, id, true, NOW(), NOW()
            FROM users
            WHERE program_id IS NOT NULL
            AND EXISTS (
                SELECT 1 FROM model_has_roles
                WHERE model_id = users.id
                AND role_id = (SELECT id FROM roles WHERE name = "Volunteer")
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('program_volunteer');
    }
};
