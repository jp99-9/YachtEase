<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Type;
use App\Models\Location;
use App\Models\StorageBox;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {

        Item::create([
            'name' => 'Martillo',
            'description' => 'Martillo de 1kg',
            'quantity' => 1,
            'image' => '',
            'brand' => 'Stanley',
            'minimum_recommended' => 1,
            'qr_code' => '1234567890',
            'type_id' => 1,
            'location_id' => 1,
            'storage_box_id' => 4,
        ]);

        if (Type::count() === 0) {
            Type::factory()->count(3)->create();
        }

        if (Location::count() === 0) {
            Location::factory()->count(5)->create();
        }

        if (StorageBox::count() === 0) {
            StorageBox::factory()->count(5)->create();
        }

        Item::factory(0)->create();
    }
}
