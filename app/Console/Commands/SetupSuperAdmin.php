<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SetupSuperAdmin extends Command
{
    protected $signature   = 'shield:setup-super-admin';
    protected $description = 'Generate all Shield permissions and grant them to Ayala Super Admin';

    public function handle(): int
    {
        $this->info('Generating Shield permissions for all resources...');
        $this->call('shield:generate', ['--all' => true]);

        $this->info('Assigning all permissions to Ayala Super Admin...');

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $role = Role::firstOrCreate(
            ['name' => 'Ayala Super Admin', 'guard_name' => 'web']
        );

        $allPermissions = Permission::all();
        $role->syncPermissions($allPermissions);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->info("Done. Granted {$allPermissions->count()} permissions to [{$role->name}].");

        return self::SUCCESS;
    }
}
