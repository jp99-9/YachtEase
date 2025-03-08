<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\StorageBox;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StorageBox>
 */
class StorageBoxFactory extends Factory
{

    protected $model = StorageBox::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'capacity' => $this->faker->numberBetween(10, 100),
            'location_id' => Location::inRandomOrder()->first()->id, 
        ];
    }
}
