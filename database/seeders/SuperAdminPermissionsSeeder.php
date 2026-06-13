<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Ensures the Ayala Super Admin role has every permission in the system.
 * Safe to run multiple times (idempotent).
 *
 * Usage:
 *   php artisan db:seed --class=SuperAdminPermissionsSeeder
 *
 * If Shield permissions haven't been generated yet, run first:
 *   php artisan shield:generate --all
 */
class SuperAdminPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::firstOrCreate(
            ['name' => 'Ayala Super Admin', 'guard_name' => 'web']
        );

        // Grab every permission in the system and sync to the role
        $allPermissions = Permission::all();
        $role->syncPermissions($allPermissions);

        $this->command->info("Granted {$allPermissions->count()} permissions to [{$role->name}].");

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
