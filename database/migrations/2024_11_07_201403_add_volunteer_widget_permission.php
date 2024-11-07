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
        $widgets = [
            'widget_Welcome',
        ];

        foreach ($widgets as $widget) {
            DB::table('permissions')
                ->insert([
                    'name' => $widget,
                    'guard_name' => 'web',
                    'created_at' => now(),
                ]);

            $permissions[] = Utils::getPermissionModel()::where('name', $widget)->first();

        }

        $role = Utils::getRoleModel()::where('name','Volunteer')->first();
        $role2 = Utils::getRoleModel()::where('name','super_admin')->first();

        $role->givePermissionTo($permissions);
        $role2->givePermissionTo($permissions);

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
