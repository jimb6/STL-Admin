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
        Permission::create(['name' => 'menu dashboard']);
        Permission::create(['name' => 'menu agents']);
        Permission::create(['name' => 'menu booths']);
        Permission::create(['name' => 'menu gamedraws']);
        Permission::create(['name' => 'menu bets']);
        Permission::create(['name' => 'menu collections']);
        Permission::create(['name' => 'menu reports']);
        Permission::create(['name' => 'menu mobilesettings']);
        Permission::create(['name' => 'menu settings']);


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
        $currentPermissions = Permission::all();
        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo($currentPermissions);

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

        // Create admin role and assign all permissions
        $allPermissions = Permission::all();
        $adminRole = Role::create(['name' => 'super-admin']);
        $adminRole->givePermissionTo($allPermissions);

        $user = \App\Models\User::whereEmail('admin@admin.com')->first();

        if ($user) {
            $user->assignRole($adminRole);
        }

        $monitor = \App\Models\User::create([
            'name' => 'Jim',
            'email' => 'jimwellbuot@gmail.com',
            'password' => Hash::make('password'),
            'base_id' => 1
        ]);

        $monitoringMenu = Permission::all()->whereIn(
            'name', ['menu dashboard', 'menu agents', 'menu booths', 'menu gamedraws', 'menu bets', 'menu collections', 'menu reports']);
        $monitoringAccess = Permission::all()->whereIn(
            'name',
            [
                'list agents', 'list booths', 'list bets', 'view bets', 'list closenumbers',
                'create closenumbers', 'store closenumbers', 'list viewresults', 'list collectionrecords'
            ]);

        $monitorRole = Role::create(['name' => 'monitoring']);
        $monitorRole->givePermissionTo($monitoringMenu);
        $monitorRole->givePermissionTo($monitoringAccess);
        $monitor->assignRole($monitorRole);
    }
}
