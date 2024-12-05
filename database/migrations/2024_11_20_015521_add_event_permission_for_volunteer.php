<?php

use Illuminate\Database\Migrations\Migration;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $volunteer = Utils::getRoleModel()::where('name', 'Volunteer')->first();

        $custom_permissions = Utils::getPermissionModel()::wherein('name', [
            'view_any_event',
            'view_event',
        ])->get();

        $volunteer->givePermissionTo($custom_permissions);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
