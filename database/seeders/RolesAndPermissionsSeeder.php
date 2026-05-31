<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'dashboard.view',
            'departments.view', 'departments.create', 'departments.update', 'departments.delete', 'department_users.manage',
            'job_positions.view', 'job_positions.create', 'job_positions.update', 'job_positions.delete', 'job_positions.publish', 'job_positions.close', 'job_positions.manage_questions',
            'application_sources.view', 'application_sources.create', 'application_sources.update', 'application_sources.delete',
            'candidates.view', 'candidates.create', 'candidates.update', 'candidates.delete',
            'applications.view_all', 'applications.view_department', 'applications.view_assigned', 'applications.create', 'applications.update', 'applications.delete', 'applications.change_status', 'applications.assign_user', 'applications.reject', 'applications.hire', 'applications.export',
            'interviews.view_all', 'interviews.view_department', 'interviews.view_own', 'interviews.create', 'interviews.update', 'interviews.cancel', 'interviews.complete', 'interviews.calendar',
            'notes.view', 'notes.create', 'notes.update_own', 'notes.delete_own', 'notes.delete_any',
            'sms_templates.view', 'sms_templates.create', 'sms_templates.update', 'sms_templates.delete', 'sms_logs.view', 'sms.send',
            'imports.view', 'imports.create', 'imports.retry',
            'reports.view',
            'settings.view', 'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::query()->firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'Super Admin' => $permissions,
            'HR Manager' => $permissions,
            'HR Staff / Recruiter' => [
                'dashboard.view',
                'job_positions.view',
                'applications.view_all', 'applications.create', 'applications.update', 'applications.change_status',
                'interviews.view_all', 'interviews.create', 'interviews.update', 'interviews.calendar',
                'notes.view', 'notes.create',
                'candidates.view', 'candidates.update',
                'sms.send', 'sms_logs.view',
                'imports.view', 'imports.create',
                'reports.view',
            ],
            'Department Manager' => [
                'dashboard.view',
                'job_positions.view',
                'applications.view_department',
                'interviews.view_department',
                'notes.view', 'notes.create',
                'reports.view',
            ],
            'Interviewer' => [
                'dashboard.view',
                'interviews.view_own', 'interviews.update', 'interviews.calendar',
                'notes.create',
            ],
            'Viewer' => [
                'dashboard.view',
                'reports.view',
            ],
        ];

        foreach ($roles as $name => $rolePermissions) {
            $role = Role::query()->firstOrCreate(['name' => $name, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@yadahrm.local'],
            [
                'name' => 'Super Admin',
                'mobile' => '09120000000',
                'password' => 'password',
                'status' => 'active',
                'email_verified_at' => now(),
            ],
        );

        $admin->assignRole('Super Admin');
    }
}