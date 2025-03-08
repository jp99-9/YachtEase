<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Type;
use App\Models\Location;
use App\Models\StorageBox;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;
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
            'quantity' => $this->faker->numberBetween(1, 100),
            'image' => $this->faker->imageUrl(640, 480, 'technics'),
            'brand' =>$this->faker->word(),
            'minimum_recommended' => $this->faker->numberBetween(1, 10),
            'qr_code' => $this->faker->uuid(),
            'type_id' => Type::inRandomOrder()->first()->id, 
            'location_id' => Location::inRandomOrder()->first()->id, 
            'storage_box_id' => StorageBox::inRandomOrder()->first()->id, 
        ];
    }
}
