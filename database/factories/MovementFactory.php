<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Profile;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movement>
 */
class MovementFactory extends Factory
{

    protected $model = Movement::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 20),
            'movement_date' => $this->faker->dateTime(),
            'reason' => $this->faker->sentence(),
            'observations' => $this->faker->optional()->sentence(),
            'profile_id' => Profile::inRandomOrder()->first()->id, 
            'location_id' => Location::inRandomOrder()->first()->id, 
            'item_id' => Item::inRandomOrder()->first()->id, 
        ];
    }
}
