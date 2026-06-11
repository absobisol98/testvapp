<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('event_companies', function (Blueprint $table) {
            $table->foreign(['event_id'], 'event_companies_ibfk_1')->references(['id'])->on('events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['company_id'], 'event_companies_ibfk_2')->references(['id'])->on('companies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }
        Schema::table('event_companies', function (Blueprint $table) {
            $table->dropForeign('event_companies_ibfk_1');
            $table->dropForeign('event_companies_ibfk_2');
        });
    }
};
