<?php

namespace Database\Seeders;

use App\Models\Booth;
use Illuminate\Database\Seeder;

class BoothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Booth::factory()
            ->count(5)
            ->create();
    }
}
