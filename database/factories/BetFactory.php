<?php

namespace Database\Factories;

use App\Models\Bet;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class BetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Bet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'voided' => $this->faker->boolean,
            'rumbled' => $this->faker->boolean,
            'combination' => $this->faker->text(255),
            'amount' => $this->faker->randomNumber(2),
            'bet_game_id' => \App\Models\BetGame::all()->random()->id,
            'bet_transaction_id' => \App\Models\BetTransaction::all()->random()->id,
        ];
    }
}
