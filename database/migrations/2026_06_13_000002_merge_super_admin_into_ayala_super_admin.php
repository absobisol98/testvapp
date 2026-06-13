<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $superAdmin = DB::table('roles')->where('name', 'super_admin')->first();

        if (! $superAdmin) {
            return; // Already cleaned up or never existed
        }

        $ayala = DB::table('roles')->where('name', 'Ayala Super Admin')->first();

        if (! $ayala) {
            // Simply rename
            DB::table('roles')->where('id', $superAdmin->id)->update(['name' => 'Ayala Super Admin']);
            return;
        }

        // Both exist — merge into Ayala Super Admin then delete super_admin

        // Reassign users
        DB::table('model_has_roles')
            ->where('role_id', $superAdmin->id)
            ->update(['role_id' => $ayala->id]);

        // Move any permissions not already on Ayala Super Admin
        $existingPermissions = DB::table('role_has_permissions')
            ->where('role_id', $ayala->id)
            ->pluck('permission_id')
            ->toArray();

        $toMove = DB::table('role_has_permissions')
            ->where('role_id', $superAdmin->id)
            ->whereNotIn('permission_id', $existingPermissions)
            ->get();

        foreach ($toMove as $row) {
            DB::table('role_has_permissions')->insert([
                'permission_id' => $row->permission_id,
                'role_id'       => $ayala->id,
            ]);
        }

        DB::table('role_has_permissions')->where('role_id', $superAdmin->id)->delete();
        DB::table('roles')->where('id', $superAdmin->id)->delete();
    }

    public function down(): void
    {
        // Recreate super_admin as an alias — cannot fully reverse a merge
        DB::table('roles')->insertOrIgnore([
            'name'       => 'super_admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
