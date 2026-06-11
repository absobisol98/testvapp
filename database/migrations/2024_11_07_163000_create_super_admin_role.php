<?php

use Illuminate\Database\Migrations\Migration;
use BezhanSalleh\FilamentShield\Support\Utils;

// This migration ensures the super_admin role exists before the subsequent
// permission-assignment migrations run. On a fresh database (e.g. in tests)
// those migrations call ->givePermissionTo() on the role and crash when it
// is absent. Using firstOrCreate makes this safe to run on an already-seeded
// production database too.
return new class extends Migration
{
    public function up(): void
    {
        Utils::getRoleModel()::firstOrCreate([
            'name'       => 'super_admin',
            'guard_name' => Utils::getFilamentAuthGuard(),
        ]);
    }

    public function down(): void
    {
        //
    }
};
