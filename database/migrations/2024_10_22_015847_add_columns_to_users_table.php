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
            $table->boolean('volunteer')->default(false)->after('id');
            $table->string('middle_name',255)->nullable()->after('lastname');
            $table->date('birthday')->nullable()->after('middle_name');
            $table->string('company_name',255)->nullable()->after('birthday');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_contact_number',255)->nullable()->after('company_address');
            $table->string('company_representative',255)->nullable()->after('company_contact_number');
            $table->string('company_email',255)->nullable()->after('company_representative');
            $table->text('school')->nullable()->after('company_email');
            $table->text('emergency_contact_name')->nullable()->after('school');
            $table->string('emergency_contact_number',255)->nullable()->after('emergency_contact_name');
            $table->integer('affiliate_type_id')->nullable()->after('emergency_contact_number');

            $table->foreign(['affiliate_type_id'], 'affiliate_type_id')->references(['id'])->on('affiliate_types')->onUpdate('CASCADE');

        });
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
