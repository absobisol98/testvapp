<?php

namespace Database\Seeders;

use App\Filament\Resources\Shield\RoleResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions (idempotent)
        $logPermission = Permission::firstOrCreate(['name' => 'access_log_viewer', 'guard_name' => 'web']);

        $roles = ["super_admin", "admin", "author", "Volunteer", "Facilitator", "External Partner", "Ayala Super Admin"];

        foreach ($roles as $role) {
            $roleCreated = (new (RoleResource::getModel()))->firstOrCreate(
                ['name' => $role, 'guard_name' => 'web'],
                ['created_at' => now(), 'updated_at' => now()]
            );

            if ($role === 'super_admin' && ! $roleCreated->hasPermissionTo('access_log_viewer')) {
                $roleCreated->givePermissionTo($logPermission);
            }
        }
    }
}
