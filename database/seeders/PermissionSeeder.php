<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'admin';

        $permissions = [
            // Website Content permissions
            'manage_website_content',
            'view_hero_banners',
            'create_hero_banners',
            'edit_hero_banners',
            'delete_hero_banners',
            'view_leaders',
            'create_leaders',
            'edit_leaders',
            'delete_leaders',
            'view_services',
            'create_services',
            'edit_services',
            'delete_services',
            'manage_services_page',
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'manage_about_page',
            'manage_contact_info',
            'manage_footer_info',

            // Recruitment permissions
            'view_job_circulars',
            'create_job_circulars',
            'edit_job_circulars',
            'delete_job_circulars',
            'view_job_applications',
            'edit_job_applications',
            'delete_job_applications',
            'view_applicants',
            'create_applicants',
            'edit_applicants',
            'delete_applicants',
            'view_notices',
            'create_notices',
            'edit_notices',
            'delete_notices',

            // Administration permissions
            'manage_site_settings',
            'manage_custom_fields',
            'manage_users',
            'manage_roles',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => $guard]);
        }

        // 1. Super Admin Role (All Permissions)
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => $guard]);
        $superAdminRole->syncPermissions(Permission::where('guard_name', $guard)->get());

        // 2. Site Manager Role (Full Website Content Management)
        $siteManagerRole = Role::firstOrCreate(['name' => 'site_manager', 'guard_name' => $guard]);
        $siteManagerRole->syncPermissions([
            'manage_website_content',
            'view_hero_banners',
            'create_hero_banners',
            'edit_hero_banners',
            'delete_hero_banners',
            'view_leaders',
            'create_leaders',
            'edit_leaders',
            'delete_leaders',
            'view_services',
            'create_services',
            'edit_services',
            'delete_services',
            'manage_services_page',
            'view_clients',
            'create_clients',
            'edit_clients',
            'delete_clients',
            'manage_about_page',
            'manage_contact_info',
            'manage_footer_info',
        ]);

        // 3. Service Staff Role (Can publish job circulars, manage notices & applications)
        $serviceStaffRole = Role::firstOrCreate(['name' => 'service_staff', 'guard_name' => $guard]);
        $serviceStaffRole->syncPermissions([
            'view_job_circulars',
            'create_job_circulars',
            'edit_job_circulars',
            'delete_job_circulars',
            'view_job_applications',
            'edit_job_applications',
            'view_applicants',
            'view_notices',
            'create_notices',
            'edit_notices',
        ]);

        // 4. Editor Role (General Content Editor)
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => $guard]);
        $editorRole->syncPermissions([
            'view_hero_banners',
            'edit_hero_banners',
            'view_services',
            'edit_services',
            'manage_services_page',
            'manage_about_page',
            'view_job_circulars',
            'create_job_circulars',
            'edit_job_circulars',
            'view_notices',
            'create_notices',
            'edit_notices',
        ]);

        // Assign super_admin role to all existing users
        foreach (User::all() as $user) {
            if (!$user->hasRole('super_admin', $guard)) {
                $user->assignRole($superAdminRole);
            }
        }
    }
}
