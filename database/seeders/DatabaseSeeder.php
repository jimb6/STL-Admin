<?php

namespace Database\Seeders;

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
        $this->call(BoothSeeder::class);
        $user = \App\Models\User::factory(5);
        $this->call(PermissionsSeeder::class);
//        $this->call(CollectionRecordSeeder::class);
        $this->call(AgentSeeder::class);
        $this->call(DrawPeriodSeeder::class);
//        $this->call(CloseNumberSeeder::class);
        $this->call(CollectionStatusSeeder::class);
        $this->call(UserSeeder::class);

//        $this->call(BetTransactionSeeder::class);
//        $this->call(BetSeeder::class);
//        $this->call(WinnerSeeder::class);
//        $this->call(DrawResultSeeder::class);
        $this->call(BetGameSeeder::class);
        $this->call(BetGameDrawPeriodFactory::class);

    }
}
