<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Permissions
        Permission::create(['name' => 'manage mikrotik']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'manage users']);

        // Roles
        $admin = Role::create(['name' => 'admin']);
        $operator = Role::create(['name' => 'operator']);

        // Assign Permissions to Roles
        $admin->givePermissionTo(['manage mikrotik', 'view users', 'manage users']);
        $operator->givePermissionTo(['view users']);
    }
}
