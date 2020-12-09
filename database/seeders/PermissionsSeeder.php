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
        Permission::create(['name' => 'menu mobilesettings']);
        Permission::create(['name' => 'menu settings']);
        Permission::create(['name' => 'menu bases']);

        // Create default permissions
        Permission::create(['name' => 'list collectionrecords']);
        Permission::create(['name' => 'view collectionrecords']);
        Permission::create(['name' => 'create collectionrecords']);
        Permission::create(['name' => 'update collectionrecords']);
        Permission::create(['name' => 'delete collectionrecords']);

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

        Permission::create(['name' => 'list drawperiods']);
        Permission::create(['name' => 'view drawperiods']);
        Permission::create(['name' => 'create drawperiods']);
        Permission::create(['name' => 'update drawperiods']);
        Permission::create(['name' => 'delete drawperiods']);

        Permission::create(['name' => 'list closenumbers']);
        Permission::create(['name' => 'view closenumbers']);
        Permission::create(['name' => 'create closenumbers']);
        Permission::create(['name' => 'update closenumbers']);
        Permission::create(['name' => 'delete closenumbers']);

        Permission::create(['name' => 'list collectionstatuses']);
        Permission::create(['name' => 'view collectionstatuses']);
        Permission::create(['name' => 'create collectionstatuses']);
        Permission::create(['name' => 'update collectionstatuses']);
        Permission::create(['name' => 'delete collectionstatuses']);

        Permission::create(['name' => 'list bases']);
        Permission::create(['name' => 'view bases']);
        Permission::create(['name' => 'create bases']);
        Permission::create(['name' => 'update bases']);
        Permission::create(['name' => 'delete bases']);

        Permission::create(['name' => 'list bettransactions']);
        Permission::create(['name' => 'view bettransactions']);
        Permission::create(['name' => 'create bettransactions']);
        Permission::create(['name' => 'update bettransactions']);
        Permission::create(['name' => 'delete bettransactions']);

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

        Permission::create(['name' => 'list drawresults']);
        Permission::create(['name' => 'view drawresults']);
        Permission::create(['name' => 'create drawresults']);
        Permission::create(['name' => 'update drawresults']);
        Permission::create(['name' => 'delete drawresults']);

        Permission::create(['name' => 'list betgames']);
        Permission::create(['name' => 'view betgames']);
        Permission::create(['name' => 'create betgames']);
        Permission::create(['name' => 'update betgames']);
        Permission::create(['name' => 'delete betgames']);

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

    }
}
