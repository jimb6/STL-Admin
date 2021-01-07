<?php

namespace Database\Seeders;

use App\Models\Agent;
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


        $agentRole = Role::find(1)->givePermissionTo(
            'list-bets',
            'view-bets',
            'create-bets',
            'list-bet-transactions',
            'view-bet-transactions',
            'create-bet-transactions',
            'list-collections',
            'view-collections',
        );
        $agents = Agent::all();
        foreach ($agents as $agent){
            User::find($agent->id)->assignRole($agentRole);
        }
    }
}
