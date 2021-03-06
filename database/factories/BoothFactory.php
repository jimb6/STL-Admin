<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Base;
use App\Models\Booth;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoothFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booth::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address_id' => Address::all()->random()->id,
            'base_id' => Base::all()->random()->id,
            'user_id' => null,
        ];
    }
}
