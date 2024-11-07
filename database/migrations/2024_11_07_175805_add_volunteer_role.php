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
         DB::table('roles')
            ->insertGetId([
                'name' => 'Volunteer',
                'guard_name' => 'web',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);

        $volunteer = Utils::getRoleModel()::where('name', 'Volunteer')->first();

        $custom_permissions = Utils::getPermissionModel()::wherein('name', [
            'view_any_volunteer',
            'view_volunteer',
            'update_volunteer',
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
