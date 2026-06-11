<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            $table->smallInteger('encoding_type')
                ->comment("1: Single with Account \n 2: Single Without Account \n 3: Bulk")
                ->default(1);
            $table->string('no_account_name')->nullable();
            $table->integer('volunteer_count')->comment('For Bulk Encoding')->nullable();

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('event_attendees_ibfk_1');
            }
        });

        Schema::table('event_attendees', function (Blueprint $table) {
            $table->string('attendee_id')->nullable()->change();

            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign(['attendee_id'], 'event_attendees_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('event_attendees', function (Blueprint $table) {
            $table->dropColumn('encoding_type');
            $table->dropColumn('no_account_name');
            $table->dropColumn('volunteer_count');

            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('event_attendees_ibfk_1');
            }
        });

        Schema::table('event_attendees', function (Blueprint $table) {
            $table->string('attendee_id')->change();

            if (DB::getDriverName() !== 'sqlite') {
                $table->foreign(['attendee_id'], 'event_attendees_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            }
        });
    }
};
