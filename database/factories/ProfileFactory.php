<?php

namespace Database\Factories;

use App\Models\Boat;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{

    protected $model = Profile::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'avatar' => $this->faker->imageUrl(100, 100, 'people'),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
            'user_id' => User::inRandomOrder()->first()->id, 
            'boat_id' => Boat::inRandomOrder()->first()->id, 
        ];
    }
}
