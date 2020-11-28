<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit agents']);
        Permission::create(['name' => 'delete agents']);
        Permission::create(['name' => 'create agents']);
        Permission::create(['name' => 'update agents']);

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('edit agents');
        $role2->givePermissionTo('create agents');
        $role2->givePermissionTo('update agents');
        $role2->givePermissionTo('delete agents');

        $role3 = Role::create(['name' => 'super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = User::find(1);
        $user->assignRole($role2);

        $user = \App\Models\User::find(2);
        $user->assignRole($role3);

    }
}
