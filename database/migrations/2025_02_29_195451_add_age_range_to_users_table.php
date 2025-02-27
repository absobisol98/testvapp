<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nickname')->nullable()->after('lastname');
            $table->string('age_range')->nullable()->after('birthday');
        });

        // Populate age_range based on birthday
        DB::table('users')->whereNotNull('birthday')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                if ($user->birthday) {
                    try {
                        $birthdate = Carbon::parse($user->birthday);
                        $age = $birthdate->age;

                        $ageRange = match(true) {
                            $age >= 10 && $age <= 17 => '10-17',
                            $age >= 18 && $age <= 24 => '18-24',
                            $age >= 25 && $age <= 34 => '25-34',
                            $age >= 35 && $age <= 44 => '35-44',
                            $age >= 45 && $age <= 54 => '45-54',
                            $age >= 55 && $age <= 64 => '55-64',
                            $age >= 65 => '65+',
                            default => null,
                        };

                        DB::table('users')
                            ->where('id', $user->id)
                            ->update(['age_range' => $ageRange]);
                    } catch (\Exception $e) {
                        // Log error or handle invalid dates
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('nickname');
            $table->dropColumn('age_range');
        });
    }
};
