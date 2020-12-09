<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Adding an admin user
        $this->call(BaseSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(BoothSeeder::class);
        $this->call(PermissionsSeeder::class);
//        $this->call(DrawPeriodSeeder::class);
//        $this->call(CollectionStatusSeeder::class);
        $this->call(UserSeeder::class);
//        $this->call(BetGameSeeder::class);
//        $this->call(BetGameDrawPeriodFactory::class);
        $this->call(RoleSeeder::class);

    }
}
