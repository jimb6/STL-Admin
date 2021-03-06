<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

//      Web App Menu Permissions
        Permission::create(['name' => 'menu dashboard']);
        Permission::create(['name' => 'menu agents']);
        Permission::create(['name' => 'menu booths']);
        Permission::create(['name' => 'menu bets']);
        Permission::create(['name' => 'menu collections']);
        Permission::create(['name' => 'menu reports']);
        Permission::create(['name' => 'menu mobile settings']);
        Permission::create(['name' => 'menu settings']);
        Permission::create(['name' => 'menu clusters']);
        Permission::create(['name' => 'menu games']);
        Permission::create(['name' => 'menu draw periods']);

        // Create default permissions
        Permission::create(['name' => 'list collection records']);
        Permission::create(['name' => 'view collection records']);
        Permission::create(['name' => 'create collection records']);
        Permission::create(['name' => 'update collection records']);
        Permission::create(['name' => 'delete collection records']);

        Permission::create(['name' => 'list agents']);
        Permission::create(['name' => 'view agents']);
        Permission::create(['name' => 'create agents']);
        Permission::create(['name' => 'update agents']);
        Permission::create(['name' => 'delete agents']);

        Permission::create(['name' => 'list booths']);
        Permission::create(['name' => 'view booths']);
        Permission::create(['name' => 'create booths']);
        Permission::create(['name' => 'update booths']);
        Permission::create(['name' => 'delete booths']);

        Permission::create(['name' => 'list draw periods']);
        Permission::create(['name' => 'view draw periods']);
        Permission::create(['name' => 'create draw periods']);
        Permission::create(['name' => 'update draw periods']);
        Permission::create(['name' => 'delete draw periods']);

        Permission::create(['name' => 'list close numbers']);
        Permission::create(['name' => 'view close numbers']);
        Permission::create(['name' => 'create close numbers']);
        Permission::create(['name' => 'update close numbers']);
        Permission::create(['name' => 'delete close numbers']);

        Permission::create(['name' => 'list clusters']);
        Permission::create(['name' => 'view clusters']);
        Permission::create(['name' => 'create clusters']);
        Permission::create(['name' => 'update clusters']);
        Permission::create(['name' => 'delete clusters']);

        Permission::create(['name' => 'list bet transactions']);
        Permission::create(['name' => 'view bet transactions']);
        Permission::create(['name' => 'create bet transactions']);
        Permission::create(['name' => 'update bet transactions']);
        Permission::create(['name' => 'delete bet transactions']);

        Permission::create(['name' => 'list bets']);
        Permission::create(['name' => 'view bets']);
        Permission::create(['name' => 'create bets']);
        Permission::create(['name' => 'update bets']);
        Permission::create(['name' => 'delete bets']);

        Permission::create(['name' => 'list winners']);
        Permission::create(['name' => 'view winners']);
        Permission::create(['name' => 'create winners']);
        Permission::create(['name' => 'update winners']);
        Permission::create(['name' => 'delete winners']);

        Permission::create(['name' => 'list draw results']);
        Permission::create(['name' => 'view draw results']);
        Permission::create(['name' => 'create draw results']);
        Permission::create(['name' => 'update draw results']);
        Permission::create(['name' => 'delete draw results']);

        Permission::create(['name' => 'list games']);
        Permission::create(['name' => 'view games']);
        Permission::create(['name' => 'create games']);
        Permission::create(['name' => 'update games']);
        Permission::create(['name' => 'delete games']);

        // Create user role and assign existing permissions

        // Create admin exclusive permissions
        Permission::create(['name' => 'list roles']);
        Permission::create(['name' => 'view roles']);
        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);

        Permission::create(['name' => 'list permissions']);
        Permission::create(['name' => 'view permissions']);
        Permission::create(['name' => 'create permissions']);
        Permission::create(['name' => 'update permissions']);
        Permission::create(['name' => 'delete permissions']);

        Permission::create(['name' => 'list users']);
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'list devices']);
        Permission::create(['name' => 'view devices']);
        Permission::create(['name' => 'create devices']);
        Permission::create(['name' => 'update devices']);
        Permission::create(['name' => 'delete devices']);

        Permission::create(['name' => 'list addresses']);
        Permission::create(['name' => 'view addresses']);
        Permission::create(['name' => 'create addresses']);
        Permission::create(['name' => 'update addresses']);
        Permission::create(['name' => 'delete addresses']);


    }
}
