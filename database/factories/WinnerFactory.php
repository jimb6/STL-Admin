<?php

namespace Database\Factories;

use App\Models\Winner;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class WinnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Winner::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'bet_id' => \App\Models\Bet::factory(),
//            'draw_result_id' => \App\Models\DrawResult::factory(),
        ];
    }
}
