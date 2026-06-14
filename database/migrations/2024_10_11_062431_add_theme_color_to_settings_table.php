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
        DB::table('settings')->where('name','site_theme')
            ->update(['payload' => '{
                "gray": "#485173",
                "info": "#6E6DD7",
                "danger": "#ff5467",
                "primary": "#ff7b00",
                "success": "#1DCB8A",
                "warning": "#f5de8d",
                "secondary": "#0433ff"
            }']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            //
        });
    }
};
