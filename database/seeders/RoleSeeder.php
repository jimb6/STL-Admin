<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Agent']);
        Role::create(['name' => 'Monitoring']);
        Role::create(['name' => 'Controller']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Super-Admin']);

        $permissions = Permission::all();
        $role = Role::find(5)->givePermissionTo($permissions);
        $user = User::find(1)->assignRole($role);
    }
}
