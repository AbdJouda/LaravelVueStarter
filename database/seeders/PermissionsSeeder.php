<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'admin' => [
            ],
            'user_management' => [
                'view_users' => 'View Users',
                'create_users' => 'Create Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
                'assign_roles' => 'Assign Roles to Users',
            ],

            'role_management' => [
                'view_roles' => 'View Roles',
                'create_roles' => 'Create Roles',
                'edit_roles' => 'Edit Roles',
                'delete_roles' => 'Delete Roles',
                'manage_role_permissions' => 'Manage Role Permissions',
            ],

            'permissions_management' => [
                'view_permissions' => 'View Permissions',
                'create_permissions' => 'Create Permissions',
                'edit_permissions' => 'Edit Permissions',
                'delete_permissions' => 'Delete Permissions',
            ],

            'content_management' => [
                'view_content' => 'View Content',
                'create_content' => 'Create Content',
                'edit_content' => 'Edit Content',
                'delete_content' => 'Delete Content',
                'publish_content' => 'Publish Content',
            ],

            'system_settings' => [
                'view_system_settings' => 'View System Settings',
                'update_system_settings' => 'Update System Settings',
            ],

            // Reports and Analytics
            'reports_and_analytics' => [
                'view_reports' => 'View Reports',
                'generate_reports' => 'Generate Reports',
                'download_reports' => 'Download Reports',
            ],

            'file_management' => [
                'upload_files' => 'Upload Files',
                'view_files' => 'View Files',
                'edit_files' => 'Edit Files',
                'delete_files' => 'Delete Files',
            ],

            'project_management' => [
                'view_projects' => 'View Projects',
                'create_projects' => 'Create Projects',
                'edit_projects' => 'Edit Projects',
                'delete_projects' => 'Delete Projects',
                'assign_users_to_projects' => 'Assign Users to Projects',
            ],

        ];

        foreach ($permissions as $role => $perms) {

            $role = Role::create(['name' => $role]);

            foreach ($perms as $key => $label) {
                Permission::create([
                    'name' => $key,
                ]);
            }

            $role->syncPermissions(array_keys($perms));

        }

    }
}
