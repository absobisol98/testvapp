<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new column for external company name
            $table->string('external_company_name')->nullable();

            // Update existing affiliate_type_id values
            DB::statement("UPDATE users SET affiliate_type_id = 2 WHERE affiliate_type_id = 3");
            DB::statement("UPDATE users SET affiliate_type_id = 2 WHERE affiliate_type_id > 3");
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('external_company_name');
        });
    }
};
