<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Cluster;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class  UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'address_id' => Address::all()->random()->id,
            'contact_number' => $this->faker->phoneNumber,
            'birthdate' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Others']),
            'session_status' => $this->faker->boolean,
            'cluster_id' => Cluster::all()->random()->id,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),


//            'name', 'birthdate', 'gender', 'address_id', 'contact_number', 'email', 'cluster_id', 'password'
        ];
    }
}
