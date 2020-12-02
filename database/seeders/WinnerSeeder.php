<?php

namespace Database\Seeders;

use App\Models\Winner;
use Illuminate\Database\Seeder;

class WinnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Winner::factory()
            ->count(5)
            ->create();
    }
}
