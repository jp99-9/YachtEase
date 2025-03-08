<?php

namespace Database\Factories;

use App\Models\Boat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Boat>
 */
class BoatFactory extends Factory
{

    protected $model = Boat::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => $this->faker->word(),
            'house' => $this->faker->company(),
            'size' => $this->faker->randomElement(['20m', '30m', '40m']),
            'incorporation_date' => $this->faker->date(),
            'password' => Hash::make('password123'),
            'unique_code' => strtoupper($this->faker->bothify('????-#####')),
        ];
    }
}
