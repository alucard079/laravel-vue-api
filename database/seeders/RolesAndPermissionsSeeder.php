<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'permission.view',
            'permission.create',
            'permission.edit',
            'permission.delete',
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
        ];
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }
        // create roles and assign created permissions
        $role = Role::create([
                'name' => 'Super Admin',
                'guard_name' => 'api',
        ]);
        $role->givePermissionTo(Permission::all());

        // or may be done by chaining
        $role = Role::create([
            'name' => 'User',
            'guard_name' => 'api',
        ])->givePermissionTo(['permission.view', 'role.view', 'user.view']);
    }
}
