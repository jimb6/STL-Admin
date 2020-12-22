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
        Role::create(['name' => 'agent']);
        Role::create(['name' => 'monitoring']);
        Role::create(['name' => 'controller']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'super-admin']);

        $permissions = Permission::all();
        $role = Role::find(5)->givePermissionTo($permissions);
        $user = User::find(1)->assignRole($role);
        $role = Role::find(4)->givePermissionTo($permissions);
        $user = User::find(2)->assignRole($role);


        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'super-admin');
        })->get();

        $arrayOfProhibitedPermission = [
            'create user agents', 'update user agents', 'delete user agents',
            'create user controllers', 'update user controllers', 'delete user controllers',
            'create user monitoring', 'update user monitoring', 'delete user monitoring',
        ];

        $this->removePermission($users, $arrayOfProhibitedPermission);

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        $arrayOfProhibitedPermission = [
            'create user super-admins', 'update user super-admins', 'delete user super-admins',
        ];
        $this->removePermission($users, $arrayOfProhibitedPermission);
    }


    private function removePermission($users, $permissions)
    {
        foreach ($permissions as $permission) {
            $thePermission = Permission::findByName($permission);
            foreach ($users as $user)
                User::find($user->id)->revokePermissionTo($thePermission);
        }
    }
}
