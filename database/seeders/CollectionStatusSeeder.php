<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CollectionStatus;

class CollectionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CollectionStatus::factory()
            ->count(5)
            ->create();
    }
}
