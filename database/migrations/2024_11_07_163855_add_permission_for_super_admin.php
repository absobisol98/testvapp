<?php

use Illuminate\Database\Migrations\Migration;
use BezhanSalleh\FilamentShield\Support\Utils;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $role = Utils::getRoleModel()::where('name', 'super_admin')->first();
        $permissionPrefixes = Utils::getGeneralResourcePermissionPrefixes();

        $permissions = collect();
        $resource = 'program';

        collect($permissionPrefixes)
            ->each(function ($prefix) use ($resource, $permissions, $role) {
                $permissions->push(Utils::getPermissionModel()::firstOrCreate([
                    'name' => $prefix . '_' . $resource,
                    'guard_name' => Utils::getFilamentAuthGuard()
                ]));
            });

        $role->givePermissionTo($permissions);

        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
