<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

//      Web App Menu Permissions
        Permission::create(['guard_name' => 'web', 'name' => 'menu-dashboard']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-agents']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-booths']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-bets']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-collections']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-reports']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-mobile settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-clusters']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-games']);
        Permission::create(['guard_name' => 'web', 'name' => 'menu-draw-periods']);

        Permission::create(['guard_name' => 'web', 'name' => 'list-agents']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-agents']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-agents']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-agents']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-agents']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-app-settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-app-settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-app-settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-app-settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-app-settings']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-bets']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-bets']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-bets']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-bets']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-bets']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-bet-transactions']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-bet-transactions']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-bet-transactions']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-bet-transactions']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-bet-transactions']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-booths']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-booths']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-booths']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-booths']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-booths']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-close-numbers']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-close-numbers']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-close-numbers']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-close-numbers']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-close-numbers']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-clusters']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-clusters']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-clusters']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-clusters']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-clusters']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-collections']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-collections']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-collections']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-collections']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-collections']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-commissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-commissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-commissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-commissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-commissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-control-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-control-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-control-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-control-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-control-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-devices']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-devices']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-devices']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-devices']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-devices']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-draw-periods']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-draw-periods']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-draw-periods']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-draw-periods']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-draw-periods']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-draw-results']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-draw-results']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-draw-results']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-draw-results']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-draw-results']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-games']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-games']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-games']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-games']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-games']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-game-configurations']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-game-configurations']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-game-configurations']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-game-configurations']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-game-configurations']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-users']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-users']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-users']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-users']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-users']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-winning-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-winning-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-winning-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-winning-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-winning-combinations']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-roles']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-roles']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-roles']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-roles']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-roles']);
        Permission::create(['guard_name' => 'web', 'name' => 'list-permissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'view-permissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'create-permissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'update-permissions']);
        Permission::create(['guard_name' => 'web', 'name' => 'delete-permissions']);


//        END


//        // Create default permissions
//        Permission::create(['name' => 'list collection records']);
//        Permission::create(['name' => 'view collection records']);
//        Permission::create(['name' => 'create collection records']);
//        Permission::create(['name' => 'update collection records']);
//        Permission::create(['name' => 'delete collection records']);
//
//        Permission::create(['name' => 'list agents']);
//        Permission::create(['name' => 'view agents']);
//        Permission::create(['name' => 'create agents']);
//        Permission::create(['name' => 'update agents']);
//        Permission::create(['name' => 'delete agents']);
//
//        Permission::create(['name' => 'list booths']);
//        Permission::create(['name' => 'view booths']);
//        Permission::create(['name' => 'create booths']);
//        Permission::create(['name' => 'update booths']);
//        Permission::create(['name' => 'delete booths']);
//
//        Permission::create(['name' => 'list draw periods']);
//        Permission::create(['name' => 'view draw periods']);
//        Permission::create(['name' => 'create draw periods']);
//        Permission::create(['name' => 'update draw periods']);
//        Permission::create(['name' => 'delete draw periods']);
//
//        Permission::create(['name' => 'list close numbers']);
//        Permission::create(['name' => 'view close numbers']);
//        Permission::create(['name' => 'create close numbers']);
//        Permission::create(['name' => 'update close numbers']);
//        Permission::create(['name' => 'delete close numbers']);
//
//        Permission::create(['name' => 'list clusters']);
//        Permission::create(['name' => 'view clusters']);
//        Permission::create(['name' => 'create clusters']);
//        Permission::create(['name' => 'update clusters']);
//        Permission::create(['name' => 'delete clusters']);
//
//        Permission::create(['name' => 'list bet transactions']);
//        Permission::create(['name' => 'view bet transactions']);
//        Permission::create(['name' => 'create bet transactions']);
//        Permission::create(['name' => 'update bet transactions']);
//        Permission::create(['name' => 'delete bet transactions']);
//
//        Permission::create(['name' => 'list bets']);
//        Permission::create(['name' => 'view bets']);
//        Permission::create(['name' => 'create bets']);
//        Permission::create(['name' => 'update bets']);
//        Permission::create(['name' => 'delete bets']);
//
//        Permission::create(['name' => 'list winners']);
//        Permission::create(['name' => 'view winners']);
//        Permission::create(['name' => 'create winners']);
//        Permission::create(['name' => 'update winners']);
//        Permission::create(['name' => 'delete winners']);
//
//        Permission::create(['name' => 'list draw results']);
//        Permission::create(['name' => 'view draw results']);
//        Permission::create(['name' => 'create draw results']);
//        Permission::create(['name' => 'update draw results']);
//        Permission::create(['name' => 'delete draw results']);
//
//        Permission::create(['name' => 'list games']);
//        Permission::create(['name' => 'view games']);
//        Permission::create(['name' => 'create games']);
//        Permission::create(['name' => 'update games']);
//        Permission::create(['name' => 'delete games']);
//
//        // Create user role and assign existing permissions
//
//        // Create admin exclusive permissions
//        Permission::create(['name' => 'list roles']);
//        Permission::create(['name' => 'view roles']);
//        Permission::create(['name' => 'create roles']);
//        Permission::create(['name' => 'update roles']);
//        Permission::create(['name' => 'delete roles']);
//
//        Permission::create(['name' => 'list permissions']);
//        Permission::create(['name' => 'view permissions']);
//        Permission::create(['name' => 'create permissions']);
//        Permission::create(['name' => 'update permissions']);
//        Permission::create(['name' => 'delete permissions']);
//
//        Permission::create(['name' => 'list users']);
//        Permission::create(['name' => 'view users']);
//        Permission::create(['name' => 'create users']);
//        Permission::create(['name' => 'update users']);
//        Permission::create(['name' => 'delete users']);
//
//        Permission::create(['name' => 'list devices']);
//        Permission::create(['name' => 'view devices']);
//        Permission::create(['name' => 'create devices']);
//        Permission::create(['name' => 'update devices']);
//        Permission::create(['name' => 'delete devices']);
//
//        Permission::create(['name' => 'list addresses']);
//        Permission::create(['name' => 'view addresses']);
//        Permission::create(['name' => 'create addresses']);
//        Permission::create(['name' => 'update addresses']);
//        Permission::create(['name' => 'delete addresses']);
//
//
//        Permission::create(['name' => 'create user super-admins']);
//        Permission::create(['name' => 'update user super-admins']);
//        Permission::create(['name' => 'delete user super-admins']);
//
//
//        Permission::create(['name' => 'create user admins']);
//        Permission::create(['name' => 'update user admins']);
//        Permission::create(['name' => 'delete user admins']);
//
//        Permission::create(['name' => 'update user controllers']);
//        Permission::create(['name' => 'delete user controllers']);
//        Permission::create(['name' => 'create user controllers']);
//
//        Permission::create(['name' => 'create user monitoring']);
//        Permission::create(['name' => 'update user monitoring']);
//        Permission::create(['name' => 'delete user monitoring']);


    }
}
