<?php

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
                'name' => 'External Partner',
                'guard_name' => 'web',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);

        $volunteer = Utils::getRoleModel()::where('name', 'External Partner')->first();

        $custom_permissions = Utils::getPermissionModel()::wherein('name', [
            'widget_VolunteersReportByGroupWidget',
            'widget_VolunteersByOpportunity',
            'widget_VolunteersByCategory',
            'widget_ReportByProgram',
            'widget_ReportByDepartmentBar',
            'widget_OpportunityPerVolunteer',
            'widget_VolunteersWidget',
            'widget_VolunteersTableWidget',
            'widget_VolunteerUsageWidgetByProgram',
            'widget_VolunteerUsageWidgetByDepartment',
            'widget_VolunteerSignupPerMonth',

            'view_event',
            'view_any_event',
            'create_event',
            'update_event',
            'restore_event',
            'replicate_event',
            'reorder_event',
            'delete_event',
            'delete_any_event',
            'force_delete_event',

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
