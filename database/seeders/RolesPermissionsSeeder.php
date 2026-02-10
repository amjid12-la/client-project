<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilities = [
            'read',
            'write',
            'create',
            'delete',
        ];

        $permissions_by_role = [
            'administrator' => [
                'user management',

            ]
        ];

        // Step 1: Create all permissions dynamically for all roles
        foreach ($permissions_by_role as $permissions) {
            foreach ($permissions as $permission) {
                foreach ($abilities as $ability) {
                    $permissionName = $ability . ' ' . $permission;
                    if (!Permission::where('name', $permissionName)->exists()) {
                        Permission::create(['name' => $permissionName]);
                    }
                }
            }
        }

        // Step 2: Create roles and sync permissions
        foreach ($permissions_by_role as $role => $permissions) {
            $full_permissions_list = [];
            foreach ($abilities as $ability) {
                foreach ($permissions as $permission) {
                    $full_permissions_list[] = $ability . ' ' . $permission;
                }
            }

            // Create role and sync permissions
            $roleInstance = Role::firstOrCreate(['name' => $role]);
            // Attach permissions if any
            if (!empty($full_permissions_list)) {
                $roleInstance->syncPermissions($full_permissions_list);
            }
        }

        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web',
        ]);

        // Step 3: Assign roles to users
        
        // Assign administrator role to admin user
        $adminUser = User::where('email', 'admin@admin.com')->first();
        if ($adminUser) {
            $adminUser->assignRole('administrator');
        }
    }
}
