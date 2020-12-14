<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('addresses')->delete();
        $address = [
            [
                'province' => 'Davao Oriental',
                'municipality' => 'Mati',
                'barangay' => 'Menzi',
                'street' => 'Purok 1 Mongoose'
            ],
            [
                'province' => 'Davao Oriental',
                'municipality' => 'Lupon',
                'barangay' => 'Ilangay',
                'street' => 'Fighter'
            ],
            [
                'province' => 'Davao Oriental',
                'municipality' => 'Mati',
                'barangay' => 'Sainz',
                'street' => 'Tindalo St.'
            ],
            [
                'province' => 'Davao Oriental',
                'municipality' => 'Baganga',
                'barangay' => 'Baculin',
                'street' => 'Bay-bay'
            ],
            [
                'province' => 'Davao Oriental',
                'municipality' => 'Lupon',
                'barangay' => 'Poblacion',
                'street' => 'Rizal St.'
            ]
        ];

        DB::table('addresses')->insert($address);
    }
}
