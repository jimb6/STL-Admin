<?php

namespace Database\Factories;

use App\Models\CloseNumber;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class CloseNumberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CloseNumber::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'number_value' => $this->faker->text(255),
//            'bet_game_id' => \App\Models\BetGame::factory(),
        ];
    }
}
