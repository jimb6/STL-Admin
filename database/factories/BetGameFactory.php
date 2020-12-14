<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetGameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
//            'game_name' => $this->faker->text(255),
//            'game_days' => [],
//            'game_abbreviation' => $this->faker->text(255),
//            'game_prize' => $this->faker->randomNumber(2),
        ];
    }
}
