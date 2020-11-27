<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Booth;
use App\Models\User;
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
        User::factory(10)->create();
        Agent::factory(1000)->create();
        Booth::factory(1000)->create();
    }
}
